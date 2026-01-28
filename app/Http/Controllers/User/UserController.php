<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AssistanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $database = app('firebase.database');
        $usersCollection = collect();

        try {
            $snapshot = $database->getReference('users')->getSnapshot();
            
            if ($snapshot->exists()) {
                foreach ($snapshot->getValue() as $uid => $data) {
                    $data['id'] = $uid;
                    $usersCollection->push((object)$data);
                }
            }
        } catch (\Exception $e) {
            // Log error
        }

        // Sort by latest
        $users = $usersCollection->sortByDesc('created_at');
        
        // Paginate
        $perPage = 15;
        $page = request()->input('page', 1);
        $users = new \Illuminate\Pagination\LengthAwarePaginator(
            $users->forPage($page, $perPage),
            $users->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('users.list', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'role' => 'required|in:user,mechanic,insurance_company,admin',
            'password' => 'required|string|min:8|confirmed',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'status' => 'nullable|in:active,inactive',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $validated['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
        }

        // Hash password
        $validated['password'] = Hash::make($validated['password']);
        $validated['status'] = $validated['status'] ?? 'active';

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        
        // Get user statistics
        $totalRequests = AssistanceRequest::where('user_id', $id)->count();
        $pendingRequests = AssistanceRequest::where('user_id', $id)
            ->where('status', 'pending')
            ->count();
        $completedRequests = AssistanceRequest::where('user_id', $id)
            ->where('status', 'completed')
            ->count();
        
        $recentRequests = AssistanceRequest::where('user_id', $id)
            ->latest()
            ->take(5)
            ->get();
        
        // Mock activities for now - you can create an Activity model later
        $recentActivities = collect();

        return view('users.show', compact(
            'user', 
            'totalRequests', 
            'pendingRequests', 
            'completedRequests',
            'recentRequests',
            'recentActivities'
        ));
    }

    /**
     * Display the specified user (alternative view).
     */
    public function view($id)
    {
        $user = User::findOrFail($id);
        
        // Get user statistics
        $totalRequests = AssistanceRequest::where('user_id', $id)->count();
        $pendingRequests = AssistanceRequest::where('user_id', $id)
            ->where('status', 'pending')
            ->count();
        $completedRequests = AssistanceRequest::where('user_id', $id)
            ->where('status', 'completed')
            ->count();
        
        $recentRequests = AssistanceRequest::where('user_id', $id)
            ->latest()
            ->take(5)
            ->get();
        
        $activities = collect();
        $rating = '4.5';

        return view('users.view', compact(
            'user',
            'totalRequests',
            'pendingRequests',
            'completedRequests',
            'recentRequests',
            'activities',
            'rating'
        ));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'required|string|max:20',
            'role' => 'required|in:user,mechanic,insurance_company,admin',
            'password' => 'nullable|string|min:8|confirmed',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'status' => 'nullable|in:active,inactive,suspended',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $validated['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
        }

        // Hash password if provided
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.edit', $user->id)
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Delete profile image if exists
        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}
