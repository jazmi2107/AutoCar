<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mechanic Details - AutoCar Admin</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #000; color: #fff; margin: 0; font-family: Arial, sans-serif; }
        .container { max-width: 1200px; margin: 0 auto; padding: 30px 20px; }
        .page-header { margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #f8c300; }
        .page-header h1 { margin: 0 0 10px; font-size: 2.5rem; }
        .breadcrumb { color: #888; font-size: 0.9rem; }
        .breadcrumb a { color: #f8c300; text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }
        
        .user-profile-card { background: #1a1a1a; padding: 40px; border-radius: 5px; border: 2px solid #333; margin-bottom: 30px; }
        .profile-header { display: flex; gap: 30px; align-items: start; margin-bottom: 30px; padding-bottom: 30px; border-bottom: 2px solid #333; }
        .profile-avatar { width: 120px; height: 120px; border-radius: 50%; border: 4px solid #9c27b0; }
        .profile-info { flex: 1; }
        .profile-info h2 { margin: 0 0 10px; font-size: 2rem; color: #fff; }
        .role-badge { display: inline-block; padding: 8px 16px; border-radius: 3px; font-size: 0.85rem; font-weight: bold; text-transform: uppercase; margin-bottom: 15px; }
        .role-mechanic { background: #9c27b0; color: #fff; }
        
        .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 25px; }
        .info-item { display: flex; gap: 15px; padding: 15px; background: #222; border-radius: 3px; border-left: 4px solid #f8c300; }
        .info-item i { font-size: 1.5rem; color: #f8c300; margin-top: 3px; }
        .info-details label { display: block; color: #888; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 5px; }
        .info-details p { margin: 0; color: #fff; font-size: 1.1rem; }
        
        .section-title { font-size: 1.5rem; color: #f8c300; margin: 40px 0 20px; padding-bottom: 10px; border-bottom: 2px solid #333; display: flex; align-items: center; gap: 10px; }
        
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; }
        .stat-box { background: #1a1a1a; padding: 25px; border-radius: 5px; border: 2px solid #333; text-align: center; transition: all 0.3s; }
        .stat-box:hover { border-color: #f8c300; transform: translateY(-3px); }
        .stat-box i { font-size: 2.5rem; color: #9c27b0; margin-bottom: 15px; }
        .stat-box h3 { margin: 0; font-size: 2rem; color: #fff; }
        .stat-box p { margin: 10px 0 0; color: #888; font-size: 0.9rem; text-transform: uppercase; }
        
        .requests-table { background: #1a1a1a; border-radius: 5px; border: 2px solid #333; overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #2a2a2a; }
        th { padding: 15px; text-align: left; color: #f8c300; font-weight: bold; text-transform: uppercase; font-size: 0.9rem; border-bottom: 2px solid #333; }
        td { padding: 15px; color: #ddd; border-bottom: 1px solid #333; }
        tbody tr { transition: background 0.3s; }
        tbody tr:hover { background: #2a2a2a; }
        tbody tr:last-child td { border-bottom: none; }
        
        .status-badge { display: inline-block; padding: 5px 12px; border-radius: 3px; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; }
        .status-pending { background: #ff9800; color: #000; }
        .status-assigned { background: #2196F3; color: #fff; }
        .status-in_progress { background: #9c27b0; color: #fff; }
        .status-completed { background: #4caf50; color: #fff; }
        .status-cancelled { background: #f44336; color: #fff; }
        .status-approved { background: #4caf50; color: #fff; }
        .status-rejected { background: #f44336; color: #fff; }
        .status-available { background: #4caf50; color: #fff; }
        .status-busy { background: #ff9800; color: #000; }
        .status-offline { background: #666; color: #fff; }
        
        .action-buttons { display: flex; gap: 15px; margin-top: 30px; }
        .btn { padding: 12px 25px; border-radius: 3px; text-decoration: none; font-weight: bold; transition: all 0.3s; display: inline-flex; align-items: center; gap: 8px; border: none; cursor: pointer; }
        .btn-primary { background: #f8c300; color: #000; }
        .btn-primary:hover { background: #fff; }
        .btn-secondary { background: #333; border: 2px solid #555; color: #fff; }
        .btn-secondary:hover { border-color: #f8c300; }
        .btn-edit { background: #2196F3; color: #fff; }
        .btn-edit:hover { background: #1976D2; }
        
        .empty-state { text-align: center; padding: 40px 20px; color: #888; }
        .empty-state i { font-size: 3rem; margin-bottom: 15px; color: #333; }
    </style>
</head>
<body>
    @include('components.admin-header')

    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-user-cog" style="color: #f8c300;"></i> Mechanic Details</h1>
            <p class="breadcrumb">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a> / 
                <a href="{{ route('admin.mechanics') }}">Mechanics</a> / 
                {{ $mechanic->user->name }}
            </p>
        </div>

        <div class="user-profile-card">
            <div class="profile-header">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($mechanic->user->name) }}&size=120&background=9c27b0" alt="Mechanic Avatar" class="profile-avatar">
                <div class="profile-info">
                    <h2>{{ $mechanic->user->name }}</h2>
                    <span class="role-badge role-mechanic">Mechanic</span>
                    <p style="color: #888; margin: 10px 0 0;">Member since {{ $mechanic->created_at->format('F d, Y') }}</p>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <div class="info-details">
                        <label>Email Address</label>
                        <p>{{ $mechanic->user->email }}</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-phone"></i>
                    <div class="info-details">
                        <label>Phone Number</label>
                        <p>{{ $mechanic->phone_number ?? 'Not provided' }}</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-id-card"></i>
                    <div class="info-details">
                        <label>License Number</label>
                        <p>{{ $mechanic->license_number ?? 'Not provided' }}</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-briefcase"></i>
                    <div class="info-details">
                        <label>Years of Experience</label>
                        <p>{{ $mechanic->years_of_experience ?? 0 }} years</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-shield-alt"></i>
                    <div class="info-details">
                        <label>Insurance Company</label>
                        <p>{{ $mechanic->insuranceCompany->company_name ?? 'None' }}</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <div class="info-details">
                        <label>Availability Status</label>
                        <p><span class="status-badge status-{{ $mechanic->availability_status }}">{{ ucfirst($mechanic->availability_status) }}</span></p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-check-circle"></i>
                    <div class="info-details">
                        <label>Approval Status</label>
                        <p><span class="status-badge status-{{ $mechanic->approval_status }}">{{ ucfirst($mechanic->approval_status) }}</span></p>
                    </div>
                </div>
                @if($mechanic->address)
                <div class="info-item" style="grid-column: 1 / -1;">
                    <i class="fas fa-map-marker-alt"></i>
                    <div class="info-details">
                        <label>Address</label>
                        <p>{{ $mechanic->address }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <h2 class="section-title">
            <i class="fas fa-chart-bar"></i> Mechanic Statistics
        </h2>
        
        <div class="stats-grid">
            <div class="stat-box">
                <i class="fas fa-clipboard-list"></i>
                <h3>{{ $requests->total() }}</h3>
                <p>Total Requests</p>
            </div>
            <div class="stat-box">
                <i class="fas fa-check-circle"></i>
                <h3>{{ $requests->where('status', 'completed')->count() }}</h3>
                <p>Completed</p>
            </div>
            <div class="stat-box">
                <i class="fas fa-hourglass-half"></i>
                <h3>{{ $requests->whereIn('status', ['assigned', 'in_progress'])->count() }}</h3>
                <p>In Progress</p>
            </div>
        </div>

        <h2 class="section-title">
            <i class="fas fa-history"></i> Assigned Assistance Requests
        </h2>
        
        @if($requests->isEmpty())
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>No assistance requests assigned yet</p>
            </div>
        @else
            <div class="requests-table">
                <table>
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>User</th>
                            <th>Service Type</th>
                            <th>Vehicle</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $request)
                            <tr>
                                <td><strong>#{{ $request->id }}</strong></td>
                                <td>{{ $request->user->name }}</td>
                                <td>{{ ucfirst($request->service_type ?? 'N/A') }}</td>
                                <td>{{ $request->vehicle_make ?? 'N/A' }} {{ $request->vehicle_model ?? '' }}</td>
                                <td>{{ Str::limit($request->location ?? 'N/A', 30) }}</td>
                                <td><span class="status-badge status-{{ $request->status }}">{{ ucfirst(str_replace('_', ' ', $request->status)) }}</span></td>
                                <td>{{ $request->created_at->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="action-buttons">
            <a href="{{ route('admin.mechanics.edit', $mechanic->id) }}" class="btn btn-edit">
                <i class="fas fa-edit"></i> Edit Mechanic
            </a>
            <a href="{{ route('admin.mechanics') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Mechanics
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
    </script>
</body>
</html>
