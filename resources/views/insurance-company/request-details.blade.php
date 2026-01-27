<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Details - AutoCar</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #000; color: #fff; margin: 0; font-family: Arial, sans-serif; }
        .container { max-width: 1400px; margin: 0 auto; padding: 30px 20px; }
        .page-header { margin-bottom: 40px; padding-bottom: 20px; border-bottom: 2px solid #f8c300; display: flex; align-items: center; justify-content: space-between; }
        .page-header h1 { margin: 0; font-size: 2rem; }
        .page-header-actions { display: flex; gap: 10px; }
        
        .details-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 30px; }
        .card { background: #1a1a1a; padding: 25px; border-radius: 5px; border: 2px solid #333; margin-bottom: 30px; }
        .card h3 { margin: 0 0 20px; color: #f8c300; font-size: 1.2rem; border-bottom: 1px solid #333; padding-bottom: 10px; }
        
        .info-row { display: flex; margin-bottom: 15px; border-bottom: 1px solid #333; padding-bottom: 15px; }
        .info-row:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
        .info-label { width: 150px; color: #888; font-weight: bold; }
        .info-value { flex: 1; color: #fff; }
        
        #map { height: 400px; border-radius: 5px; background: #333; border: 2px solid #333; z-index: 1; }
        
        .status-badge { display: inline-block; padding: 5px 15px; border-radius: 3px; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; }
        .status-badge.pending { background: #ff9800; color: #000; }
        .status-badge.in-progress, .status-badge.in_progress { background: #9c27b0; color: #fff; }
        .status-badge.completed { background: #4caf50; color: #fff; }
        .status-badge.assigned { border: 1px solid #f8c300; color: #f8c300; background: transparent; }
        .status-badge.cancelled { background: #666; color: #fff; }
        
        .btn { display: inline-block; padding: 8px 15px; border-radius: 3px; text-decoration: none; font-weight: bold; transition: all 0.3s; cursor: pointer; border: none; text-align: center; font-size: 0.85rem; }
        .btn-outline { background: transparent; border: 2px solid #333; color: #fff; }
        .btn-outline:hover { border-color: #f8c300; color: #f8c300; }
        .btn-success { background: #4caf50; color: #fff; }
        .btn-success:hover { background: #66bb6a; }
        
        .timeline { margin-top: 20px; }
        .timeline-item { padding-left: 20px; border-left: 2px solid #333; padding-bottom: 20px; position: relative; }
        .timeline-item:last-child { border-left: none; }
        .timeline-item::before { content: ''; position: absolute; left: -6px; top: 0; width: 10px; height: 10px; border-radius: 50%; background: #f8c300; }
        .timeline-date { font-size: 0.8rem; color: #888; margin-bottom: 5px; }
        .timeline-content { color: #fff; }
    </style>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

</head>
<body>
    @include('components.insurance-header')

    <div class="container">
        <div class="page-header">
            <div style="display: flex; align-items: center; gap: 15px;">
                <a href="{{ route('insurance_company.requests') }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                <h1><i class="fas fa-file-alt" style="color: #f8c300;"></i> Request #{{ $request->id }}</h1>
            </div>
            <div class="page-header-actions">
                @if($request->status == 'completed')
                    <a href="{{ route('insurance_company.receipt', $request->id) }}" class="btn btn-success" target="_blank">
                        <i class="fas fa-file-invoice"></i> Generate Receipt
                    </a>
                @endif
            </div>
        </div>

        <div class="details-grid">
            <div class="main-content">
                <div class="card">
                    <h3>Incident Details</h3>
                    <div class="info-row">
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            <span class="status-badge {{ $request->status }}">
                                {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                            </span>
                        </div>
                    </div>
                   
                    <div class="info-row">
                        <div class="info-label">Description</div>
                        <div class="info-value">{{ $request->description ?: 'No description provided.' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Reported At</div>
                        <div class="info-value">{{ $request->created_at->format('M d, Y H:i A') }}</div>
                    </div>
                </div>

                <div class="card">
                    <h3>Location</h3>
                    <div id="map"></div>
                </div>
            </div>

            <div class="sidebar">
                <div class="card">
                    <h3>User Information</h3>
                    <div class="info-row">
                        <div class="info-label">Name</div>
                        <div class="info-value">{{ $request->user->name }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Phone</div>
                        <div class="info-value">{{ $request->phone_number }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Vehicle</div>
                        <div class="info-value">
                            {{ $request->vehicle_make }} {{ $request->vehicle_model }}<br>
                            <small style="color: #888;">{{ $request->plate_number }}</small>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h3>Assigned Mechanic</h3>
                    @if($request->mechanic)
                        <div class="info-row">
                            <div class="info-label">Name</div>
                            <div class="info-value">{{ $request->mechanic->user->name }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Phone</div>
                            <div class="info-value">{{ $request->mechanic->phone_number }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">License</div>
                            <div class="info-value">{{ $request->mechanic->license_number }}</div>
                        </div>
                    @else
                        <div style="text-align: center; color: #888; padding: 20px;">
                            <i class="fas fa-user-slash" style="font-size: 2rem; margin-bottom: 10px;"></i>
                            <p>No mechanic assigned yet</p>
                        </div>
                    @endif
                </div>
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
    </script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Default to Kuala Lumpur if coordinates are missing
            var lat = {{ $request->latitude ?? 3.1390 }};
            var lng = {{ $request->longitude ?? 101.6869 }};
            var address = "{{ $request->location_address ?? 'Location not available' }}";
            
            var map = L.map('map').setView([lat, lng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            var marker = L.marker([lat, lng]).addTo(map);
            
            if (address) {
                marker.bindPopup(address).openPopup();
            }
        });
    </script>
</body>
</html>
