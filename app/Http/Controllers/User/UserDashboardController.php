<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AssistanceRequest;
use App\Models\InsuranceCompany;
use App\Models\Mechanic;
use App\Services\OpenAIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the user dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get statistics
        $totalRequests = AssistanceRequest::where('user_id', $user->id)->count();
        $pendingRequests = AssistanceRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();
        $inProgressRequests = AssistanceRequest::where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->count();
        $completedRequests = AssistanceRequest::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        // Get recent assistance requests
        $recentRequests = AssistanceRequest::where('user_id', $user->id)
            ->with(['mechanic', 'insuranceCompany'])
            ->latest()
            ->take(10)
            ->get();

        // Mock activities - you can create an Activity model and log user activities
        $activities = collect([
            (object) [
                'icon' => 'check-circle',
                'message' => 'Your assistance request #001 has been completed',
                'created_at' => now()->subHours(2),
            ],
            (object) [
                'icon' => 'user-plus',
                'message' => 'Welcome to AutoCar!',
                'created_at' => now()->subDays(1),
            ],
        ]);

        return view('users.index', compact(
            'totalRequests',
            'pendingRequests',
            'inProgressRequests',
            'completedRequests',
            'recentRequests',
            'activities'
        ));
    }

    /**
     * Show the request assistance form.
     */
    public function requestAssistance()
    {
        $insuranceCompanies = InsuranceCompany::where('approval_status', 'approved')->get();
        return view('users.request-assistance', compact('insuranceCompanies'));
    }

    /**
     * Get mechanics by insurance company.
     */
    public function getMechanics(Request $request, $insurance_company_id)
    {
        $mechanics = Mechanic::where('insurance_company_id', $insurance_company_id)
            ->where('approval_status', 'approved')
            ->with('user')
            ->get()
            ->toArray();

        // Get user's location if provided
        $userLat = $request->query('lat');
        $userLng = $request->query('lng');

        // Calculate distance for each mechanic
        if ($userLat && $userLng) {
            foreach ($mechanics as &$mechanic) {
                if ($mechanic['latitude'] && $mechanic['longitude']) {
                    $mechanic['distance'] = $this->calculateDistance(
                        $userLat, 
                        $userLng, 
                        $mechanic['latitude'], 
                        $mechanic['longitude']
                    );
                } else {
                    $mechanic['distance'] = 999;
                }
            }
        }

        // Use OpenAI to recommend the best mechanic
        if (!empty($mechanics)) {
            $openAIService = new OpenAIService();
            
            $context = [
                'breakdown_type' => $request->query('breakdown_type', 'general'),
                'urgency' => $request->query('urgency', 'normal'),
            ];
            
            $mechanics = $openAIService->recommendMechanic($mechanics, $context);
        }

        return response()->json($mechanics);
    }

    /**
     * Calculate distance between two coordinates using Haversine formula.
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Store a new assistance request.
     */
    public function storeAssistanceRequest(Request $request)
    {
        $validated = $request->validate([
            'insurance_company_id' => 'required|exists:insurance_companies,id',
            'mechanic_id' => 'nullable|exists:mechanics,id',
            'breakdown_type' => 'required|string',
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'plate_number' => 'required|string|max:20',
            'vehicle_make' => 'required|string|max:255',
            'vehicle_model' => 'nullable|string|max:255',
            'location_address' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'distance_fee' => 'nullable|numeric',
            'night_surcharge' => 'nullable|numeric',
            'total_cost' => 'nullable|numeric',
        ]);

        // Get insurance company name
        $insuranceCompany = InsuranceCompany::findOrFail($validated['insurance_company_id']);
        $validated['insurance_name'] = $insuranceCompany->company_name;
        
        $validated['user_id'] = Auth::id();
        
        // If mechanic is selected by user, set status to 'assigned', otherwise 'pending'
        if (!empty($validated['mechanic_id'])) {
            $validated['status'] = 'assigned';
        } else {
            $validated['status'] = 'pending';
        }

        AssistanceRequest::create($validated);

        return redirect()->route('user.dashboard')
            ->with('success', 'Assistance request submitted successfully!');
    }

    /**
     * Show user's current requests (pending, assigned, in_progress only).
     */
    public function myRequests()
    {
        $requests = AssistanceRequest::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'assigned', 'in_progress'])
            ->with(['mechanic.user', 'insuranceCompany'])
            ->latest()
            ->paginate(15);

        return view('users.my-requests', compact('requests'));
    }

    /**
     * Show user's request history.
     */
    public function requestHistory()
    {
        $requests = AssistanceRequest::where('user_id', Auth::id())
            ->whereIn('status', ['completed', 'cancelled'])
            ->with(['mechanic.user', 'insuranceCompany'])
            ->latest()
            ->paginate(15);

        return view('users.request-history', compact('requests'));
    }

    /**
     * Track assistance request with real-time mechanic location and ETA.
     */
    public function trackRequest($id)
    {
        $request = AssistanceRequest::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['mechanic.user', 'insuranceCompany'])
            ->firstOrFail();

        return view('users.track', compact('request'));
    }

    /**
     * Cancel assistance request.
     */
    public function cancelRequest($id)
    {
        $request = AssistanceRequest::where('id', $id)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'assigned'])
            ->firstOrFail();

        $request->update([
            'status' => 'cancelled'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Request cancelled successfully'
        ]);
    }

    /**
     * Rate completed request.
     */
    public function rateRequest(Request $request, $id)
    {
        $assistanceRequest = AssistanceRequest::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->whereNull('mechanic_rating')
            ->firstOrFail();

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:500'
        ]);

        $assistanceRequest->update([
            'mechanic_rating' => $validated['rating'],
            'mechanic_review' => $validated['review'] ?? null,
            'rated_at' => now()
        ]);

        // Update mechanic's average rating
        if ($assistanceRequest->mechanic_id) {
            $mechanic = \App\Models\Mechanic::find($assistanceRequest->mechanic_id);
            if ($mechanic) {
                $averageRating = \App\Models\AssistanceRequest::where('mechanic_id', $mechanic->id)
                    ->whereNotNull('mechanic_rating')
                    ->avg('mechanic_rating');
                
                $mechanic->update(['rating' => round($averageRating, 2)]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Rating submitted successfully'
        ]);
    }

    /**
     * View completed request details with rating option.
     */
    public function viewCompletedRequest($id)
    {
        $request = AssistanceRequest::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->with(['mechanic.user', 'insuranceCompany'])
            ->firstOrFail();

        return view('users.request-details', compact('request'));
    }

    /**
     * Show receipt for completed request.
     */
    public function receipt($id)
    {
        $request = AssistanceRequest::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->with(['mechanic.user', 'insuranceCompany'])
            ->firstOrFail();

        return view('users.receipt', compact('request'));
    }

    /**
     * Show user profile.
     */
    public function profile()
    {
        $user = Auth::user()->load('driver');
        
        // Get user statistics
        $totalRequests = AssistanceRequest::where('user_id', $user->id)->count();
        $pendingRequests = AssistanceRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();
        $completedRequests = AssistanceRequest::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();
        
        $recentRequests = AssistanceRequest::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
        
        $activities = collect();
        $rating = '4.5';

        return view('users.show', compact(
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
     * Update user profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user()->load('driver');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update user name and email
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Prepare driver data - start with existing data or empty array
        $driverData = [];
        
        // Only update phone if provided
        if (isset($validated['phone'])) {
            $driverData['phone_number'] = $validated['phone'];
        }
        
        // Only update address if provided
        if (isset($validated['address'])) {
            $driverData['address'] = $validated['address'];
        }

        // Handle profile picture upload
        if ($request->hasFile('profile_picture') && $request->file('profile_picture')->isValid()) {
            // Delete old profile picture if exists
            if ($user->driver && $user->driver->profile_picture) {
                \Storage::disk('public')->delete($user->driver->profile_picture);
            }

            // Store new profile picture
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $driverData['profile_picture'] = $path;
        }

        // Update driver record - always update if there's data
        if (!empty($driverData)) {
            $driver = $user->driver()->updateOrCreate(
                ['user_id' => $user->id],
                $driverData
            );
            
            // Force refresh the relationship
            $user->load('driver');
        }

        return redirect()->route('user.profile')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Update user password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        // Check if current password matches
        if (!\Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Check if new password is the same as current password
        if (\Hash::check($validated['password'], $user->password)) {
            return back()->withErrors(['password' => 'New password must be different from current password.']);
        }

        $user->update([
            'password' => \Hash::make($validated['password'])
        ]);

        return redirect()->route('user.profile')
            ->with('success', 'Password updated successfully!');
    }

    /**
     * Reverse geocode coordinates to address using Google Geocoding API.
     * Falls back to OSM Nominatim if Google fails.
     */
    public function reverseGeocode(Request $request)
    {
        $validated = $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $apiKey = config('services.google_maps.api_key', 'AIzaSyCziCeDmXEcKcayGX8CkuDWQ_OBctigFW8');

        try {
            // Try Google Geocoding API first
            $response = \Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
                'latlng' => $validated['lat'] . ',' . $validated['lng'],
                'key' => $apiKey,
                'region' => 'MY', // Prioritize Malaysia results
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['status'] === 'OK' && isset($data['results'][0])) {
                    return response()->json([
                        'success' => true,
                        'address' => $data['results'][0]['formatted_address'],
                        'data' => $data['results'][0]
                    ]);
                } else if ($data['status'] === 'OVER_QUERY_LIMIT') {
                    \Log::warning('Google Geocoding API quota exceeded, falling back to OSM');
                    return $this->fallbackToOSM($validated['lat'], $validated['lng']);
                } else {
                    \Log::warning('Google Geocoding returned status: ' . $data['status']);
                    return $this->fallbackToOSM($validated['lat'], $validated['lng']);
                }
            } else {
                \Log::error('Google Geocoding API failed with status: ' . $response->status());
                return $this->fallbackToOSM($validated['lat'], $validated['lng']);
            }
        } catch (\Exception $e) {
            \Log::error('Google Geocoding error: ' . $e->getMessage());
            return $this->fallbackToOSM($validated['lat'], $validated['lng']);
        }
    }

    /**
     * Fallback reverse geocoding using OSM Nominatim.
     */
    private function fallbackToOSM($lat, $lng)
    {
        try {
            $response = \Http::withHeaders([
                'User-Agent' => 'AutoCar-App',
                'Accept' => 'application/json',
            ])->get('https://nominatim.openstreetmap.org/reverse', [
                'format' => 'json',
                'lat' => $lat,
                'lon' => $lng,
                'zoom' => 18,
                'addressdetails' => 1,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['display_name'])) {
                    return response()->json([
                        'success' => true,
                        'address' => $data['display_name'],
                        'data' => $data
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'error' => 'No address found for these coordinates'
                    ], 404);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to fetch address from geocoding service'
                ], $response->status());
            }
        } catch (\Exception $e) {
            \Log::error('OSM Nominatim fallback error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'All geocoding services failed'
            ], 500);
        }
    }
}
