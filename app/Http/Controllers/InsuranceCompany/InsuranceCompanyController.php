<?php

namespace App\Http\Controllers\InsuranceCompany;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\InsuranceCompany;
use App\Models\AssistanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class InsuranceCompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('insurance.approved');
    }

    /**
     * Show insurance company dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $insurance = InsuranceCompany::where('user_id', $user->id)->firstOrFail();

        // Statistics
        $totalRequests = AssistanceRequest::where('insurance_company_id', $insurance->id)->count();
        $pendingRequests = AssistanceRequest::where('insurance_company_id', $insurance->id)
            ->where('status', 'pending')
            ->count();
        $completedRequests = AssistanceRequest::where('insurance_company_id', $insurance->id)
            ->where('status', 'completed')
            ->count();
        $inProgressRequests = AssistanceRequest::where('insurance_company_id', $insurance->id)
            ->whereIn('status', ['assigned', 'in_progress'])
            ->count();

        // Recent requests
        $recentRequests = AssistanceRequest::where('insurance_company_id', $insurance->id)
            ->with(['user', 'mechanic.user'])
            ->latest()
            ->take(10)
            ->get();

        return view('insurance-company.dashboard', compact(
            'insurance',
            'totalRequests',
            'pendingRequests',
            'completedRequests',
            'inProgressRequests',
            'recentRequests'
        ));
    }

    /**
     * Show all requests from users with this insurance.
     */
    public function requests()
    {
        $user = Auth::user();
        $insurance = InsuranceCompany::where('user_id', $user->id)->firstOrFail();

        $requests = AssistanceRequest::where('insurance_company_id', $insurance->id)
            ->with(['user', 'mechanic.user'])
            ->latest()
            ->paginate(15);

        return view('insurance-company.requests', compact('insurance', 'requests'));
    }

    /**
     * Show specific request details.
     */
    public function showRequest($id)
    {
        $user = Auth::user();
        $insurance = InsuranceCompany::where('user_id', $user->id)->firstOrFail();

        $request = AssistanceRequest::where('id', $id)
            ->where('insurance_company_id', $insurance->id)
            ->with(['user', 'mechanic.user'])
            ->firstOrFail();

        return view('insurance-company.request-details', compact('insurance', 'request'));
    }

    /**
     * Generate receipt for completed request.
     */
    public function receipt($id)
    {
        $user = Auth::user();
        $insurance = InsuranceCompany::where('user_id', $user->id)->firstOrFail();

        $request = AssistanceRequest::where('id', $id)
            ->where('insurance_company_id', $insurance->id)
            ->where('status', 'completed')
            ->with(['user', 'mechanic.user'])
            ->firstOrFail();

        return view('insurance-company.receipt', compact('insurance', 'request'));
    }

    /**
     * Show insurance company profile.
     */
    public function profile()
    {
        $user = Auth::user();
        $insurance = InsuranceCompany::where('user_id', $user->id)->firstOrFail();

        return view('insurance-company.profile', compact('user', 'insurance'));
    }

    /**
     * Update insurance company profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $insurance = InsuranceCompany::where('user_id', $user->id)->firstOrFail();

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($insurance->profile_picture) {
                $oldPath = storage_path('app/public/' . $insurance->profile_picture);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // Store new profile picture
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $validated['profile_picture'] = $path;
        }

        // Update insurance company info
        $insurance->update($validated);

        return redirect()->route('insurance_company.profile')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Update insurance company password.
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

        // Check if new password is same as current password
        if (Hash::check($validated['password'], $user->password)) {
            return back()->withErrors(['password' => 'New password cannot be the same as current password.']);
        }

        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return redirect()->route('insurance_company.profile')
            ->with('success', 'Password updated successfully!');
    }
}
