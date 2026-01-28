<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mechanic Dashboard - AutoCar</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #000;
            color: #fff;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .mechanic-container {
            display: flex;
            min-height: 100vh;
        }

        .mechanic-main {
            margin-left: 280px;
            flex: 1;
            padding: 30px;
        }

        .dashboard-hero {
            background: linear-gradient(135deg, #f8c300 0%, #ffa726 100%);
            padding: 40px;
            border-radius: 10px;
            margin-bottom: 40px;
            color: #000;
        }

        .dashboard-hero h1 {
            font-size: 2.5rem;
            margin: 0 0 10px;
        }

        .dashboard-hero p {
            font-size: 1.1rem;
            margin: 0;
            opacity: 0.8;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: #1a1a1a;
            padding: 30px;
            border-radius: 8px;
            border: 2px solid #333;
            text-align: center;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: rgba(248, 195, 0, 0.05);
            border-radius: 50%;
            transform: translate(30%, -30%);
        }

        .stat-card:hover {
            border-color: #f8c300;
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(248, 195, 0, 0.2);
        }

        .stat-card i {
            font-size: 2.5rem;
            color: #f8c300;
            margin-bottom: 15px;
        }

        .stat-number {
            font-size: 2.5rem;
            color: #fff;
            font-weight: bold;
            margin: 10px 0;
        }

        .stat-label {
            color: #888;
            font-size: 0.9rem;
            text-transform: uppercase;
        }

        .section-title {
            font-size: 1.8rem;
            margin: 40px 0 20px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 15px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f8c300;
        }

        .section-title i {
            color: #f8c300;
        }

        .requests-grid {
            display: grid;
            gap: 20px;
            margin-bottom: 40px;
        }

        .request-card {
            background: #1a1a1a;
            border: 2px solid #333;
            border-radius: 8px;
            padding: 25px;
            transition: all 0.3s;
        }

        .request-card:hover {
            border-color: #f8c300;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(248, 195, 0, 0.1);
        }

        .request-card.new-request {
            border-left: 4px solid #42a5f5;
        }

        .request-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #333;
        }

        .request-id {
            font-size: 1.3rem;
            color: #f8c300;
            margin: 0;
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-assigned { background: #42a5f5; color: #fff; }
        .status-in_progress { background: #66bb6a; color: #fff; }
        .status-completed { background: #4caf50; color: #fff; }

        .request-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .detail-item {
            display: flex;
            gap: 12px;
        }

        .detail-item i {
            color: #f8c300;
            font-size: 1.2rem;
            width: 20px;
        }

        .detail-label {
            color: #888;
            font-size: 0.85rem;
            margin: 0 0 5px;
        }

        .detail-value {
            color: #fff;
            font-weight: bold;
            margin: 0;
        }

        .request-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-accept {
            background: #66bb6a;
            color: #fff;
        }

        .btn-accept:hover {
            background: #4caf50;
        }

        .btn-reject {
            background: transparent;
            color: #f44336;
            border: 2px solid #f44336;
        }

        .btn-reject:hover {
            background: #f44336;
            color: #fff;
        }

        .btn-complete {
            background: #f8c300;
            color: #000;
        }

        .btn-complete:hover {
            background: #ffa726;
        }

        .btn-navigate {
            background: #9c27b0;
            color: #fff;
        }

        .btn-navigate:hover {
            background: #7b1fa2;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: #1a1a1a;
            border-radius: 8px;
            border: 2px dashed #333;
        }

        .empty-state i {
            font-size: 4rem;
            color: #333;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            color: #888;
            margin: 0 0 10px;
        }

        .empty-state p {
            color: #666;
            margin: 0;
        }

        @media (max-width: 768px) {
            .mechanic-main {
                margin-left: 0;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .request-details {
                grid-template-columns: 1fr;
            }

            .request-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="mechanic-container">
        @include('mechanics.sidebar')

        <main class="mechanic-main">
            <div class="dashboard-hero">
                <h1><i class="fas fa-tools"></i> Mechanic Dashboard</h1>
                <p>Welcome back, {{ $mechanic->user->name }}! Manage your assistance requests here.</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <i class="fas fa-bell"></i>
                    <div class="stat-number">{{ $stats['pending'] }}</div>
                    <div class="stat-label">New Requests</div>
                </div>

                <div class="stat-card">
                    <i class="fas fa-cog fa-spin"></i>
                    <div class="stat-number">{{ $stats['active'] }}</div>
                    <div class="stat-label">Active Jobs</div>
                </div>

                <div class="stat-card">
                    <i class="fas fa-check-circle"></i>
                    <div class="stat-number">{{ $stats['completed_today'] }}</div>
                    <div class="stat-label">Completed Today</div>
                </div>

                <div class="stat-card">
                    <i class="fas fa-trophy"></i>
                    <div class="stat-number">{{ $stats['total_completed'] }}</div>
                    <div class="stat-label">Total Completed</div>
                </div>
            </div>

            @if($newRequests->count() > 0)
                <h2 class="section-title">
                    <i class="fas fa-bell"></i> New Requests - Action Required
                </h2>
                <div class="requests-grid">
                    @foreach($newRequests as $request)
                        <div class="request-card new-request" id="request-{{ $request->id }}">
                            <div class="request-header">
                                <h3 class="request-id">Request #{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}</h3>
                                <span class="status-badge status-assigned">
                                    <i class="fas fa-user-check"></i> Assigned to You
                                </span>
                            </div>

                            <div class="request-details">
                                <div class="detail-item">
                                    <i class="fas fa-tools"></i>
                                    <div>
                                        <p class="detail-label">Breakdown Type</p>
                                        <p class="detail-value">{{ $request->breakdown_type }}</p>
                                    </div>
                                </div>

                                <div class="detail-item">
                                    <i class="fas fa-user"></i>
                                    <div>
                                        <p class="detail-label">Customer</p>
                                        <p class="detail-value">{{ $request->user->name }}</p>
                                    </div>
                                </div>

                                <div class="detail-item">
                                    <i class="fas fa-car"></i>
                                    <div>
                                        <p class="detail-label">Vehicle</p>
                                        <p class="detail-value">{{ $request->plate_number }}</p>
                                    </div>
                                </div>

                                <div class="detail-item">
                                    <i class="fas fa-phone"></i>
                                    <div>
                                        <p class="detail-label">Contact</p>
                                        <p class="detail-value">{{ $request->phone_number }}</p>
                                    </div>
                                </div>

                                <div class="detail-item" style="grid-column: 1 / -1;">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <div>
                                        <p class="detail-label">Location</p>
                                        <p class="detail-value">{{ $request->location_address }}</p>
                                    </div>
                                </div>

                                <div class="detail-item">
                                    <i class="fas fa-clock"></i>
                                    <div>
                                        <p class="detail-label">Submitted</p>
                                        <p class="detail-value">{{ $request->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="request-actions">
                                <button class="btn btn-accept" onclick="acceptRequest({{ $request->id }})">
                                    <i class="fas fa-check"></i> Accept Job
                                </button>
                                <button class="btn btn-reject" onclick="rejectRequest({{ $request->id }})">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                                <a href="{{ route('mechanic.request.show', $request->id) }}" class="btn btn-navigate">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if($activeRequests->count() > 0)
                <h2 class="section-title">
                    <i class="fas fa-cog"></i> Active Jobs
                </h2>
                <div class="requests-grid">
                    @foreach($activeRequests as $request)
                        <div class="request-card" id="request-{{ $request->id }}">
                            <div class="request-header">
                                <h3 class="request-id">Request #{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}</h3>
                                <span class="status-badge status-in_progress">
                                    <i class="fas fa-cog fa-spin"></i> In Progress
                                </span>
                            </div>

                            <div class="request-details">
                                <div class="detail-item">
                                    <i class="fas fa-tools"></i>
                                    <div>
                                        <p class="detail-label">Breakdown Type</p>
                                        <p class="detail-value">{{ $request->breakdown_type }}</p>
                                    </div>
                                </div>

                                <div class="detail-item">
                                    <i class="fas fa-user"></i>
                                    <div>
                                        <p class="detail-label">Customer</p>
                                        <p class="detail-value">{{ $request->user->name }}</p>
                                    </div>
                                </div>

                                <div class="detail-item">
                                    <i class="fas fa-car"></i>
                                    <div>
                                        <p class="detail-label">Vehicle</p>
                                        <p class="detail-value">{{ $request->plate_number }}</p>
                                    </div>
                                </div>

                                <div class="detail-item">
                                    <i class="fas fa-phone"></i>
                                    <div>
                                        <p class="detail-label">Contact</p>
                                        <p class="detail-value">{{ $request->phone_number }}</p>
                                    </div>
                                </div>

                                <div class="detail-item" style="grid-column: 1 / -1;">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <div>
                                        <p class="detail-label">Location</p>
                                        <p class="detail-value">{{ $request->location_address }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="request-actions">
                                <button class="btn btn-complete" onclick="completeRequest({{ $request->id }})">
                                    <i class="fas fa-check-circle"></i> Mark Complete
                                </button>
                                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $request->latitude }},{{ $request->longitude }}" 
                                   target="_blank" class="btn btn-navigate">
                                    <i class="fas fa-directions"></i> Navigate
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if($newRequests->count() == 0 && $activeRequests->count() == 0)
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>No Active Requests</h3>
                    <p>You don't have any pending requests at the moment. New requests will appear here.</p>
                </div>
            @endif

            @if($recentCompleted->count() > 0)
                <h2 class="section-title">
                    <i class="fas fa-history"></i> Recently Completed
                </h2>
                <div class="requests-grid">
                    @foreach($recentCompleted as $request)
                        <div class="request-card">
                            <div class="request-header">
                                <h3 class="request-id">Request #{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}</h3>
                                <span class="status-badge status-completed">
                                    <i class="fas fa-check-circle"></i> Completed
                                </span>
                            </div>

                            <div class="request-details">
                                <div class="detail-item">
                                    <i class="fas fa-tools"></i>
                                    <div>
                                        <p class="detail-label">Breakdown Type</p>
                                        <p class="detail-value">{{ $request->breakdown_type }}</p>
                                    </div>
                                </div>

                                <div class="detail-item">
                                    <i class="fas fa-user"></i>
                                    <div>
                                        <p class="detail-label">Customer</p>
                                        <p class="detail-value">{{ $request->user->name }}</p>
                                    </div>
                                </div>

                                <div class="detail-item">
                                    <i class="fas fa-clock"></i>
                                    <div>
                                        <p class="detail-label">Completed</p>
                                        <p class="detail-value">{{ $request->completed_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </main>
    </div>

    <script>
        function acceptRequest(requestId) {
            if (!confirm('Accept this job? You will be responsible for completing it.')) {
                return;
            }

            fetch(`{{ url('mechanic/requests') }}/${requestId}/accept`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Failed to accept request');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred');
            });
        }

        function rejectRequest(requestId) {
            if (!confirm('Reject this job? It will be reassigned to another mechanic.')) {
                return;
            }

            fetch(`{{ url('mechanic/requests') }}/${requestId}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Failed to reject request');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred');
            });
        }

        function completeRequest(requestId) {
            if (!confirm('Mark this job as completed? This cannot be undone.')) {
                return;
            }

            fetch(`{{ url('mechanic/requests') }}/${requestId}/complete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Failed to complete request');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred');
            });
        }
    </script>
</body>
</html>
