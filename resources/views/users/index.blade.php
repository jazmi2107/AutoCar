<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - AutoCar</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .dashboard-hero {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url('https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 350px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            padding: 20px;
        }

        .dashboard-hero h1 {
            font-size: 3rem;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .dashboard-stats {
            display: flex;
            justify-content: center;
            gap: 30px;
            padding: 50px 0;
            width: 90%;
            max-width: 1200px;
            margin: -80px auto 0;
            position: relative;
            z-index: 10;
        }

        .stat-card {
            background: #fff;
            color: #333;
            flex: 1;
            min-width: 250px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            border-radius: 5px;
            overflow: hidden;
            padding: 30px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-10px);
        }

        .stat-card .icon {
            font-size: 3rem;
            color: #f8c300;
            margin-bottom: 15px;
        }

        .stat-card h3 {
            font-size: 2.5rem;
            margin: 10px 0;
            font-weight: bold;
        }

        .stat-card p {
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
            margin: 0;
        }

        .quick-actions {
            padding: 60px 10%;
            background: #111;
        }

        .action-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .action-card {
            background: #1a1a1a;
            padding: 40px;
            text-align: center;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
            cursor: pointer;
        }

        .action-card:hover {
            border-bottom-color: #f8c300;
            transform: translateY(-5px);
        }

        .action-card .action-icon {
            font-size: 3rem;
            color: #f8c300;
            margin-bottom: 20px;
        }

        .action-card h3 {
            margin: 0 0 15px;
            font-size: 1.3rem;
            color: #fff;
        }

        .action-card p {
            color: #888;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }

        .action-card .btn-action {
            background: #f8c300;
            color: #000;
            border: none;
            padding: 12px 30px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 3px;
            text-transform: uppercase;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .action-card .btn-action:hover {
            background: #fff;
        }

        .recent-section {
            padding: 80px 10%;
            background: #000;
        }

        .recent-requests {
            max-width: 1200px;
            margin: 0 auto;
        }

        .request-item {
            background: #1a1a1a;
            margin-bottom: 20px;
            padding: 25px;
            border-left: 4px solid #f8c300;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s;
        }

        .request-item:hover {
            background: #222;
            transform: translateX(10px);
        }

        .request-info {
            flex: 1;
        }

        .request-info h4 {
            margin: 0 0 10px;
            color: #fff;
            font-size: 1.2rem;
        }

        .request-info p {
            margin: 5px 0;
            color: #888;
            font-size: 0.9rem;
        }

        .request-status {
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.8rem;
        }

        .status-pending {
            background: #ff9800;
            color: #000;
        }

        .status-completed {
            background: #4caf50;
            color: #fff;
        }

        .status-in-progress {
            background: #2196f3;
            color: #fff;
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #888;
        }

        .empty-state i {
            font-size: 5rem;
            color: #333;
            margin-bottom: 20px;
        }

        .empty-state p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

    <!-- Success Message Alert -->
    @if(session('success'))
    <div id="successAlert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; background: #28a745; color: white; padding: 20px 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.3); display: flex; align-items: center; gap: 15px; animation: slideIn 0.5s ease-out;">
        <i class="fas fa-check-circle" style="font-size: 24px;"></i>
        <div>
            <strong style="display: block; margin-bottom: 5px;">Success!</strong>
            <span>{{ session('success') }}</span>
        </div>
        <button onclick="closeAlert()" style="background: none; border: none; color: white; font-size: 20px; cursor: pointer; margin-left: 10px; opacity: 0.8; transition: opacity 0.3s;">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <style>
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
    </style>
    <script>
        function closeAlert() {
            const alert = document.getElementById('successAlert');
            alert.style.animation = 'slideOut 0.5s ease-out';
            setTimeout(() => alert.remove(), 500);
        }
        
        // Auto-close after 5 seconds
        setTimeout(closeAlert, 5000);
    </script>
    @endif

    <!-- Standardized User Header -->
    <x-user-header />

    <!-- Dashboard Hero -->
    <section class="dashboard-hero">
        <div class="hero-content">
            <h1>Welcome Back, <span style="color: #f8c300;">{{ Auth::user()->name }}</span>!</h1>
            <p style="font-size: 1.2rem; color: #ddd; margin: 0;">
                Your Dashboard - Manage your requests and services
            </p>
        </div>
    </section>

    <!-- Statistics Cards -->
    <div class="dashboard-stats">
        <div class="stat-card">
            <div class="icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <h3>{{ $totalRequests ?? 0 }}</h3>
            <p>Total Requests</p>
        </div>
        <div class="stat-card">
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
            <h3>{{ $pendingRequests ?? 0 }}</h3>
            <p>Pending</p>
        </div>
        <div class="stat-card">
            <div class="icon">
                <i class="fas fa-tools"></i>
            </div>
            <h3>{{ $inProgressRequests ?? 0 }}</h3>
            <p>In Progress</p>
        </div>
        <div class="stat-card">
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3>{{ $completedRequests ?? 0 }}</h3>
            <p>Completed</p>
        </div>
    </div>

    <!-- Recent Requests -->
    <section class="recent-section">
        <h2 class="section-title" style="color: #fff; text-align: center; margin-bottom: 50px;">Recent Requests</h2>
        <div class="recent-requests">
            @if(isset($recentRequests) && count($recentRequests) > 0)
                @foreach($recentRequests as $request)
                <div class="request-item">
                    <div class="request-info">
                        <h4>
                            <i class="fas fa-{{ $request->service_icon ?? 'wrench' }}" style="color: #f8c300; margin-right: 10px;"></i>
                            {{ $request->breakdown_type }}
                        </h4>
                        <p><i class="fas fa-map-marker-alt"></i> {{ $request->location_address }}</p>
                        <p><i class="fas fa-car"></i> {{ $request->plate_number }} - {{ $request->vehicle_model ?? 'N/A' }}</p>
                        <p><i class="fas fa-calendar"></i> {{ $request->created_at->format('M d, Y - h:i A') }}</p>
                    </div>
                    <div>
                        @if($request->status == 'pending')
                            <span class="request-status status-pending">Pending</span>
                        @elseif($request->status == 'in_progress')
                            <span class="request-status status-in-progress">In Progress</span>
                        @elseif($request->status == 'completed')
                            <span class="request-status status-completed">Completed</span>
                        @else
                            <span class="request-status" style="background: #666;">{{ ucfirst($request->status) }}</span>
                        @endif
                    </div>
                </div>
                @endforeach
            @else
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>You haven't made any requests yet</p>
                    <a href="{{ route('user.request.assistance') }}" class="btn-action" style="background: #f8c300; color: #000; padding: 15px 40px; text-decoration: none; display: inline-block; border-radius: 3px; font-weight: bold; text-transform: uppercase;">
                        Create Your First Request
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Footer (Same as welcome.blade.php) -->
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
    </script>
</body>
</html>
