<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Details - AutoCar Mechanic</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&callback=initMap" async defer></script>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #000;
            color: #fff;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .page-header {
            margin-bottom: 40px;
        }

        .page-header h1 {
            font-size: 2.5rem;
            margin: 0 0 10px;
            color: #fff;
        }

        .alert {
            padding: 15px 20px;
            margin-bottom: 30px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #4caf50;
            color: #fff;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
        }

        .main-content {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .card {
            background: #1a1a1a;
            border: 2px solid #333;
            border-radius: 5px;
            padding: 30px;
        }

        .card-title {
            font-size: 1.5rem;
            color: #f8c300;
            margin: 0 0 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .info-item.full-width {
            grid-column: 1 / -1;
        }

        .info-label {
            color: #888;
            font-size: 0.85rem;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-label i {
            color: #f8c300;
            width: 16px;
        }

        .info-value {
            color: #fff;
            font-size: 1.1rem;
            font-weight: 500;
            padding: 12px;
            background: #222;
            border-radius: 3px;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-badge.assigned {
            background: #ff9800;
            color: #000;
        }

        .status-badge.in_progress {
            background: #9c27b0;
            color: #fff;
        }

        .status-badge.completed {
            background: #4caf50;
            color: #fff;
        }

        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        #map {
            width: 100%;
            height: 400px;
            border-radius: 5px;
            border: 2px solid #333;
        }

        .btn {
            padding: 15px 25px;
            border: none;
            border-radius: 3px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
        }

        .btn-primary {
            background: #f8c300;
            color: #000;
        }

        .btn-primary:hover {
            background: #fff;
            transform: translateY(-2px);
        }

        .btn-success {
            background: #4caf50;
            color: #fff;
        }

        .btn-success:hover {
            background: #45a049;
        }

        .btn-secondary {
            background: transparent;
            border: 2px solid #333;
            color: #888;
        }

        .btn-secondary:hover {
            border-color: #f8c300;
            color: #f8c300;
        }

        @media (max-width: 1024px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    @include('components.mechanic-header')

    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-file-alt" style="color: #f8c300;"></i> Job Details - Request #{{ $request->id }}</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <div class="content-grid">
            <!-- Main Content -->
            <div class="main-content">
                <!-- Request Information -->
                <div class="card">
                    <h2 class="card-title">
                        <i class="fas fa-info-circle"></i> Request Information
                    </h2>

                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-tag"></i> Status
                            </div>
                            <div class="info-value">
                                <span class="status-badge {{ $request->status }}">{{ ucfirst(str_replace('_', ' ', $request->status)) }}</span>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-clock"></i> Requested On
                            </div>
                            <div class="info-value">{{ $request->created_at->format('M d, Y g:i A') }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-user"></i> Customer Name
                            </div>
                            <div class="info-value">{{ $request->user->name }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-envelope"></i> Customer Email
                            </div>
                            <div class="info-value">{{ $request->user->email }}</div>
                        </div>

                        <div class="info-item full-width">
                            <div class="info-label">
                                <i class="fas fa-map-marker-alt"></i> Location
                            </div>
                            <div class="info-value">{{ $request->location }}</div>
                        </div>
                    </div>
                </div>

                <!-- Vehicle Information -->
                <div class="card">
                    <h2 class="card-title">
                        <i class="fas fa-car"></i> Vehicle Information
                    </h2>

                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-car"></i> Make & Model
                            </div>
                            <div class="info-value">{{ $request->vehicle_make }} {{ $request->vehicle_model }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-calendar"></i> Year
                            </div>
                            <div class="info-value">{{ $request->vehicle_year }}</div>
                        </div>

                        @if($request->plate_number)
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-id-card"></i> Plate Number
                            </div>
                            <div class="info-value">{{ $request->plate_number }}</div>
                        </div>
                        @endif

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-wrench"></i> Issue Type
                            </div>
                            <div class="info-value">{{ $request->issue_type }}</div>
                        </div>

                        @if($request->issue_description)
                        <div class="info-item full-width">
                            <div class="info-label">
                                <i class="fas fa-comment"></i> Issue Description
                            </div>
                            <div class="info-value">{{ $request->issue_description }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Map -->
                <div class="card" style="padding: 0; overflow: hidden;">
                    <div id="map"></div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <h2 class="card-title">
                        <i class="fas fa-cog"></i> Actions
                    </h2>

                    @if($request->status === 'assigned')
                        <form action="{{ route('mechanic.request.status', $request->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="in_progress">
                            <button type="submit" class="btn btn-success" style="width: 100%; margin-bottom: 10px;">
                                <i class="fas fa-play"></i> Start Job
                            </button>
                        </form>
                    @elseif($request->status === 'in_progress')
                        <form action="{{ route('mechanic.request.status', $request->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to mark this job as completed?');">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="btn btn-success" style="width: 100%; margin-bottom: 10px;">
                                <i class="fas fa-check"></i> Mark as Completed
                            </button>
                        </form>
                    @endif

                    <button onclick="getDirections()" class="btn btn-primary" style="width: 100%; margin-bottom: 10px;">
                        <i class="fas fa-directions"></i> Get Directions
                    </button>

                    <a href="{{ route('mechanic.assigned_jobs') }}" class="btn btn-secondary" style="width: 100%;">
                        <i class="fas fa-arrow-left"></i> Back to Jobs
                    </a>
                </div>
            </div>
        </div>
    </div>

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
        let userMarker;
        const userLat = {{ $request->latitude }};
        const userLng = {{ $request->longitude }};

        function initMap() {
            const userLocation = { lat: userLat, lng: userLng };

            map = new google.maps.Map(document.getElementById('map'), {
                center: userLocation,
                zoom: 15,
                styles: [
                    { elementType: 'geometry', stylers: [{ color: '#242f3e' }] },
                    { elementType: 'labels.text.stroke', stylers: [{ color: '#242f3e' }] },
                    { elementType: 'labels.text.fill', stylers: [{ color: '#746855' }] }
                ]
            });

            userMarker = new google.maps.Marker({
                position: userLocation,
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

            const infoWindow = new google.maps.InfoWindow({
                content: '<div style="color: #000;"><strong>Customer Location</strong><br>{{ $request->user->name }}</div>'
            });

            userMarker.addListener('click', () => {
                infoWindow.open(map, userMarker);
            });
        }

        function getDirections() {
            const destination = `${userLat},${userLng}`;
            const url = `https://www.google.com/maps/dir/?api=1&destination=${destination}`;
            window.open(url, '_blank');
        }
    </script>
</body>
</html>
