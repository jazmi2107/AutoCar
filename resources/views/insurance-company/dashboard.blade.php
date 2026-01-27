<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insurance Company Dashboard - AutoCar</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #000; color: #fff; margin: 0; font-family: Arial, sans-serif; }
        .container { max-width: 1400px; margin: 0 auto; padding: 30px 20px; }
        .page-header { margin-bottom: 40px; padding-bottom: 20px; border-bottom: 2px solid #f8c300; }
        .page-header h1 { margin: 0 0 10px; font-size: 2.5rem; }
        .page-header p { color: #888; margin: 0; }
        
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .stat-card { background: #1a1a1a; padding: 25px; border-radius: 5px; border: 2px solid #333; transition: all 0.3s; position: relative; overflow: hidden; }
        .stat-card:hover { border-color: #f8c300; transform: translateY(-5px); }
        .stat-card i { font-size: 2.5rem; color: #f8c300; margin-bottom: 15px; }
        .stat-card h3 { margin: 0 0 5px; font-size: 2rem; color: #fff; }
        .stat-card p { margin: 0; color: #888; text-transform: uppercase; font-size: 0.9rem; }
        
        .section-title { display: flex; align-items: center; gap: 10px; margin: 40px 0 20px; }
        .section-title h2 { margin: 0; color: #f8c300; font-size: 1.5rem; }
        .section-title a { margin-left: auto; padding: 8px 15px; background: #333; color: #fff; text-decoration: none; border-radius: 3px; font-size: 0.85rem; transition: all 0.3s; }
        .section-title a:hover { background: #f8c300; color: #000; }
        
        table { width: 100%; background: #1a1a1a; border-collapse: collapse; border-radius: 5px; overflow: hidden; border: 2px solid #333; margin-bottom: 30px; }
        thead { background: #2a2a2a; }
        th { padding: 15px; text-align: left; color: #f8c300; font-weight: bold; text-transform: uppercase; font-size: 0.9rem; border-bottom: 2px solid #333; }
        td { padding: 15px; color: #ddd; border-bottom: 1px solid #333; }
        tbody tr { transition: background 0.3s; }
        tbody tr:hover { background: #2a2a2a; }
        tbody tr:last-child td { border-bottom: none; }
        td i { color: #f8c300; margin-right: 8px; }
        
        .status-badge { display: inline-block; padding: 6px 12px; border-radius: 4px; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; min-width: 100px; text-align: center; }
        .status-badge.pending { background: #ff9800; color: #000; }
        .status-badge.in-progress, .status-badge.in_progress { background: #9c27b0; color: #fff; }
        .status-badge.completed { background: #4caf50; color: #fff; }
        .status-badge.assigned { background: #f8c300; color: #000; }
        .status-badge.cancelled { background: #f44336; color: #fff; }
        
        .btn { display: inline-block; padding: 8px 15px; border-radius: 3px; text-decoration: none; font-weight: bold; transition: all 0.3s; cursor: pointer; border: none; text-align: center; font-size: 0.85rem; }
        .btn-primary { background: #f8c300; color: #000; }
        .btn-primary:hover { background: #fff; }
        .btn-secondary { background: #333; color: #fff; border: 2px solid #555; }
        .btn-secondary:hover { border-color: #f8c300; }
        
        .empty-state { text-align: center; padding: 40px 20px; color: #666; background: #1a1a1a; border-radius: 5px; border: 2px solid #333; }
        .empty-state i { font-size: 3rem; margin-bottom: 15px; color: #333; }
        .empty-state p { margin: 0; font-size: 1rem; }
    </style>
</head>
<body>
    @include('components.insurance-header')

    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-shield-alt" style="color: #f8c300;"></i> Insurance Dashboard</h1>
            <p>Welcome back, {{ $insurance->company_name }}!</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-list"></i>
                <h3>{{ $totalRequests }}</h3>
                <p>Total Requests</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-clock"></i>
                <h3>{{ $pendingRequests }}</h3>
                <p>Pending Requests</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-spinner"></i>
                <h3>{{ $inProgressRequests }}</h3>
                <p>In Progress</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-check-circle"></i>
                <h3>{{ $completedRequests }}</h3>
                <p>Completed</p>
            </div>
        </div>

        <div class="section-title">
            <h2><i class="fas fa-clock"></i> Recent Requests</h2>
            <a href="{{ route('insurance_company.requests') }}">View All Requests</a>
        </div>

        @if($recentRequests->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Mechanic</th>
                       
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentRequests as $request)
                        <tr>
                            <td>#{{ $request->id }}</td>
                            <td>{{ $request->user->name }}</td>
                            <td>
                                @if($request->mechanic)
                                    {{ $request->mechanic->user->name }}
                                @else
                                    <span style="color: #666;">Not Assigned</span>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge {{ $request->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                </span>
                            </td>
                            <td>{{ $request->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('insurance_company.request.show', $request->id) }}" class="btn btn-secondary">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <i class="fas fa-folder-open"></i>
                <p>No recent requests found</p>
            </div>
        @endif
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
