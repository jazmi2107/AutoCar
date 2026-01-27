<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\Mechanic;
use App\Models\AssistanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MechanicController extends Controller
{
    /**
     * Display the mechanic dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $mechanic = Mechanic::where('user_id', $user->id)->with('insuranceCompany')->first();

        if (!$mechanic) {
            return redirect()->route('home')->with('error', 'Mechanic profile not found.');
        }

        // Get assigned requests
        $assignedRequests = AssistanceRequest::where('mechanic_id', $mechanic->id)
            ->whereIn('status', ['assigned', 'in_progress'])
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get completed requests count
        $completedCount = AssistanceRequest::where('mechanic_id', $mechanic->id)
            ->where('status', 'completed')
            ->count();

        // Get pending requests count
        $pendingCount = $assignedRequests->count();

        // Get recent completed requests
        $recentCompleted = AssistanceRequest::where('mechanic_id', $mechanic->id)
            ->where('status', 'completed')
            ->with(['user'])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('mechanics.dashboard', compact(
            'mechanic',
            'assignedRequests',
            'completedCount',
            'pendingCount',
            'recentCompleted'
        ));
    }

    /**
     * Display the mechanic profile page.
     */
    public function profile()
    {
        $user = Auth::user();
        $mechanic = Mechanic::where('user_id', $user->id)->with('insuranceCompany')->first();

        if (!$mechanic) {
            return redirect()->route('home')->with('error', 'Mechanic profile not found.');
        }

        return view('mechanics.profile', compact('user', 'mechanic'));
    }

    /**
     * Update mechanic profile information.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $mechanic = Mechanic::where('user_id', $user->id)->first();

        if (!$mechanic) {
            return redirect()->route('home')->with('error', 'Mechanic profile not found.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'years_of_experience' => 'required|integer|min:0',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        // Update user name
        $user->update([
            'name' => $validated['name'],
        ]);

        // Prepare mechanic update data
        $mechanicData = [
            'phone_number' => $validated['phone_number'],
            'address' => $validated['address'],
            'years_of_experience' => $validated['years_of_experience'],
        ];

        // Add location data if provided
        if ($request->filled('latitude') && $request->filled('longitude')) {
            $mechanicData['latitude'] = $validated['latitude'];
            $mechanicData['longitude'] = $validated['longitude'];
        }

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($mechanic->profile_picture) {
                $oldPath = storage_path('app/public/' . $mechanic->profile_picture);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // Store new profile picture
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $mechanicData['profile_picture'] = $path;
        }

        // Update mechanic info
        $mechanic->update($mechanicData);

        return redirect()->route('mechanic.profile')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Update mechanic location (latitude/longitude).
     */
    public function updateLocation(Request $request)
    {
        $user = Auth::user();
        $mechanic = Mechanic::where('user_id', $user->id)->first();

        if (!$mechanic) {
            return response()->json(['success' => false, 'message' => 'Mechanic profile not found.'], 404);
        }

        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address' => 'nullable|string|max:500',
        ]);

        $updateData = [
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
        ];
        
        // Add address if provided
        if (isset($validated['address'])) {
            $updateData['address'] = $validated['address'];
        }

        $mechanic->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Location updated successfully!',
            'latitude' => $mechanic->latitude,
            'longitude' => $mechanic->longitude,
            'address' => $mechanic->address,
        ]);
    }

    /**
     * Update mechanic password.
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

        return redirect()->route('mechanic.profile')
            ->with('success', 'Password updated successfully!');
    }

    /**
     * Get assistance request details for mechanic.
     */
    public function showRequest($id)
    {
        $user = Auth::user();
        $mechanic = Mechanic::where('user_id', $user->id)->first();

        if (!$mechanic) {
            return redirect()->route('home')->with('error', 'Mechanic profile not found.');
        }

        $request = AssistanceRequest::where('id', $id)
            ->where('mechanic_id', $mechanic->id)
            ->with(['user.driver', 'insuranceCompany'])
            ->firstOrFail();

        return view('mechanics.request-details', compact('request', 'mechanic'));
    }

    /**
     * Update assistance request status.
     */
    public function updateRequestStatus(Request $request, $id)
    {
        $user = Auth::user();
        $mechanic = Mechanic::where('user_id', $user->id)->first();

        if (!$mechanic) {
            return back()->with('error', 'Mechanic profile not found.');
        }

        $assistanceRequest = AssistanceRequest::where('id', $id)
            ->where('mechanic_id', $mechanic->id)
            ->firstOrFail();

        $validated = $request->validate([
            'status' => 'required|in:in_progress,completed',
        ]);

        $assistanceRequest->update([
            'status' => $validated['status']
        ]);

        return redirect()->route('mechanic.request.show', $id)
            ->with('success', 'Request status updated successfully!');
    }

    /**
     * Show assigned jobs page.
     */
    public function assignedJobs()
    {
        $user = Auth::user();
        $mechanic = Mechanic::where('user_id', $user->id)->with('insuranceCompany')->first();

        if (!$mechanic) {
            return redirect()->route('home')->with('error', 'Mechanic profile not found.');
        }

        // Get assigned requests (waiting for mechanic approval)
        $assignedRequests = AssistanceRequest::where('mechanic_id', $mechanic->id)
            ->where('status', 'assigned')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get in-progress requests
        $inProgressRequests = AssistanceRequest::where('mechanic_id', $mechanic->id)
            ->where('status', 'in_progress')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mechanics.assigned_jobs', compact('mechanic', 'assignedRequests', 'inProgressRequests'));
    }

    /**
     * Approve assigned job.
     */
    public function approveJob($id)
    {
        $user = Auth::user();
        $mechanic = Mechanic::where('user_id', $user->id)->first();

        if (!$mechanic) {
            return back()->with('error', 'Mechanic profile not found.');
        }

        $assistanceRequest = AssistanceRequest::where('id', $id)
            ->where('mechanic_id', $mechanic->id)
            ->where('status', 'assigned')
            ->firstOrFail();

        return redirect()->route('mechanic.request.show', $id);
    }

    /**
     * Reject assigned job.
     */
    public function rejectJob($id)
    {
        $user = Auth::user();
        $mechanic = Mechanic::where('user_id', $user->id)->first();

        if (!$mechanic) {
            return back()->with('error', 'Mechanic profile not found.');
        }

        $assistanceRequest = AssistanceRequest::where('id', $id)
            ->where('mechanic_id', $mechanic->id)
            ->where('status', 'assigned')
            ->firstOrFail();

        // Remove mechanic assignment and return to pending
        $assistanceRequest->update([
            'mechanic_id' => null,
            'status' => 'pending'
        ]);

        return redirect()->route('mechanic.assigned_jobs')
            ->with('success', 'Job rejected successfully.');
    }

    /**
     * Show job history page.
     */
    public function jobHistory()
    {
        $user = Auth::user();
        $mechanic = Mechanic::where('user_id', $user->id)->with('insuranceCompany')->first();

        if (!$mechanic) {
            return redirect()->route('home')->with('error', 'Mechanic profile not found.');
        }

        // Get completed and cancelled requests
        $jobHistory = AssistanceRequest::where('mechanic_id', $mechanic->id)
            ->whereIn('status', ['completed', 'cancelled'])
            ->with('user')
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('mechanics.job_history', compact('mechanic', 'jobHistory'));
    }
}
