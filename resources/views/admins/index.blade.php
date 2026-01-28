<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - AutoCar</title>
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
        .stat-card .badge { position: absolute; top: 15px; right: 15px; background: #f8c300; color: #000; padding: 5px 10px; border-radius: 3px; font-size: 0.75rem; font-weight: bold; }
        
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
        
        .status-badge { display: inline-block; padding: 5px 15px; border-radius: 3px; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; min-width: 100px; text-align: center; }
        .status-badge.pending { background: #ff9800; color: #000; }
        .status-badge.assigned { background: #f8c300; color: #000; }
        .status-badge.in-progress, .status-badge.in_progress { background: #9c27b0; color: #fff; }
        .status-badge.completed { background: #4caf50; color: #fff; }
        .status-badge.cancelled { background: #666; color: #fff; }
        .status-badge.approved { background: #4caf50; color: #fff; }
        .status-badge.rejected { background: #f44336; color: #fff; }
        
        .btn { display: inline-block; padding: 8px 15px; border-radius: 3px; text-decoration: none; font-weight: bold; transition: all 0.3s; cursor: pointer; border: none; text-align: center; font-size: 0.85rem; }
        .btn-primary { background: #f8c300; color: #000; }
        .btn-primary:hover { background: #fff; }
        .btn-success { background: #4caf50; color: #fff; }
        .btn-success:hover { background: #66bb6a; }
        .btn-danger { background: #f44336; color: #fff; }
        .btn-danger:hover { background: #e57373; }
        .btn-secondary { background: #333; color: #fff; border: 2px solid #555; }
        .btn-secondary:hover { border-color: #f8c300; }
        
        .empty-state { text-align: center; padding: 40px 20px; color: #666; background: #1a1a1a; border-radius: 5px; border: 2px solid #333; }
        .empty-state i { font-size: 3rem; margin-bottom: 15px; color: #333; }
        .empty-state p { margin: 0; font-size: 1rem; }
        
        .alert { padding: 15px 20px; margin-bottom: 20px; border-radius: 5px; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #4caf50; color: #fff; }
        .alert-error { background: #f44336; color: #fff; }
        .alert-warning { background: #ff9800; color: #000; font-weight: bold; }
        
        .action-buttons { display: flex; gap: 5px; }
    </style>
</head>
<body>
    @include('components.admin-header')

    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-shield-alt" style="color: #f8c300;"></i> Admin Dashboard</h1>
            <p>Welcome back, {{ Auth::user()->name ?? 'Admin' }}! Manage your AutoCar system</p>
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

        @if($pendingMechanics > 0 || $pendingInsurance > 0)
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    <strong>Action Required!</strong><br>
                    @if($pendingMechanics > 0)
                        {{ $pendingMechanics }} mechanic(s) awaiting approval.<br>
                    @endif
                    @if($pendingInsurance > 0)
                        {{ $pendingInsurance }} insurance compan{{ $pendingInsurance == 1 ? 'y' : 'ies' }} awaiting approval.<br>
                    @endif
                    <a href="{{ route('admin.approvals') }}" style="color: inherit; text-decoration: underline; font-weight: bold; margin-top: 5px; display: inline-block;">Go to Approvals Page</a>
                </div>
            </div>
        @endif

        <!-- System Statistics -->
        <div class="section-title" style="margin-top: 20px;">
            <h2><i class="fas fa-chart-bar"></i> System Statistics</h2>
        </div>
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-users"></i>
                <h3>{{ $totalUsers }}</h3>
                <p>Total Users</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-wrench"></i>
                <h3>{{ $totalMechanics }}</h3>
                <p>Total Mechanics</p>
                @if($pendingMechanics > 0)
                    <a href="{{ route('admin.approvals') }}" style="text-decoration: none;">
                        <span class="badge">{{ $pendingMechanics }} Pending</span>
                    </a>
                @endif
            </div>
            <div class="stat-card">
                <i class="fas fa-shield-alt"></i>
                <h3>{{ $totalInsuranceCompanies }}</h3>
                <p>Insurance Companies</p>
                @if($pendingInsurance > 0)
                    <a href="{{ route('admin.approvals') }}" style="text-decoration: none;">
                        <span class="badge">{{ $pendingInsurance }} Pending</span>
                    </a>
                @endif
            </div>
            <div class="stat-card">
                <i class="fas fa-clipboard-list"></i>
                <h3>{{ $totalRequests }}</h3>
                <p>Total Requests</p>
            </div>
        </div>


        <!-- Recent Assistance Requests -->
        <div class="section-title">
            <h2><i class="fas fa-history"></i> Recent Assistance Requests</h2>
            <a href="{{ route('admin.requests') }}">View All <i class="fas fa-arrow-right"></i></a>
        </div>
        @if($recentRequests->isNotEmpty())
            <table>
                <thead>
                    <tr>
                        <th style="width: 8%;">ID</th>
                        <th style="width: 18%;">User</th>
                        <th style="width: 18%;">Service Type</th>
                        <th style="width: 12%;">Vehicle</th>
                        <th style="width: 18%;">Mechanic</th>
                        <th style="width: 12%;">Status</th>
                        <th style="width: 14%;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentRequests->take(7) as $request)
                        <tr>
                            <td><strong style="color: #f8c300;">#{{ $request->id }}</strong></td>
                            <td>{{ isset($request->user) ? ($request->user->name ?? 'User') : ($request->name ?? 'Unknown') }}</td>
                            <td><i class="fas fa-tools"></i> {{ $request->breakdown_type ?? 'N/A' }}</td>
                            <td><i class="fas fa-car"></i> {{ $request->plate_number ?? 'N/A' }}</td>
                            <td>
                                @if(isset($request->mechanic) && isset($request->mechanic->user))
                                    <i class="fas fa-user-check"></i> {{ $request->mechanic->user->name ?? 'Mechanic' }}
                                @elseif(isset($request->mechanic_name))
                                    <i class="fas fa-user-check"></i> {{ $request->mechanic_name }}
                                @else
                                    <span style="color: #888;"><i class="fas fa-user-slash"></i> Not Assigned</span>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge {{ $request->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                </span>
                            </td>
                            <td style="color: #aaa;">
                                @if(isset($request->created_at) && method_exists($request->created_at, 'format'))
                                    {{ $request->created_at->format('M d, Y') }}<br><small style="color: #666;">{{ $request->created_at->format('H:i') }}</small>
                                @else
                                    {{ $request->created_at ?? 'N/A' }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>No assistance requests yet</p>
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
