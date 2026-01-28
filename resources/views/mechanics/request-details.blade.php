<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Details #{{ $request->id }} - AutoCar Mechanic</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&callback=initMap" async defer></script>
    <style>
        body { background: #000; color: #fff; margin: 0; font-family: Arial, sans-serif; }
        .container { max-width: 1400px; margin: 0 auto; padding: 30px 20px; }
        
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #f8c300; }
        .page-header h1 { margin: 0; font-size: 2rem; }
        .breadcrumb { color: #888; font-size: 0.9rem; margin-bottom: 5px; }
        .breadcrumb a { color: #f8c300; text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }
        
        .alert { padding: 15px 20px; margin-bottom: 30px; border-radius: 5px; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #4caf50; color: #fff; }
        .alert-error { background: #f44336; color: #fff; }
        
        .content-grid { display: grid; grid-template-columns: 350px 1fr; gap: 30px; margin-bottom: 30px; }
        
        /* Request Card */
        .request-card { background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%); padding: 30px; border-radius: 15px; border: 2px solid #333; text-align: center; height: fit-content; position: sticky; top: 20px; box-shadow: 0 4px 15px rgba(248, 195, 0, 0.1); }
        .request-icon { width: 100px; height: 100px; border-radius: 50%; margin: 0 auto 20px; border: 5px solid #f8c300; display: flex; align-items: center; justify-content: center; font-size: 3rem; background: #000; }
        .request-id { color: #f8c300; font-size: 1.5rem; margin: 0 0 10px; font-weight: bold; }
        .request-status { 
            display: inline-block; 
            padding: 6px 12px; 
            border-radius: 4px; 
            font-size: 0.75rem; 
            font-weight: bold; 
            text-transform: uppercase; 
            min-width: 100px; 
            text-align: center;
            margin-bottom: 20px; 
        }
        .request-status.assigned { background: #f8c300; color: #000; }
        .request-status.in_progress { background: #9c27b0; color: #fff; }
        .request-status.completed { background: #4caf50; color: #fff; }
        .request-date { color: #888; font-size: 0.9rem; margin: 10px 0; }
        
        .action-buttons { display: flex; flex-direction: column; gap: 10px; margin-top: 30px; }
        .btn { padding: 15px; border-radius: 5px; border: none; font-weight: bold; cursor: pointer; transition: all 0.3s; text-transform: uppercase; display: flex; align-items: center; justify-content: center; gap: 10px; text-decoration: none; }
        .btn-primary { background: #f8c300; color: #000; }
        .btn-primary:hover { background: #fff; transform: translateY(-2px); }
        .btn-success { background: #4caf50; color: #fff; }
        .btn-success:hover { background: #45a049; transform: translateY(-2px); }
        .btn-complete { background: #2196f3; color: #fff; }
        .btn-complete:hover { background: #0b7dda; transform: translateY(-2px); }
        .btn-secondary { background: #333; color: #fff; border: 2px solid #555; }
        .btn-secondary:hover { background: #444; border-color: #666; }
        
        /* Content Cards */
        .content-area { display: flex; flex-direction: column; gap: 30px; }
        .content-card { background: #1a1a1a; border-radius: 8px; padding: 30px; border: 2px solid #333; }
        .content-card h2 { color: #fff; font-size: 1.5rem; margin: 0 0 20px; padding-bottom: 15px; border-bottom: 2px solid #f8c300; display: flex; align-items: center; gap: 10px; }
        .content-card h2 i { color: #f8c300; }
        
        .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 25px; }
        .info-item { display: flex; flex-direction: column; gap: 8px; }
        .info-label { color: #888; font-size: 0.85rem; text-transform: uppercase; display: flex; align-items: center; gap: 8px; letter-spacing: 0.5px; }
        .info-label i { color: #f8c300; width: 16px; }
        .info-value { color: #fff; font-size: 1.1rem; font-weight: bold; padding: 12px; background: #222; border-radius: 5px; border: 1px solid #333; }
        .info-value.full-width { grid-column: 1 / -1; }
        
        .map-container { width: 100%; height: 400px; border-radius: 8px; overflow: hidden; border: 2px solid #333; margin-top: 20px; }
        
        .status-form { display: flex; gap: 15px; align-items: end; margin-top: 20px; }
        .status-form select { flex: 1; padding: 12px; background: #222; border: 2px solid #333; color: #fff; border-radius: 5px; font-size: 1rem; }
        .status-form select:focus { outline: none; border-color: #f8c300; }
        .status-form button { flex: 0 0 200px; }
        
        @media (max-width: 968px) {
            .content-grid { grid-template-columns: 1fr; }
            .request-card { position: relative; top: 0; }
            .info-grid { grid-template-columns: 1fr; }
            .status-form { flex-direction: column; }
            .status-form button { width: 100%; }
        }
    </style>
</head>
<body>
    @include('components.mechanic-header')
    
    <div class="container">
        <div class="page-header">
            <div>
                <h1>Job Details - Request #{{ $request->id }}</h1>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="content-grid">
            <!-- Request Card -->
            <div class="request-card">
                <div class="request-icon">
                    <i class="fas fa-wrench"></i>
                </div>
                
                <div class="request-id">Request #{{ $request->id }}</div>
                <span class="request-status {{ $request->status }}">
                    {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                </span>
                <div class="request-date">
                    <i class="fas fa-calendar-alt"></i> {{ $request->created_at->format('M d, Y') }}<br>
                    <i class="fas fa-clock"></i> {{ $request->created_at->format('g:i A') }}
                </div>

                <div class="action-buttons">
                    @if($request->latitude && $request->longitude && $mechanic->latitude && $mechanic->longitude)
                        <button class="btn btn-primary" onclick="showRoute()" id="routeBtn">
                            <i class="fas fa-route"></i> Show Route
                        </button>
                        <button class="btn btn-primary" onclick="clearRoute()" id="clearRouteBtn" style="display: none;">
                            <i class="fas fa-times"></i> Clear Route
                        </button>
                    @endif
                    
                    @if($request->status === 'assigned')
                        <form action="{{ route('mechanic.request.status', $request->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="in_progress">
                            <button type="submit" class="btn btn-success" style="width: 100%;">
                                <i class="fas fa-play"></i> Start Job
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('mechanic.assigned_jobs') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Jobs
                    </a>
                </div>
            </div>

            <!-- Content Area -->
            <div class="content-area">
                <!-- Customer Information -->
                <div class="content-card">
                    <h2><i class="fas fa-user"></i> Customer Information</h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-user"></i> Name
                            </div>
                            <div class="info-value">{{ $request->user->name }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-envelope"></i> Email
                            </div>
                            <div class="info-value">{{ $request->user->email }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-phone"></i> Phone
                            </div>
                            <div class="info-value">{{ $request->phone_number ?? 'Not provided' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-shield-alt"></i> Insurance
                            </div>
                            <div class="info-value">{{ $request->insurance_name ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Vehicle & Service Information -->
                <div class="content-card">
                    <h2><i class="fas fa-car"></i> Vehicle & Service Details</h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-car"></i> Vehicle Brand
                            </div>
                            <div class="info-value">{{ $request->vehicle_make ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-car-side"></i> Vehicle Model
                            </div>
                            <div class="info-value">{{ $request->vehicle_model ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-id-card"></i> Plate Number
                            </div>
                            <div class="info-value">{{ $request->plate_number ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-wrench"></i> Service Type
                            </div>
                            <div class="info-value">{{ ucfirst($request->breakdown_type) }}</div>
                        </div>
                        @if($request->notes)
                        <div class="info-item" style="grid-column: 1 / -1;">
                            <div class="info-label">
                                <i class="fas fa-comment-alt"></i> Issue Description
                            </div>
                            <div class="info-value">{{ $request->notes }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Location & Map -->
                <div class="content-card">
                    <h2><i class="fas fa-map-marker-alt"></i> Location</h2>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-location-arrow"></i> Address
                        </div>
                        <div class="info-value full-width">{{ $request->location_address }}</div>
                    </div>

                    @if($request->latitude && $request->longitude)
                    <div class="map-container" id="map"></div>
                    @else
                    <div style="text-align: center; padding: 40px; color: #888;">
                        <i class="fas fa-map-marker-slash" style="font-size: 3rem; margin-bottom: 10px;"></i>
                        <p>Location coordinates not available</p>
                    </div>
                    @endif
                </div>

                <!-- Update Job Status -->
                @if($request->status !== 'completed')
                <div class="content-card">
                    <h2><i class="fas fa-tasks"></i> Update Job Status</h2>
                    <p style="color: #888; margin-bottom: 20px;">Change the status of this job as you progress through the work.</p>
                    
                    <form action="{{ route('mechanic.request.status', $request->id) }}" method="POST" class="status-form">
                        @csrf
                        @method('PUT')
                        
                        <div style="flex: 1;">
                            <label style="color: #f8c300; font-size: 0.9rem; margin-bottom: 8px; display: block; text-transform: uppercase; font-weight: bold;">
                                Select New Status
                            </label>
                            <select name="status" required>
                                <option value="">-- Select Status --</option>
                                @if($request->status === 'assigned')
                                    <option value="in_progress">In Progress</option>
                                @endif
                                @if($request->status === 'in_progress')
                                    <option value="completed">Completed</option>
                                @endif
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-complete">
                            <i class="fas fa-check-circle"></i> Update Status
                        </button>
                    </form>
                </div>
                @else
                <div class="content-card">
                    <h2><i class="fas fa-check-circle"></i> Job Completed</h2>
                    <div style="text-align: center; padding: 40px; color: #4caf50;">
                        <i class="fas fa-check-circle" style="font-size: 4rem; margin-bottom: 20px;"></i>
                        <h3 style="margin: 0 0 10px; font-size: 1.5rem;">This job has been completed!</h3>
                        <p style="color: #888; margin: 0;">Great work! You can view this job in your history.</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-bottom">
            <div class="container footer-flex">
                <div class="copyright">
                    &copy; <span id="year"></span> Auto Car Repair. All Rights Reserved.
                </div>
                <div class="footer-phone">
                    CALL TODAY: <span>{{ config('site.phone') }}</span>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('year').textContent = new Date().getFullYear();

        // Google Maps
        let map;
        let customerMarker;
        let mechanicMarker;
        let directionsRenderer;
        let directionsService;
        let routeShown = false;
        
        function initMap() {
            @if($request->latitude && $request->longitude)
            const customerLocation = { lat: {{ $request->latitude }}, lng: {{ $request->longitude }} };
            
            @if($mechanic->latitude && $mechanic->longitude)
            const mechanicLocation = { lat: {{ $mechanic->latitude }}, lng: {{ $mechanic->longitude }} };
            @endif
            
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 15,
                center: customerLocation,
                styles: [
                    { elementType: 'geometry', stylers: [{ color: '#242f3e' }] },
                    { elementType: 'labels.text.stroke', stylers: [{ color: '#242f3e' }] },
                    { elementType: 'labels.text.fill', stylers: [{ color: '#746855' }] },
                    { featureType: 'road', elementType: 'geometry', stylers: [{ color: '#38414e' }] },
                    { featureType: 'road', elementType: 'geometry.stroke', stylers: [{ color: '#212a37' }] },
                    { featureType: 'road.highway', elementType: 'geometry', stylers: [{ color: '#746855' }] },
                    { featureType: 'water', elementType: 'geometry', stylers: [{ color: '#17263c' }] }
                ]
            });
            
            // Customer marker with custom icon
            customerMarker = new google.maps.Marker({
                position: customerLocation,
                map: map,
                title: 'Customer Location',
                icon: {
                    url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="50" viewBox="0 0 40 50">
                            <ellipse cx="20" cy="46" rx="7" ry="2" fill="rgba(0,0,0,0.3)"/>
                            <path d="M20 0C11.716 0 5 6.716 5 15c0 8.284 15 30 15 30s15-21.716 15-30C35 6.716 28.284 0 20 0z" fill="#f44336" stroke="#fff" stroke-width="1"/>
                            <circle cx="20" cy="15" r="11" fill="#fff"/>
                            <circle cx="20" cy="12" r="3.5" fill="#f44336"/>
                            <path d="M20 16.5c-3.5 0-6 2-6 4.5v1.5c0 .5.5 1 1 1h10c.5 0 1-.5 1-1V21c0-2.5-2.5-4.5-6-4.5z" fill="#f44336"/>
                        </svg>`),
                    scaledSize: new google.maps.Size(40, 50),
                    anchor: new google.maps.Point(20, 50)
                }
            });
            
            const customerInfoWindow = new google.maps.InfoWindow({
                content: '<div style="color: #000;"><strong>Customer Location</strong><br>{{ $request->user->name }}</div>'
            });
            
            customerMarker.addListener('click', () => {
                customerInfoWindow.open(map, customerMarker);
            });
            
            @if($mechanic->latitude && $mechanic->longitude)
            // Mechanic marker with custom icon
            mechanicMarker = new google.maps.Marker({
                position: mechanicLocation,
                map: map,
                title: 'Your Location',
                icon: {
                    url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="50" viewBox="0 0 40 50">
                            <ellipse cx="20" cy="46" rx="7" ry="2" fill="rgba(0,0,0,0.3)"/>
                            <path d="M20 0C11.716 0 5 6.716 5 15c0 8.284 15 30 15 30s15-21.716 15-30C35 6.716 28.284 0 20 0z" fill="#9c27b0" stroke="#fff" stroke-width="1"/>
                            <circle cx="20" cy="15" r="11" fill="#fff"/>
                            <path d="M22 10l-1.5 1.5 2 2-6 6-2-2L13 19l3 3 8-8-2-2L23 11l-1-1zm-8 8l1.5 1.5L13 22l-1.5-1.5L13 18z" fill="#9c27b0" stroke="#9c27b0" stroke-width="0.5"/>
                            <rect x="14" y="10" width="2" height="6" rx="1" fill="#9c27b0" transform="rotate(45 15 13)"/>
                        </svg>`),
                    scaledSize: new google.maps.Size(40, 50),
                    anchor: new google.maps.Point(20, 50)
                }
            });
            
            const mechanicInfoWindow = new google.maps.InfoWindow({
                content: '<div style="color: #000;"><strong>Your Location</strong><br>{{ $mechanic->user->name }}</div>'
            });
            
            mechanicMarker.addListener('click', () => {
                mechanicInfoWindow.open(map, mechanicMarker);
            });
            
            // Fit map to show both markers
            const bounds = new google.maps.LatLngBounds();
            bounds.extend(customerLocation);
            bounds.extend(mechanicLocation);
            map.fitBounds(bounds);
            
            // Initialize directions service and renderer
            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer({
                map: null,
                suppressMarkers: true,
                polylineOptions: {
                    strokeColor: '#f8c300',
                    strokeWeight: 5,
                    strokeOpacity: 0.8
                }
            });
            @else
            // Only customer location
            map.setCenter(customerLocation);
            map.setZoom(15);
            @endif
            @endif
        }
        
        // Show route function
        function showRoute() {
            if (routeShown) {
                return;
            }
            
            @if($mechanic->latitude && $mechanic->longitude && $request->latitude && $request->longitude)
            const mechanicLocation = { lat: {{ $mechanic->latitude }}, lng: {{ $mechanic->longitude }} };
            const customerLocation = { lat: {{ $request->latitude }}, lng: {{ $request->longitude }} };
            
            const request = {
                origin: mechanicLocation,
                destination: customerLocation,
                travelMode: google.maps.TravelMode.DRIVING
            };
            
            directionsService.route(request, function(result, status) {
                if (status === google.maps.DirectionsStatus.OK) {
                    directionsRenderer.setMap(map);
                    directionsRenderer.setDirections(result);
                    routeShown = true;
                    
                    // Show clear button, hide show button
                    document.getElementById('routeBtn').style.display = 'none';
                    document.getElementById('clearRouteBtn').style.display = 'flex';
                } else {
                    alert('Directions request failed: ' + status);
                }
            });
            @endif
        }
        
        // Clear route function
        function clearRoute() {
            if (!routeShown) {
                return;
            }
            
            directionsRenderer.setMap(null);
            routeShown = false;
            
            @if($mechanic->latitude && $mechanic->longitude && $request->latitude && $request->longitude)
            // Reset map view to show both markers
            const bounds = new google.maps.LatLngBounds();
            bounds.extend({ lat: {{ $request->latitude }}, lng: {{ $request->longitude }} });
            bounds.extend({ lat: {{ $mechanic->latitude }}, lng: {{ $mechanic->longitude }} });
            map.fitBounds(bounds);
            @else
            map.setCenter({ lat: {{ $request->latitude }}, lng: {{ $request->longitude }} });
            map.setZoom(15);
            @endif
            
            // Show show button, hide clear button
            document.getElementById('routeBtn').style.display = 'flex';
            document.getElementById('clearRouteBtn').style.display = 'none';
        }
    </script>
</body>
</html>
