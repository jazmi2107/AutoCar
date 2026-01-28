<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request #{{ $request->id }} - AutoCar Admin</title>
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
        
        .profile-card { background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%); padding: 40px; border-radius: 15px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(248, 195, 0, 0.1); border: 2px solid #333; }
        .profile-header { display: flex; gap: 30px; align-items: center; margin-bottom: 30px; }
        .profile-avatar { width: 120px; height: 120px; border-radius: 50%; border: 4px solid #f8c300; object-fit: cover; flex-shrink: 0; }
        .profile-info h2 { margin: 0 0 10px 0; font-size: 2rem; color: #fff; }
        .profile-meta { display: flex; gap: 20px; flex-wrap: wrap; margin-top: 15px; }
        .profile-meta span { display: inline-flex; align-items: center; gap: 8px; padding: 8px 15px; background: rgba(248, 195, 0, 0.2); border-radius: 20px; font-size: 0.9rem; color: #f8c300; }
        
        .status-badge { 
            display: inline-block; 
            padding: 6px 12px; 
            border-radius: 4px; 
            font-size: 0.75rem; 
            font-weight: bold; 
            text-transform: uppercase; 
            min-width: 100px; 
            text-align: center;
        }
        .status-badge.pending { background: #ff9800; color: #000; }
        .status-badge.assigned { background: #f8c300; color: #000; }
        .status-badge.in-progress, .status-badge.in_progress { background: #9c27b0; color: #fff; }
        .status-badge.completed { background: #4caf50; color: #fff; }
        .status-badge.cancelled { background: #f44336; color: #fff; }
        
        .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .info-item { background: #1a1a1a; padding: 20px; border-radius: 8px; border-left: 4px solid #f8c300; }
        .info-item label { display: block; color: #888; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px; }
        .info-item .value { color: #fff; font-size: 1.1rem; word-break: break-word; }
        .info-item i { color: #f8c300; margin-right: 8px; }
        .info-item.full-width { grid-column: 1 / -1; }
        
        .section-title { font-size: 1.5rem; margin: 30px 0 20px 0; padding-bottom: 10px; border-bottom: 2px solid #f8c300; color: #f8c300; display: flex; align-items: center; gap: 10px; }
        
        .action-card { background: #1a1a1a; padding: 30px; border-radius: 8px; border: 2px solid #333; margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; color: #f8c300; font-weight: bold; margin-bottom: 10px; text-transform: uppercase; font-size: 0.9rem; }
        .form-group select { width: 100%; padding: 12px 15px; background: #222; border: 2px solid #333; color: #fff; border-radius: 5px; font-size: 1rem; }
        .form-group select:focus { outline: none; border-color: #f8c300; }
        
        #map { height: 400px; border-radius: 8px; margin-top: 15px; }
        
        .btn { display: inline-block; padding: 12px 30px; border-radius: 5px; text-decoration: none; font-weight: bold; transition: all 0.3s; cursor: pointer; border: none; text-align: center; font-size: 0.95rem; }
        .btn i { margin-right: 8px; }
        .btn-primary { background: #f8c300; color: #000; }
        .btn-primary:hover { background: #fff; }
        .btn-secondary { background: #333; color: #fff; border: 2px solid #555; }
        .btn-secondary:hover { background: #444; border-color: #666; }
        .btn:disabled { opacity: 0.5; cursor: not-allowed; }
        
        .action-buttons { display: flex; gap: 15px; margin-top: 30px; flex-wrap: wrap; }
        
        .alert { padding: 15px 20px; margin-bottom: 20px; border-radius: 5px; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #4caf50; color: #fff; }
        .alert-error { background: #f44336; color: #fff; }
    </style>
</head>
<body>
    @include('components.admin-header')

    <div class="container">
        <div class="page-header">
            <div>
                <div class="breadcrumb">
                    <a href="{{ route('admin.requests') }}"><i class="fas fa-clipboard-list"></i> Assistance Requests</a> / Request Details
                </div>
                <h1>Request #{{ $request->id }}</h1>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i>{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error"><i class="fas fa-exclamation-triangle"></i>{{ session('error') }}</div>
        @endif

        <!-- Profile Card -->
        <div class="profile-card">
            <div class="profile-header">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($request->name) }}&size=120&background=f8c300&color=000&bold=true" 
                     alt="{{ $request->name }}" 
                     class="profile-avatar">
                <div class="profile-info">
                    <h2>{{ $request->name }}</h2>
                    <span class="status-badge {{ strtolower(str_replace(' ', '-', $request->status)) }}">
                        {{ $request->status }}
                    </span>
                    <div class="profile-meta">
                        <span><i class="fas fa-hashtag"></i> Request ID: {{ $request->id }}</span>
                        <span><i class="fas fa-calendar-alt"></i> {{ $request->created_at->format('M d, Y h:i A') }}</span>
                        <span><i class="fas fa-tools"></i> {{ $request->breakdown_type }}</span>
                    </div>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <label><i class="fas fa-phone"></i> Phone Number</label>
                    <div class="value">{{ $request->phone_number }}</div>
                </div>

                <div class="info-item">
                    <label><i class="fas fa-envelope"></i> Email Address</label>
                    <div class="value">{{ $request->user->email ?? 'N/A' }}</div>
                </div>

                <div class="info-item">
                    <label><i class="fas fa-car"></i> Vehicle </label>
                    <div>
                                    <p class="user-name">{{ $request->vehicle_make ?? 'N/A' }}</p>
                                    <p class="user-info">{{ $request->vehicle_model }}</p>
                                </div>
                </div>
                
                <div class="info-item">
                    <label><i class="fas fa-id-card"></i> Plate Number</label>
                    <div class="value">{{ $request->plate_number }}</div>
                </div>

                <div class="info-item">
                    <label><i class="fas fa-shield-alt"></i> Insurance Company</label>
                    <div class="value">{{ $request->insuranceCompany->company_name ?? 'Not Specified' }}</div>
                </div>

                <div class="info-item">
                    <label><i class="fas fa-flag"></i> Request Status</label>
                    <div class="value">
                        <span class="status-badge {{ strtolower(str_replace(' ', '-', $request->status)) }}">
                            {{ ucfirst($request->status) }}
                        </span>
                    </div>
                </div>

                <div class="info-item full-width">
                    <label><i class="fas fa-map-marker-alt"></i> Breakdown Location</label>
                    <div class="value">{{ $request->location }}</div>
                    @if($request->latitude && $request->longitude)
                        <div id="map"></div>
                    @endif
                </div>

                @if($request->notes)
                <div class="info-item full-width">
                    <label><i class="fas fa-sticky-note"></i> Additional Notes</label>
                    <div class="value">{{ $request->notes }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Mechanic Assignment -->
        <h3 class="section-title"><i class="fas fa-user-cog"></i> Mechanic Assignment</h3>
        
        @if($request->mechanic)
            <div class="profile-card">
                <div class="info-grid">
                    <div class="info-item">
                        <label><i class="fas fa-user"></i> Mechanic Name</label>
                        <div class="value">{{ $request->mechanic->user->name }}</div>
                    </div>

                    <div class="info-item">
                        <label><i class="fas fa-phone"></i> Mechanic Phone</label>
                        <div class="value">{{ $request->mechanic->phone_number ?? 'N/A' }}</div>
                    </div>

                    <div class="info-item">
                        <label><i class="fas fa-briefcase"></i> Experience</label>
                        <div class="value">{{ $request->mechanic->years_of_experience ?? 0 }} years</div>
                    </div>

                    <div class="info-item">
                        <label><i class="fas fa-star"></i> Rating</label>
                        <div class="value">{{ number_format($request->mechanic->rating ?? 0, 1) }}/5.0</div>
                    </div>

                    <div class="info-item">
                        <label><i class="fas fa-shield-alt"></i> Insurance Company</label>
                        <div class="value">{{ $request->mechanic->insuranceCompany->company_name ?? 'N/A' }}</div>
                    </div>

                    <div class="info-item">
                        <label><i class="fas fa-id-card"></i> License Number</label>
                        <div class="value">{{ $request->mechanic->license_number ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-error" style="background: rgba(255, 152, 0, 0.2); color: #ff9800; border: 1px solid #ff9800;">
                <i class="fas fa-exclamation-triangle"></i>
                <div>No mechanic has been assigned to this request yet.</div>
            </div>
        @endif

        

        <!-- Timeline -->
        <h3 class="section-title"><i class="fas fa-clock"></i> Request Timeline</h3>
        <div class="info-grid">
            <div class="info-item">
                <label><i class="fas fa-calendar-plus"></i> Request Created</label>
                <div class="value">{{ $request->created_at->format('M d, Y h:i A') }}</div>
            </div>

            <div class="info-item">
                <label><i class="fas fa-calendar-check"></i> Last Updated</label>
                <div class="value">{{ $request->updated_at->format('M d, Y h:i A') }}</div>
            </div>

            <div class="info-item">
                <label><i class="fas fa-hourglass-half"></i> Time Elapsed</label>
                <div class="value">{{ $request->created_at->diffForHumans() }}</div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="{{ route('admin.requests') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Requests
            </a>
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

        // User Dropdown Toggle
        const userMenuBtn = document.getElementById('userMenuBtn');
        const userDropdown = document.getElementById('userDropdown');

        if (userMenuBtn && userDropdown) {
            userMenuBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdown.classList.toggle('show');
            });

            document.addEventListener('click', function(e) {
                if (!userMenuBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                    userDropdown.classList.remove('show');
                }
            });
        }

        // Google Maps
        let map;
        let marker;
        
        function initMap() {
            @if($request->latitude && $request->longitude)
                const location = { lat: {{ $request->latitude }}, lng: {{ $request->longitude }} };
                
                map = new google.maps.Map(document.getElementById('map'), {
                    center: location,
                    zoom: 15,
                    styles: [
                        { elementType: 'geometry', stylers: [{ color: '#242f3e' }] },
                        { elementType: 'labels.text.stroke', stylers: [{ color: '#242f3e' }] },
                        { elementType: 'labels.text.fill', stylers: [{ color: '#746855' }] },
                        {
                            featureType: 'administrative.locality',
                            elementType: 'labels.text.fill',
                            stylers: [{ color: '#d59563' }]
                        },
                        {
                            featureType: 'poi',
                            elementType: 'labels.text.fill',
                            stylers: [{ color: '#d59563' }]
                        },
                        {
                            featureType: 'poi.park',
                            elementType: 'geometry',
                            stylers: [{ color: '#263c3f' }]
                        },
                        {
                            featureType: 'poi.park',
                            elementType: 'labels.text.fill',
                            stylers: [{ color: '#6b9a76' }]
                        },
                        {
                            featureType: 'road',
                            elementType: 'geometry',
                            stylers: [{ color: '#38414e' }]
                        },
                        {
                            featureType: 'road',
                            elementType: 'geometry.stroke',
                            stylers: [{ color: '#212a37' }]
                        },
                        {
                            featureType: 'road',
                            elementType: 'labels.text.fill',
                            stylers: [{ color: '#9ca5b3' }]
                        },
                        {
                            featureType: 'road.highway',
                            elementType: 'geometry',
                            stylers: [{ color: '#746855' }]
                        },
                        {
                            featureType: 'road.highway',
                            elementType: 'geometry.stroke',
                            stylers: [{ color: '#1f2835' }]
                        },
                        {
                            featureType: 'road.highway',
                            elementType: 'labels.text.fill',
                            stylers: [{ color: '#f3d19c' }]
                        },
                        {
                            featureType: 'transit',
                            elementType: 'geometry',
                            stylers: [{ color: '#2f3948' }]
                        },
                        {
                            featureType: 'transit.station',
                            elementType: 'labels.text.fill',
                            stylers: [{ color: '#d59563' }]
                        },
                        {
                            featureType: 'water',
                            elementType: 'geometry',
                            stylers: [{ color: '#17263c' }]
                        },
                        {
                            featureType: 'water',
                            elementType: 'labels.text.fill',
                            stylers: [{ color: '#515c6d' }]
                        },
                        {
                            featureType: 'water',
                            elementType: 'labels.text.stroke',
                            stylers: [{ color: '#17263c' }]
                        }
                    ]
                });
                
                marker = new google.maps.Marker({
                    position: location,
                    map: map,
                    title: 'Breakdown Location',
                    icon: {
                        url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="50" viewBox="0 0 40 50">
                                <ellipse cx="20" cy="46" rx="7" ry="2" fill="rgba(0,0,0,0.3)"/>
                                <path d="M20 0C11.716 0 5 6.716 5 15c0 8.284 15 30 15 30s15-21.716 15-30C35 6.716 28.284 0 20 0z" fill="#f44336" stroke="#fff" stroke-width="1"/>
                                <circle cx="20" cy="15" r="11" fill="#fff"/>
                                <path d="M18 8h4l-1 8h-2l-1-8zm1 10h2v2h-2v-2z" fill="#f44336"/>
                                <circle cx="20" cy="20" r="1.5" fill="#f44336"/>
                            </svg>`),
                        scaledSize: new google.maps.Size(40, 50),
                        anchor: new google.maps.Point(20, 50)
                    }
                });
                
                const infoWindow = new google.maps.InfoWindow({
                    content: '<div style="color: #000;"><strong>Breakdown Location</strong><br>{{ addslashes($request->location) }}</div>'
                });
                
                infoWindow.open(map, marker);
                
                marker.addListener('click', () => {
                    infoWindow.open(map, marker);
                });
            @endif
        }
    </script>
</body>
</html>
