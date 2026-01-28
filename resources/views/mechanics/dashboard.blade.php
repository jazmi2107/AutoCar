<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mechanic Dashboard - AutoCar</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #000; color: #fff; margin: 0; font-family: Arial, sans-serif; }
        .container { max-width: 1400px; margin: 0 auto; padding: 30px 20px; }
        .page-header { margin-bottom: 40px; padding-bottom: 20px; border-bottom: 2px solid #f8c300; }
        .page-header h1 { margin: 0 0 10px; font-size: 2.5rem; }
        .page-header p { color: #888; margin: 0; }
        
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .stat-card { background: #1a1a1a; padding: 25px; border-radius: 5px; border: 2px solid #333; transition: all 0.3s; }
        .stat-card:hover { border-color: #f8c300; transform: translateY(-5px); }
        .stat-card i { font-size: 2.5rem; color: #f8c300; margin-bottom: 15px; }
        .stat-card h3 { margin: 0 0 5px; font-size: 2rem; color: #fff; }
        .stat-card p { margin: 0; color: #888; text-transform: uppercase; font-size: 0.9rem; }
        
        .profile-status { background: #1a1a1a; padding: 25px; border-radius: 5px; border: 2px solid #333; margin-bottom: 40px; }
        .profile-status h2 { margin: 0 0 20px; color: #f8c300; }
        .status-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
        .status-item { display: flex; align-items: center; gap: 10px; }
        .status-item i { color: #f8c300; font-size: 1.2rem; }
        .status-item span { color: #ddd; }
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
        .status-badge.available { background: #4caf50; color: #fff; }
        .status-badge.busy { background: #9c27b0; color: #fff; }
        .status-badge.offline { background: #666; color: #fff; }
        .status-badge.approved { background: #4caf50; color: #fff; }
        .status-badge.pending { background: #ff9800; color: #000; }
        .status-badge.assigned { background: #f8c300; color: #000; }
        .status-badge.in_progress { background: #9c27b0; color: #fff; }
        .status-badge.completed { background: #4caf50; color: #fff; }
        .status-badge.cancelled { background: #f44336; color: #fff; }
        .status-badge.rejected { background: #f44336; color: #fff; }
        
        .requests-section { margin-bottom: 40px; }
        .requests-section h2 { margin: 0 0 20px; color: #f8c300; }
        table { width: 100%; background: #1a1a1a; border-collapse: collapse; border-radius: 5px; overflow: hidden; border: 2px solid #333; }
        thead { background: #2a2a2a; }
        th { padding: 15px; text-align: left; color: #f8c300; font-weight: bold; text-transform: uppercase; font-size: 0.9rem; border-bottom: 2px solid #333; }
        td { padding: 15px; color: #ddd; border-bottom: 1px solid #333; }
        tbody tr { transition: background 0.3s; }
        tbody tr:hover { background: #2a2a2a; }
        tbody tr:last-child td { border-bottom: none; }
        td i { color: #f8c300; margin-right: 8px; }
        
        .btn { display: inline-block; padding: 10px 20px; border-radius: 3px; text-decoration: none; font-weight: bold; transition: all 0.3s; cursor: pointer; border: none; text-align: center; }
        .btn-primary { background: #f8c300; color: #000; }
        .btn-primary:hover { background: #fff; }
        .btn-secondary { background: #333; color: #fff; border: 2px solid #555; }
        .btn-secondary:hover { border-color: #f8c300; }
        
        .empty-state { text-align: center; padding: 60px 20px; color: #666; }
        .empty-state i { font-size: 4rem; margin-bottom: 20px; color: #333; }
        .empty-state p { margin: 0; font-size: 1.1rem; }
        
        .alert { padding: 15px 20px; margin-bottom: 20px; border-radius: 5px; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #4caf50; color: #fff; }
        .alert-error { background: #f44336; color: #fff; }
        .alert-warning { background: #ff9800; color: #fff; }
        
        .location-warning { background: #2a2a1a; border: 2px solid #ff9800; padding: 20px; border-radius: 5px; margin-bottom: 30px; }
        .location-warning i { color: #ff9800; font-size: 1.5rem; margin-right: 10px; }
    </style>
</head>
<body>
    @include('components.mechanic-header')

    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-tachometer-alt" style="color: #f8c300;"></i> Mechanic Dashboard</h1>
            <p>Welcome back, {{ $mechanic->user->name }}!</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
            </div>
        @endif

        @if(!$mechanic->latitude || !$mechanic->longitude)
            <div class="location-warning">
                <i class="fas fa-map-marker-alt"></i>
                <strong>Location Not Set!</strong>
                <p style="margin: 10px 0 0; color: #ddd;">Please set your location in your profile so customers can find you easily and we can calculate distances accurately.</p>
                <a href="{{ route('mechanic.profile') }}" class="btn btn-primary" style="margin-top: 15px; display: inline-block;">
                    <i class="fas fa-map-marker-alt"></i> Set Location Now
                </a>
            </div>
        @endif

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-clipboard-list"></i>
                <h3>{{ $pendingCount }}</h3>
                <p>Active Requests</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-check-double"></i>
                <h3>{{ $completedCount }}</h3>
                <p>Completed Jobs</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-star"></i>
                <h3>{{ number_format($mechanic->rating, 1) }}</h3>
                <p>Your Rating</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-chart-line"></i>
                <h3>{{ $mechanic->availability_status == 'available' ? 'Ready' : ucfirst($mechanic->availability_status) }}</h3>
                <p>Current Status</p>
            </div>
        </div>

        <!-- Pending Assignments -->
        <div class="requests-section">
            <h2><i class="fas fa-tasks"></i> Pending Assignments</h2>
            @if($assignedRequests->isNotEmpty())
                <table>
                    <thead>
                        <tr>
                            <th><i class="fas fa-tools"></i> Service Type</th>
                            <th><i class="fas fa-user"></i> Customer</th>
                            <th><i class="fas fa-car"></i> Vehicle</th>
                            <th><i class="fas fa-phone"></i> Contact</th>
                            <th><i class="fas fa-clock"></i> Time</th>
                            <th><i class="fas fa-info-circle"></i> Status</th>
                            <th><i class="fas fa-cog"></i> Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignedRequests as $request)
                            <tr>
                                <td><strong>{{ $request->breakdown_type }}</strong></td>
                                <td>{{ $request->name }}</td>
                                <td>{{ $request->plate_number }}</td>
                                <td>{{ $request->phone }}</td>
                                <td>{{ $request->created_at->diffForHumans() }}</td>
                                <td>
                                    <span class="status-badge {{ $request->status }}">
                                        {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('mechanic.request.show', $request->id) }}" class="btn btn-primary" style="padding: 8px 15px; font-size: 0.85rem;">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <i class="fas fa-clipboard-check"></i>
                    <p>No pending assignments at the moment</p>
                </div>
            @endif
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
