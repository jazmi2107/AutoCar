<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admin;
use App\Models\Mechanic;
use App\Models\InsuranceCompany;
use App\Models\AssistanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        // Add role check middleware if you have one
        // $this->middleware('role:admin');
    }

    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        $database = app('firebase.database');
        
        $totalUsers = 0;
        $totalMechanics = 0;
        $totalInsuranceCompanies = 0;
        $pendingMechanics = 0;
        $pendingInsurance = 0;
        
        $recentInsurance = collect();

        try {
            // Fetch all users once (optimization: use shallow=true if possible, but we need fields)
            $snapshot = $database->getReference('users')->getSnapshot();
            
            if ($snapshot->exists()) {
                foreach ($snapshot->getValue() as $uid => $data) {
                    $role = $data['role'] ?? 'user';
                    
                    if ($role === 'user') {
                        $totalUsers++;
                    } elseif ($role === 'mechanic') {
                        $totalMechanics++;
                        if (($data['approval_status'] ?? '') === 'pending') {
                            $pendingMechanics++;
                        }
                    } elseif ($role === 'insurance_company') {
                        $totalInsuranceCompanies++;
                        if (($data['approval_status'] ?? '') === 'pending') {
                            $pendingInsurance++;
                            
                            // Collect pending insurance for recent list
                            if ($recentInsurance->count() < 5) {
                                $data['id'] = $uid; // Append ID
                                // Recursively convert to object and handle dates
                                $obj = json_decode(json_encode($data));
                                if (isset($obj->created_at)) {
                                    try {
                                        $obj->created_at = \Illuminate\Support\Carbon::parse($obj->created_at);
                                    } catch (\Exception $e) {}
                                }
                                $recentInsurance->push($obj);
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Handle error (log it)
        }

        // For Assistance Requests, we might need another collection in RTDB or stick to MySQL if hybrid
        // Assuming we are moving to Firebase, we should count from 'assistance_requests' node
        $totalRequests = 0;
        $pendingRequests = 0;
        $completedRequests = 0;
        $recentRequests = collect();

        try {
             $reqSnapshot = $database->getReference('assistance_requests')->getSnapshot();
             if ($reqSnapshot->exists()) {
                 foreach ($reqSnapshot->getValue() as $key => $req) {
                     $totalRequests++;
                     $status = $req['status'] ?? 'pending';
                     if ($status === 'pending') $pendingRequests++;
                     if ($status === 'completed') $completedRequests++;
                     
                     if ($recentRequests->count() < 10) {
                         $req['id'] = $key;
                         // Recursively convert to object
                         $obj = json_decode(json_encode($req));
                         
                         // Ensure created_at is a Carbon instance for the view
                         if (isset($obj->created_at)) {
                             try {
                                 $obj->created_at = \Illuminate\Support\Carbon::parse($obj->created_at);
                             } catch (\Exception $e) {
                                 $obj->created_at = now();
                             }
                         } else {
                             $obj->created_at = now();
                         }

                         // Handle nested user and mechanic objects if they are just UIDs
                         // The view expects $request->user->name and $request->mechanic->user->name
                         // For now, let's just ensure they are objects if they exist
                         
                         $recentRequests->push($obj);
                     }
                 }
             }
        } catch (\Exception $e) {
             // Fallback to 0
        }

        // Sort recent requests by date
        $recentRequests = $recentRequests->sortByDesc('created_at');

        return view('admins.index', compact(
            'totalUsers',
            'totalMechanics',
            'totalInsuranceCompanies',
            'totalRequests',
            'pendingMechanics',
            'pendingInsurance',
            'pendingRequests',
            'completedRequests',
            'recentRequests',
            'recentInsurance'
        ));
    }

    /**
     * Show all users (role = user only).
     */
    public function users(Request $request)
    {
        $database = app('firebase.database');
        $usersCollection = collect();

        try {
            // Fetch all users and filter in PHP to avoid "Index not defined" error until rules are updated
            $snapshot = $database->getReference('users')->getSnapshot();

            if ($snapshot->exists()) {
                foreach ($snapshot->getValue() as $uid => $data) {
                    // Filter by role 'user'
                    if (($data['role'] ?? '') === 'user') {
                        $data['id'] = $uid;
                        
                        // Handle date conversion
                        $obj = json_decode(json_encode($data));
                        if (isset($obj->created_at)) {
                            try {
                                $obj->created_at = \Illuminate\Support\Carbon::parse($obj->created_at);
                            } catch (\Exception $e) {
                                $obj->created_at = now();
                            }
                        } else {
                            $obj->created_at = now();
                        }
                        
                        $usersCollection->push($obj);
                    }
                }
            }
        } catch (\Exception $e) {
            // Log error
        }

        // Search filter
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $usersCollection = $usersCollection->filter(function ($user) use ($search) {
                return str_contains(strtolower($user->name ?? ''), $search) || 
                       str_contains(strtolower($user->email ?? ''), $search);
            });
        }

        // Sort by created_at (latest) - assuming created_at is timestamp
        $usersCollection = $usersCollection->sortByDesc('created_at');

        // Manual Pagination
        $perPage = 15;
        $page = $request->input('page', 1);
        $users = new \Illuminate\Pagination\LengthAwarePaginator(
            $usersCollection->forPage($page, $perPage),
            $usersCollection->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admins.users.index', compact('users'));
    }

    /**
     * Show create user form.
     */
    public function createUser()
    {
        return view('admins.users.create');
    }

    /**     * Show user details.
     */
    public function showUser($id)
    {
        $user = User::withCount('assistanceRequests')->findOrFail($id);
        $recentRequests = $user->assistanceRequests()->latest()->take(5)->get();
        
        return view('admins.users.show', compact('user', 'recentRequests'));
    }

    /**
     * Store a new user.
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'user';
        $phone = $validated['phone'] ?? null;
        unset($validated['phone']);
        
        $user = User::create($validated);

        // Create driver record with phone number
        if ($phone) {
            $user->driver()->create([
                'phone_number' => $phone,
            ]);
        }

        return redirect()->route('admin.users')
            ->with('success', 'User created successfully!');
    }

    /**
     * Show edit user form.
     */
    public function editUser($id)
    {
        $user = User::with('driver')->findOrFail($id);
        return view('admins.users.edit', compact('user'));
    }

    /**
     * Update user.
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:user,mechanic,insurance,admin',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $validated['password'] = Hash::make($request->password);
        }

        $phone = $validated['phone'] ?? null;
        unset($validated['phone']);

        $user->update($validated);

        // Update or create driver record with phone number if role is user
        if ($user->role === 'user') {
            if ($user->driver) {
                $user->driver->update(['phone_number' => $phone]);
            } elseif ($phone) {
                $user->driver()->create(['phone_number' => $phone]);
            }
        }

        return redirect()->route('admin.users')
            ->with('success', 'User updated successfully!');
    }

    /**
     * Delete user.
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting own account
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account!');
        }

        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', 'User deleted successfully!');
    }

    /**
     * Show mechanic approvals page.
     */
    public function mechanicApprovals()
    {
        return redirect()->route('admin.approvals');
    }

    /**
     * Show all mechanics.
     */
    public function mechanics(Request $request)
    {
        // TODO: Implement Firebase fetching for mechanics list
        return redirect()->route('admin.dashboard')->with('info', 'Mechanic list is being migrated to Firebase.');
    }

    /**
     * Show mechanic details.
     */
    public function showMechanic($id)
    {
        // TODO: Implement Firebase fetching for mechanic details
        return redirect()->route('admin.mechanics')->with('info', 'Mechanic details are being migrated to Firebase.');
    }

    /**
     * Delete mechanic.
     */
    public function deleteMechanic($id)
    {
        // TODO: Implement Firebase deletion for mechanic
        return redirect()->route('admin.mechanics')->with('info', 'Mechanic deletion is being migrated to Firebase.');
    }

    /**
     * Show edit mechanic form.
     */
    public function editMechanic($id)
    {
        // TODO: Implement Firebase fetching for mechanic edit
        return redirect()->route('admin.mechanics')->with('info', 'Mechanic editing is being migrated to Firebase.');
    }

    /**
     * Show create mechanic form.
     */
    public function createMechanic()
    {
        $insuranceCompanies = InsuranceCompany::where('approval_status', 'approved')->get();
        return view('admins.mechanics.create', compact('insuranceCompanies'));
    }

    /**
     * Store new mechanic.
     */
    public function storeMechanic(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'nullable|string|max:20',
            'insurance_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'years_of_experience' => 'nullable|integer|min:0',
            'license_number' => 'nullable|string|max:100',
            'insurance_company_id' => 'nullable|exists:insurance_companies,id',
        ]);

        // Create user account
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'mechanic',
        ]);

        // Create mechanic record (mechanics don't need approval, default to available)
        $user->mechanic()->create([
            'phone_number' => $validated['phone_number'],
            'insurance_name' => $validated['insurance_name'],
            'address' => $validated['address'],
            'years_of_experience' => $validated['years_of_experience'] ?? 0,
            'license_number' => $validated['license_number'],
            'insurance_company_id' => $validated['insurance_company_id'],
            'availability_status' => 'available',
        ]);

        return redirect()->route('admin.mechanics')->with('success', 'Mechanic created successfully!');
    }

    /**
     * Update mechanic.
     */
    public function updateMechanic(Request $request, $id)
    {
        $mechanic = Mechanic::with('user')->findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $mechanic->user_id,
            'phone_number' => 'nullable|string|max:20',
            'insurance_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'years_of_experience' => 'nullable|integer|min:0',
            'license_number' => 'nullable|string|max:100',
            'insurance_company_id' => 'nullable|exists:insurance_companies,id',
            'availability_status' => 'required|in:available,busy,offline',
            'approval_status' => 'required|in:pending,approved,rejected',
        ]);

        // Update user information
        $mechanic->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update mechanic information
        $mechanic->update([
            'phone_number' => $validated['phone_number'],
            'insurance_name' => $validated['insurance_name'] ?? null,
            'address' => $validated['address'],
            'years_of_experience' => $validated['years_of_experience'],
            'license_number' => $validated['license_number'],
            'insurance_company_id' => $validated['insurance_company_id'],
            'availability_status' => $validated['availability_status'],
            'approval_status' => $validated['approval_status'],
        ]);

        return redirect()->route('admin.mechanics')->with('success', 'Mechanic updated successfully!');
    }

    /**
     * Show all insurance companies.
     */
    public function insurance(Request $request)
    {
        // TODO: Implement Firebase fetching for insurance list
        return redirect()->route('admin.dashboard')->with('info', 'Insurance list is being migrated to Firebase.');
    }

    /**
     * Approve insurance company.
     */
    public function approveInsuranceLegacy($id)
    {
        // Deprecated, see approveInsurance at bottom
        return redirect()->route('admin.approvals');
    }

    /**
     * Reject insurance company.
     */
    public function rejectInsuranceLegacy(Request $request, $id)
    {
        // Deprecated, see rejectInsurance at bottom
        return redirect()->route('admin.approvals');
    }

    /**
     * Show insurance company details.
     */
    public function showInsurance($id)
    {
        // TODO: Implement Firebase fetching for insurance details
        return redirect()->route('admin.insurance')->with('info', 'Insurance details are being migrated to Firebase.');
    }

    /**
     * Show create insurance company form.
     */
    public function createInsurance()
    {
        return view('admins.insurance.create');
    }

    /**
     * Store new insurance company.
     */
    public function storeInsurance(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'registration_number' => 'required|string|max:100|unique:insurance_companies,registration_number',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'website' => 'nullable|url|max:255',
        ]);

        // Create user account
        $user = User::create([
            'name' => $validated['company_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'insurance_company',
        ]);

        // Create insurance company record (approved by default when created by admin)
        $user->insuranceCompany()->create([
            'company_name' => $validated['company_name'],
            'registration_number' => $validated['registration_number'],
            'phone_number' => $validated['phone_number'],
            'address' => $validated['address'],
            'website' => $validated['website'],
            'approval_status' => 'approved',
        ]);

        return redirect()->route('admin.insurance')->with('success', 'Insurance company created successfully!');
    }

    /**
     * Show edit insurance company form.
     */
    public function editInsurance($id)
    {
        $company = InsuranceCompany::with('user')->findOrFail($id);
        return view('admins.insurance.edit', compact('company'));
    }

    /**
     * Update insurance company.
     */
    public function updateInsurance(Request $request, $id)
    {
        $company = InsuranceCompany::with('user')->findOrFail($id);
        
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'registration_number' => 'required|string|max:100|unique:insurance_companies,registration_number,' . $id,
            'email' => 'required|email|unique:users,email,' . $company->user_id,
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'website' => 'nullable|url|max:255',
            'approval_status' => 'required|in:pending,approved,rejected',
        ]);

        // Update user information
        $company->user->update([
            'name' => $validated['company_name'],
            'email' => $validated['email'],
        ]);

        // Update insurance company information
        $company->update([
            'company_name' => $validated['company_name'],
            'registration_number' => $validated['registration_number'],
            'phone_number' => $validated['phone_number'],
            'address' => $validated['address'],
            'website' => $validated['website'],
            'approval_status' => $validated['approval_status'],
        ]);

        return redirect()->route('admin.insurance')->with('success', 'Insurance company updated successfully!');
    }

    /**
     * Delete insurance company.
     */
    public function deleteInsurance($id)
    {
        $company = InsuranceCompany::findOrFail($id);
        $user = $company->user;
        
        // Delete the insurance company record
        $company->delete();
        
        // Delete the user account as well
        if ($user) {
            $user->delete();
        }

        return redirect()->route('admin.insurance')->with('success', 'Insurance company deleted successfully!');
    }

    /**
     * Show all assistance requests.
     */
    public function requests(Request $request)
    {
        $query = AssistanceRequest::with(['user', 'mechanic.user', 'mechanic.insuranceCompany']);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%")
                  ->orWhere('plate_number', 'like', "%{$search}%")
                  ->orWhere('vehicle_model', 'like', "%{$search}%");
            });
        }

        $requests = $query->latest()->paginate(15);

        return view('admins.requests.index', compact('requests'));
    }

    /**
     * Show request details.
     */
    public function showRequest($id)
    {
        $request = AssistanceRequest::with(['user', 'mechanic.user', 'mechanic.insuranceCompany'])
            ->findOrFail($id);

        // Get available mechanics for assignment
        $mechanics = Mechanic::with(['user', 'insuranceCompany'])
            ->where('approval_status', 'approved')
            ->where('availability_status', 'available')
            ->get();

        return view('admins.requests.show', compact('request', 'mechanics'));
    }

    /**
     * Assign mechanic to request.
     */
    public function assignMechanic(Request $request, $id)
    {
        $assistanceRequest = AssistanceRequest::findOrFail($id);
        
        $validated = $request->validate([
            'mechanic_id' => 'required|exists:mechanics,id'
        ]);

        $assistanceRequest->update([
            'mechanic_id' => $validated['mechanic_id'],
            'status' => 'assigned'
        ]);

        return back()->with('success', 'Mechanic assigned successfully!');
    }

    /**
     * Update request status.
     */
    public function updateRequestStatus(Request $request, $id)
    {
        $assistanceRequest = AssistanceRequest::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,assigned,in_progress,completed,cancelled'
        ]);

        $assistanceRequest->update(['status' => $validated['status']]);

        return back()->with('success', 'Request status updated successfully!');
    }

    /**
     * Show admin profile.
     */
    public function profile()
    {
        $user = Auth::user();
        $admin = $user->admin;
        return view('admins.profile', compact('user', 'admin'));
    }

    /**
     * Update admin profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user()->load('admin');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update user table (name, email)
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Prepare admin data
        $adminData = [];
        
        // Only update phone_number if provided
        if (isset($validated['phone_number'])) {
            $adminData['phone_number'] = $validated['phone_number'];
        }

        // Handle profile picture upload
        if ($request->hasFile('profile_picture') && $request->file('profile_picture')->isValid()) {
            // Delete old profile picture if exists
            if ($user->admin && $user->admin->profile_picture) {
                \Storage::disk('public')->delete($user->admin->profile_picture);
            }

            // Store new profile picture
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $adminData['profile_picture'] = $path;
        }

        // Update or create admin record
        if (!empty($adminData)) {
            $admin = $user->admin()->updateOrCreate(
                ['user_id' => $user->id],
                $adminData
            );
            
            // Force refresh the relationship
            $user->load('admin');
        }

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Update admin password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        // Check if current password matches
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Check if new password is the same as current password
        if (Hash::check($validated['password'], $user->password)) {
            return back()->withErrors(['password' => 'New password must be different from current password.']);
        }

        $user->update(['password' => Hash::make($validated['password'])]);

        return back()->with('success', 'Password updated successfully!');
    }

    /**
     * Show unified approvals page.
     */
    public function approvals()
    {
        $database = app('firebase.database');
        
        $pendingMechanics = collect();
        $pendingInsurance = collect();
        
        try {
            // Fetch users from Firebase
            $snapshot = $database->getReference('users')->getSnapshot();
            
            if ($snapshot->exists()) {
                foreach ($snapshot->getValue() as $uid => $data) {
                    $role = $data['role'] ?? '';
                    $status = $data['approval_status'] ?? '';
                    
                    if ($status === 'pending') {
                        $data['id'] = $uid;
                        // Recursively convert to object
                        $obj = json_decode(json_encode($data));
                        
                        // Handle date
                        if (isset($obj->created_at)) {
                            try {
                                $obj->created_at = \Illuminate\Support\Carbon::parse($obj->created_at);
                            } catch (\Exception $e) {
                                $obj->created_at = now();
                            }
                        } else {
                            $obj->created_at = now();
                        }

                        // Ensure user relation structure for view compatibility
                        // The view expects $mechanic->user->name
                        // We'll simulate this structure
                        $userObj = clone $obj;
                        $obj->user = $userObj;

                        if ($role === 'mechanic') {
                            $pendingMechanics->push($obj);
                        } elseif ($role === 'insurance_company') {
                            $pendingInsurance->push($obj);
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Log error
        }

        // Sort by date
        $pendingMechanics = $pendingMechanics->sortByDesc('created_at');
        $pendingInsurance = $pendingInsurance->sortByDesc('created_at');

        // Paginate manually
        $perPage = 10;
        
        // Mechanics Pagination
        $mechanicsPage = request()->input('mechanics_page', 1);
        $pendingMechanics = new \Illuminate\Pagination\LengthAwarePaginator(
            $pendingMechanics->forPage($mechanicsPage, $perPage),
            $pendingMechanics->count(),
            $perPage,
            $mechanicsPage,
            ['path' => request()->url(), 'query' => request()->query(), 'pageName' => 'mechanics_page']
        );

        // Insurance Pagination
        $insurancePage = request()->input('insurance_page', 1);
        $pendingInsurance = new \Illuminate\Pagination\LengthAwarePaginator(
            $pendingInsurance->forPage($insurancePage, $perPage),
            $pendingInsurance->count(),
            $perPage,
            $insurancePage,
            ['path' => request()->url(), 'query' => request()->query(), 'pageName' => 'insurance_page']
        );

        return view('admins.approvals', compact('pendingMechanics', 'pendingInsurance'));
    }

    /**
     * Show pending insurance companies for approval.
     */
    public function insuranceApprovals()
    {
        return redirect()->route('admin.approvals');
    }

    /**
     * Show reports.
     */
    public function reports()
    {
        return view('admins.reports');
    }
    
    public function approveMechanic($id)
    {
        $database = app('firebase.database');
        try {
            $database->getReference('users/' . $id)->update([
                'approval_status' => 'approved'
            ]);
            return back()->with('success', 'Mechanic approved successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to approve mechanic: ' . $e->getMessage());
        }
    }

    public function rejectMechanic($id)
    {
        $database = app('firebase.database');
        try {
            $database->getReference('users/' . $id)->update([
                'approval_status' => 'rejected'
            ]);
            return back()->with('success', 'Mechanic rejected successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to reject mechanic: ' . $e->getMessage());
        }
    }

    public function approveInsurance($id)
    {
        $database = app('firebase.database');
        try {
            $database->getReference('users/' . $id)->update([
                'approval_status' => 'approved'
            ]);
            return back()->with('success', 'Insurance company approved successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to approve insurance company: ' . $e->getMessage());
        }
    }

    public function rejectInsurance($id)
    {
        $database = app('firebase.database');
        try {
            $database->getReference('users/' . $id)->update([
                'approval_status' => 'rejected'
            ]);
            return back()->with('success', 'Insurance company rejected successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to reject insurance company: ' . $e->getMessage());
        }
    }

    /**
     * Export reports.
     */
    public function exportReports()
    {
        return back()->with('info', 'Report export functionality is coming soon.');
    }
}
