<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Request #{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }} - AutoCar</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&libraries=places"></script>
    <style>
        body { background: #000; color: #fff; margin: 0; font-family: Arial, sans-serif; }

        .track-container { max-width: 1400px; margin: 0 auto; padding: 30px 20px; }

        .track-header {
            background: #1a1a1a;
            padding: 30px;
            border-radius: 8px;
            border: 2px solid #f8c300;
            margin-bottom: 30px;
        }

        .track-header h1 {
            margin: 0 0 10px;
            font-size: 2rem;
            color: #f8c300;
        }

        .track-header p {
            margin: 0;
            color: #888;
            font-size: 1.1rem;
        }

        .track-layout {
            display: grid;
            grid-template-columns: 1fr 450px;
            gap: 30px;
        }

        .map-section {
            background: #1a1a1a;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid #333;
            height: 700px;
            position: relative;
        }

        #trackingMap {
            width: 100%;
            height: 100%;
        }

        .map-loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            z-index: 10;
        }

        .map-loading i {
            font-size: 3rem;
            color: #f8c300;
            margin-bottom: 15px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .details-section {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .info-card {
            background: #1a1a1a;
            padding: 25px;
            border-radius: 8px;
            border: 2px solid #333;
        }

        .info-card h3 {
            margin: 0 0 20px;
            color: #f8c300;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-card h3 i {
            font-size: 1.5rem;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-pending { background: #ffa726; color: #000; }
        .status-assigned { background: #42a5f5; color: #fff; }
        .status-in_progress { background: #66bb6a; color: #fff; }
        .status-completed { background: #4caf50; color: #fff; }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #333;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #888;
            font-size: 0.9rem;
        }

        .info-value {
            color: #fff;
            font-weight: bold;
            text-align: right;
        }

        .eta-card {
            background: linear-gradient(135deg, #f8c300 0%, #ffa726 100%);
            color: #000;
            padding: 30px;
            border-radius: 8px;
            text-align: center;
        }

        .eta-card h3 {
            margin: 0 0 15px;
            font-size: 1.2rem;
            color: #000;
        }

        .eta-time {
            font-size: 3rem;
            font-weight: bold;
            margin: 10px 0;
        }

        .eta-distance {
            font-size: 1.1rem;
            opacity: 0.8;
        }

        .mechanic-info {
            display: flex;
            gap: 15px;
            padding: 15px;
            background: #222;
            border-radius: 5px;
        }

        .mechanic-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #f8c300;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            font-weight: bold;
            color: #000;
        }

        .mechanic-details {
            flex: 1;
        }

        .mechanic-details h4 {
            margin: 0 0 5px;
            color: #fff;
            font-size: 1.1rem;
        }

        .mechanic-details p {
            margin: 0;
            color: #888;
            font-size: 0.9rem;
        }

        .rating {
            color: #ffa726;
            font-size: 0.9rem;
        }

        .pending-message {
            text-align: center;
            padding: 40px 20px;
            background: #1a1a1a;
            border-radius: 8px;
            border: 2px dashed #f8c300;
        }

        .pending-message i {
            font-size: 4rem;
            color: #f8c300;
            margin-bottom: 20px;
        }

        .pending-message h3 {
            color: #fff;
            margin: 0 0 10px;
        }

        .pending-message p {
            color: #888;
            margin: 0;
        }

        .action-btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-back {
            background: #333;
            color: #fff;
            border: 2px solid #555;
        }

        .btn-back:hover {
            background: #444;
            border-color: #666;
        }

        .btn-cancel {
            background: transparent;
            color: #f44336;
            border: 2px solid #f44336;
        }

        .btn-cancel:hover {
            background: #f44336;
            color: #fff;
        }

        /* Rating Modal */
        .rating-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .rating-modal.show {
            display: flex;
        }

        .rating-modal-content {
            background: #1a1a1a;
            border: 2px solid #f8c300;
            border-radius: 10px;
            padding: 40px;
            max-width: 500px;
            width: 90%;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .rating-modal h2 {
            color: #f8c300;
            margin: 0 0 10px;
            text-align: center;
        }

        .rating-modal-subtitle {
            color: #888;
            text-align: center;
            margin-bottom: 30px;
        }

        .star-rating {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 30px;
        }

        .star-rating i {
            font-size: 3rem;
            color: #333;
            cursor: pointer;
            transition: all 0.2s;
        }

        .star-rating i:hover,
        .star-rating i.active {
            color: #ffa726;
            transform: scale(1.2);
        }

        .rating-form textarea {
            width: 100%;
            padding: 15px;
            background: #222;
            border: 2px solid #333;
            color: #fff;
            border-radius: 5px;
            font-size: 1rem;
            min-height: 120px;
            margin-bottom: 20px;
            font-family: Arial, sans-serif;
            resize: vertical;
        }

        .rating-form textarea:focus {
            outline: none;
            border-color: #f8c300;
        }

        .rating-form-buttons {
            display: flex;
            gap: 15px;
        }

        .btn-submit-rating {
            flex: 1;
            padding: 15px;
            background: #f8c300;
            color: #000;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .btn-submit-rating:hover {
            background: #fff;
            transform: translateY(-2px);
        }

        .btn-skip-rating {
            flex: 1;
            padding: 15px;
            background: transparent;
            color: #888;
            border: 2px solid #333;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .btn-skip-rating:hover {
            background: #333;
            color: #fff;
        }

        /* Custom Notification */
        .custom-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #1a1a1a;
            border: 2px solid #f8c300;
            border-radius: 8px;
            padding: 20px 30px;
            min-width: 300px;
            max-width: 500px;
            z-index: 10000;
            display: none;
            animation: slideInRight 0.3s ease-out;
            box-shadow: 0 4px 20px rgba(248, 195, 0, 0.3);
        }

        .custom-notification.show {
            display: block;
        }

        .custom-notification.success {
            border-color: #4caf50;
            box-shadow: 0 4px 20px rgba(76, 175, 80, 0.3);
        }

        .custom-notification.error {
            border-color: #f44336;
            box-shadow: 0 4px 20px rgba(244, 67, 54, 0.3);
        }

        .notification-content {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .notification-icon {
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .notification-icon.success {
            color: #4caf50;
        }

        .notification-icon.error {
            color: #f44336;
        }

        .notification-icon.info {
            color: #f8c300;
        }

        .notification-message {
            color: #fff;
            font-size: 1rem;
            flex: 1;
        }

        .notification-close {
            background: none;
            border: none;
            color: #888;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0;
            margin-left: 10px;
            transition: color 0.3s;
        }

        .notification-close:hover {
            color: #fff;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @media (max-width: 1024px) {
            .track-layout {
                grid-template-columns: 1fr;
            }

            .map-section {
                height: 500px;
            }
        }
    </style>
</head>
<body>
    <x-user-header />

    <div class="track-container">
        <!-- Header -->
        <div class="track-header">
            <h1><i class="fas fa-map-marked-alt"></i> Track Request #{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}</h1>
            <p>Real-time tracking and status updates for your assistance request</p>
        </div>

        <!-- Main Layout -->
        <div class="track-layout">
            <!-- Map Section -->
            <div class="map-section">
                <div class="map-loading" id="mapLoading">
                    <i class="fas fa-spinner"></i>
                    <p>Loading map...</p>
                </div>
                <div id="trackingMap"></div>
            </div>

            <!-- Details Section -->
            <div class="details-section">
                <!-- ETA Card (Only show when mechanic is on the way) -->
                @if($request->status === 'in_progress')
                    <div class="eta-card">
                        <h3><i class="fas fa-clock"></i> Estimated Arrival</h3>
                        <div class="eta-time" id="etaTime">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                        <div class="eta-distance" id="etaDistance">Calculating route...</div>
                    </div>
                @endif

                <!-- Request Status -->
                <div class="info-card">
                    <h3><i class="fas fa-info-circle"></i> Request Status</h3>
                    <div class="info-row">
                        <span class="info-label">Current Status</span>
                        <span class="info-value">
                            <span class="status-badge status-{{ $request->status }}">
                                {{ str_replace('_', ' ', ucfirst($request->status)) }}
                            </span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Breakdown Type</span>
                        <span class="info-value">{{ $request->breakdown_type }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Submitted</span>
                        <span class="info-value">{{ $request->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                </div>

                <!-- Vehicle Details -->
                <div class="info-card">
                    <h3><i class="fas fa-car"></i> Vehicle Details</h3>
                    <div class="info-row">
                        <span class="info-label">Plate Number</span>
                        <span class="info-value">{{ $request->plate_number }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Vehicle Brand</span>
                        <span class="info-value">{{ $request->vehicle_make ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Vehicle Model</span>
                        <span class="info-value">{{ $request->vehicle_model ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Insurance</span>
                        <span class="info-value">{{ $request->insuranceCompany->company_name ?? 'N/A' }}</span>
                    </div>
                </div>

                <!-- Status Messages -->
                @if($request->status === 'assigned' && $request->mechanic)
                    <div class="info-card" style="background: linear-gradient(135deg, #1a472a 0%, #1a1a1a 100%); border-color: #2e7d46;">
                        <h3><i class="fas fa-check-circle" style="color: #4caf50;"></i> Mechanic Assigned</h3>
                        <div style="padding: 15px; text-align: center; color: #a5d6a7;">
                            <p style="font-size: 1.1rem; margin-bottom: 10px;"><strong>{{ $request->mechanic->user->name }}</strong> has accepted your request!</p>
                            <p style="color: #81c784;"><i class="fas fa-info-circle"></i> Waiting for mechanic to start heading to your location...</p>
                            <p style="color: #888; font-size: 0.9rem; margin-top: 10px;">Real-time tracking will begin once the mechanic is on the way.</p>
                        </div>
                    </div>
                @endif

                <!-- Mechanic Info (Only show if in progress or completed) -->
                @if($request->mechanic && in_array($request->status, ['in_progress', 'completed']))
                    <div class="info-card">
                        <h3><i class="fas fa-user-cog"></i> Assigned Mechanic</h3>
                        <div class="mechanic-info">
                            <div class="mechanic-avatar">
                                {{ strtoupper(substr($request->mechanic->user->name, 0, 1)) }}
                            </div>
                            <div class="mechanic-details">
                                <h4>{{ $request->mechanic->user->name }}</h4>
                                <p><i class="fas fa-phone"></i> {{ $request->mechanic->phone_number }}</p>
                                <div class="rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($request->mechanic->rating))
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                    <span>{{ number_format($request->mechanic->rating, 1) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="pending-message">
                        <i class="fas fa-hourglass-half"></i>
                        <h3>Waiting for Mechanic</h3>
                        <p>Your request is being reviewed. A mechanic will be assigned soon.</p>
                    </div>
                @endif

                <!-- Location -->
                <div class="info-card">
                    <h3><i class="fas fa-map-marker-alt"></i> Your Location</h3>
                    <div class="info-row">
                        <span class="info-value" style="text-align: left; word-break: break-word;">
                            {{ $request->location_address }}
                        </span>
                    </div>
                </div>

                <!-- Actions -->
                <button class="action-btn btn-back" onclick="window.location.href='{{ route('user.my.requests') }}'">
                    <i class="fas fa-arrow-left"></i> Back to My Requests
                </button>

                @if($request->status === 'completed' && !$request->mechanic_rating)
                    <button class="action-btn" style="background: #f8c300; color: #000;" onclick="showRatingModal()">
                        <i class="fas fa-star"></i> Rate Mechanic
                    </button>
                @endif

                @if($request->status === 'pending')
                    <button class="action-btn btn-cancel" onclick="cancelRequest()">
                        <i class="fas fa-times"></i> Cancel Request
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Rating Modal -->
    @if($request->status === 'completed' && !$request->mechanic_rating && $request->mechanic)
    <div class="rating-modal" id="ratingModal">
        <div class="rating-modal-content">
            <h2><i class="fas fa-star"></i> Rate Your Experience</h2>
            <p class="rating-modal-subtitle">How was your service with {{ $request->mechanic->user->name }}?</p>
            
            <form id="ratingForm" class="rating-form">
                @csrf
                <div class="star-rating" id="starRating">
                    <i class="far fa-star" data-rating="1"></i>
                    <i class="far fa-star" data-rating="2"></i>
                    <i class="far fa-star" data-rating="3"></i>
                    <i class="far fa-star" data-rating="4"></i>
                    <i class="far fa-star" data-rating="5"></i>
                </div>
                
                <textarea 
                    name="review" 
                    id="reviewText" 
                    placeholder="Share your experience... (optional)"
                    maxlength="500"
                ></textarea>
                
                <div class="rating-form-buttons">
                    <button type="button" class="btn-skip-rating" onclick="closeRatingModal()">
                        Skip
                    </button>
                    <button type="submit" class="btn-submit-rating" id="submitRatingBtn" disabled>
                        Submit Rating
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Custom Notification -->
    <div class="custom-notification" id="customNotification">
        <div class="notification-content">
            <i class="notification-icon" id="notificationIcon"></i>
            <span class="notification-message" id="notificationMessage"></span>
            <button class="notification-close" onclick="hideNotification()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <script>
        let map, userMarker, mechanicMarker, routeLine;
        let directionsService, directionsRenderer;
        const requestId = {{ $request->id }};
        const userLat = {{ $request->latitude ?? 3.1390 }};
        const userLng = {{ $request->longitude ?? 101.6869 }};
        const mechanicLat = {{ $request->mechanic->latitude ?? 'null' }};
        const mechanicLng = {{ $request->mechanic->longitude ?? 'null' }};
        const requestStatus = '{{ $request->status }}';

        // Initialize map
        function initMap() {
            try {
                map = new google.maps.Map(document.getElementById('trackingMap'), {
                    center: { lat: userLat, lng: userLng },
                    zoom: 13,
                    styles: [
                        { elementType: "geometry", stylers: [{ color: "#242f3e" }] },
                        { elementType: "labels.text.stroke", stylers: [{ color: "#242f3e" }] },
                        { elementType: "labels.text.fill", stylers: [{ color: "#746855" }] },
                        {
                            featureType: "road",
                            elementType: "geometry",
                            stylers: [{ color: "#38414e" }]
                        },
                        {
                            featureType: "road",
                            elementType: "geometry.stroke",
                            stylers: [{ color: "#212a37" }]
                        }
                    ]
                });

                // Add user marker
                userMarker = new google.maps.Marker({
                    position: { lat: userLat, lng: userLng },
                    map: map,
                    title: 'Your Location',
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 12,
                        fillColor: '#4285F4',
                        fillOpacity: 1,
                        strokeColor: '#fff',
                        strokeWeight: 3
                    }
                });

                // Add info window for user
                const userInfoWindow = new google.maps.InfoWindow({
                    content: '<div style="color: #000; padding: 10px;"><strong>Your Location</strong><br>' + '{{ $request->location_address }}' + '</div>'
                });
                userMarker.addListener('click', () => userInfoWindow.open(map, userMarker));

                // Only show mechanic marker and route when mechanic is on the way (in_progress)
                if (mechanicLat && mechanicLng && requestStatus === 'in_progress') {
                    addMechanicMarker();
                    calculateRoute();
                    
                    // Auto-refresh every 30 seconds for real-time tracking
                    setInterval(refreshMechanicLocation, 30000);
                }

                document.getElementById('mapLoading').style.display = 'none';
            } catch (error) {
                console.error('Map initialization error:', error);
                document.getElementById('mapLoading').innerHTML = '<i class="fas fa-exclamation-triangle"></i><p>Map unavailable</p>';
            }
        }

        // Add mechanic marker
        function addMechanicMarker() {
            mechanicMarker = new google.maps.Marker({
                position: { lat: mechanicLat, lng: mechanicLng },
                map: map,
                title: 'Mechanic Location',
                icon: {
                    url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24">
                            <path fill="#F8C300" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                        </svg>
                    `),
                    scaledSize: new google.maps.Size(40, 40)
                },
                animation: google.maps.Animation.BOUNCE
            });

            const mechanicInfoWindow = new google.maps.InfoWindow({
                content: '<div style="color: #000; padding: 10px;"><strong>Mechanic Location</strong><br>{{ $request->mechanic->user->name ?? "Mechanic" }}</div>'
            });
            mechanicMarker.addListener('click', () => mechanicInfoWindow.open(map, mechanicMarker));

            // Fit bounds to show both markers
            const bounds = new google.maps.LatLngBounds();
            bounds.extend(userMarker.getPosition());
            bounds.extend(mechanicMarker.getPosition());
            map.fitBounds(bounds);
        }

        // Calculate route using Google Directions API
        function calculateRoute() {
            if (!mechanicLat || !mechanicLng) return;

            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer({
                map: map,
                suppressMarkers: true, // We have custom markers
                polylineOptions: {
                    strokeColor: '#F8C300',
                    strokeWeight: 5,
                    strokeOpacity: 0.8
                }
            });

            const request = {
                origin: { lat: mechanicLat, lng: mechanicLng },
                destination: { lat: userLat, lng: userLng },
                travelMode: google.maps.TravelMode.DRIVING,
                drivingOptions: {
                    departureTime: new Date(),
                    trafficModel: google.maps.TrafficModel.BEST_GUESS
                }
            };

            directionsService.route(request, function(result, status) {
                if (status === 'OK') {
                    directionsRenderer.setDirections(result);

                    // Extract duration and distance
                    const route = result.routes[0].legs[0];
                    const duration = route.duration_in_traffic ? route.duration_in_traffic.text : route.duration.text;
                    const distance = route.distance.text;

                    document.getElementById('etaTime').textContent = duration;
                    document.getElementById('etaDistance').textContent = distance + ' away';
                } else {
                    console.error('Directions request failed:', status);
                    document.getElementById('etaTime').innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
                    document.getElementById('etaDistance').textContent = 'Unable to calculate route';
                }
            });
        }

        // Refresh mechanic location (for real-time tracking)
        function refreshMechanicLocation() {
            // TODO: Fetch updated mechanic location from server
            // For now, recalculate route with current location
            calculateRoute();
        }

        // Cancel request
        function cancelRequest() {
            if (confirm('Are you sure you want to cancel this request?')) {
                fetch('{{ url("user/request") }}/' + requestId + '/cancel', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Request cancelled successfully', 'success');
                        setTimeout(() => {
                            window.location.href = '{{ route('user.my.requests') }}';
                        }, 1500);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }

        // Initialize map on page load
        window.addEventListener('load', initMap);

        // Custom Notification System
        function showNotification(message, type = 'info') {
            const notification = document.getElementById('customNotification');
            const icon = document.getElementById('notificationIcon');
            const messageEl = document.getElementById('notificationMessage');
            
            // Reset classes
            notification.className = 'custom-notification show';
            icon.className = 'notification-icon';
            
            // Set type-specific styling
            if (type === 'success') {
                notification.classList.add('success');
                icon.classList.add('success', 'fas', 'fa-check-circle');
            } else if (type === 'error') {
                notification.classList.add('error');
                icon.classList.add('error', 'fas', 'fa-exclamation-circle');
            } else {
                icon.classList.add('info', 'fas', 'fa-info-circle');
            }
            
            messageEl.textContent = message;
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                hideNotification();
            }, 5000);
        }

        function hideNotification() {
            const notification = document.getElementById('customNotification');
            notification.classList.remove('show');
        }

        // Rating functionality
        let selectedRating = 0;

        function showRatingModal() {
            document.getElementById('ratingModal').classList.add('show');
        }

        function closeRatingModal() {
            document.getElementById('ratingModal').classList.remove('show');
        }

        // Star rating interaction
        const stars = document.querySelectorAll('.star-rating i');
        const submitBtn = document.getElementById('submitRatingBtn');

        stars.forEach(star => {
            star.addEventListener('click', function() {
                selectedRating = parseInt(this.getAttribute('data-rating'));
                updateStars(selectedRating);
                submitBtn.disabled = false;
            });

            star.addEventListener('mouseenter', function() {
                const rating = parseInt(this.getAttribute('data-rating'));
                updateStars(rating);
            });
        });

        document.querySelector('.star-rating').addEventListener('mouseleave', function() {
            updateStars(selectedRating);
        });

        function updateStars(rating) {
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.remove('far');
                    star.classList.add('fas', 'active');
                } else {
                    star.classList.remove('fas', 'active');
                    star.classList.add('far');
                }
            });
        }

        // Submit rating
        document.getElementById('ratingForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const review = document.getElementById('reviewText').value;
            
            if (selectedRating === 0) {
                showNotification('Please select a rating', 'error');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';

            fetch('{{ url("user/request") }}/' + requestId + '/rate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    rating: selectedRating,
                    review: review
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Thank you for your rating!', 'success');
                    closeRatingModal();
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showNotification(data.message || 'Failed to submit rating', 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Submit Rating';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred. Please try again.', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Submit Rating';
            });
        });
    </script>
</body>
</html>
