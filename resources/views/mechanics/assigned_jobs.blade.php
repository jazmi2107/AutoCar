<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assigned Jobs - AutoCar Mechanic</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

        .page-header p {
            color: #888;
            font-size: 1.1rem;
            margin: 0;
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

        .section-title {
            font-size: 1.8rem;
            margin: 40px 0 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f8c300;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .jobs-grid {
            display: grid;
            gap: 20px;
        }

        .job-card {
            background: #1a1a1a;
            border: 2px solid #333;
            border-radius: 5px;
            padding: 25px;
            transition: all 0.3s;
        }

        .job-card:hover {
            border-color: #f8c300;
            transform: translateY(-2px);
        }

        .job-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #333;
        }

        .job-id {
            font-size: 1.3rem;
            color: #f8c300;
            font-weight: bold;
        }

        .job-status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: bold;
            text-transform: uppercase;
            min-width: 100px;
            text-align: center;
        }

        .job-status.assigned {
            background: #f8c300;
            color: #000;
        }

        .job-status.in_progress {
            background: #9c27b0;
            color: #fff;
        }

        .job-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .detail-label {
            color: #888;
            font-size: 0.85rem;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .detail-label i {
            color: #f8c300;
            width: 16px;
        }

        .detail-value {
            color: #fff;
            font-size: 1.05rem;
            font-weight: 500;
        }

        .job-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #333;
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 3px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: #f8c300;
            color: #000;
            flex: 1;
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

        .btn-danger {
            background: transparent;
            color: #f44336;
            border: 2px solid #f44336;
        }

        .btn-danger:hover {
            background: #f44336;
            color: #fff;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            margin: 0 0 10px;
            color: #888;
        }

        .empty-state p {
            font-size: 1rem;
            margin: 0;
        }
    </style>
</head>
<body>
    @include('components.mechanic-header')

    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-tasks" style="color: #f8c300;"></i> Assigned Jobs</h1>
            <p>Review and manage your assigned assistance requests</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Pending Approval Jobs -->
        <h2 class="section-title">
            <i class="fas fa-clock"></i> Pending Approval
        </h2>

        @if($assignedRequests->count() > 0)
            <div class="jobs-grid">
                @foreach($assignedRequests as $request)
                    <div class="job-card">
                        <div class="job-header">
                            <div class="job-id">Request #{{ $request->id }}</div>
                            <div class="job-status assigned">Assigned</div>
                        </div>

                        <div class="job-details">
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-user"></i> Customer
                                </div>
                                <div class="detail-value">{{ $request->user->name }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-car"></i> Vehicle
                                </div>
                                <div class="detail-value">{{ $request->vehicle_make }} {{ $request->vehicle_model }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-wrench"></i> Breakdown Type
                                </div>
                                <div class="detail-value">{{ $request->breakdown_type }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-clock"></i> Requested
                                </div>
                                <div class="detail-value">{{ $request->created_at->format('M d, Y g:i A') }}</div>
                            </div>
                            <div class="detail-item" style="grid-column: 1 / -1;">
                                <div class="detail-label">
                                    <i class="fas fa-map-marker-alt"></i> Location
                                </div>
                                <div class="detail-value">{{ $request->location_address }}</div>
                            </div>
                            @if($request->issue_description)
                            <div class="detail-item" style="grid-column: 1 / -1;">
                                <div class="detail-label">
                                    <i class="fas fa-comment"></i> Description
                                </div>
                                <div class="detail-value">{{ $request->issue_description }}</div>
                            </div>
                            @endif
                        </div>

                        <div class="job-actions">
                            <form action="{{ route('mechanic.job.approve', $request->id) }}" method="POST" style="flex: 1;">
                                @csrf
                                <button type="submit" class="btn btn-success" style="width: 100%;">
                                    <i class="fas fa-check"></i> View Details & Start Job
                                </button>
                            </form>
                            <form action="{{ route('mechanic.job.reject', $request->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to reject this job?');">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>No Pending Assignments</h3>
                <p>You don't have any jobs waiting for approval</p>
            </div>
        @endif

        <!-- In Progress Jobs -->
        <h2 class="section-title">
            <i class="fas fa-spinner"></i> In Progress
        </h2>

        @if($inProgressRequests->count() > 0)
            <div class="jobs-grid">
                @foreach($inProgressRequests as $request)
                    <div class="job-card">
                        <div class="job-header">
                            <div class="job-id">Request #{{ $request->id }}</div>
                            <div class="job-status in_progress">In Progress</div>
                        </div>

                        <div class="job-details">
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-user"></i> Customer
                                </div>
                                <div class="detail-value">{{ $request->user->name }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-car"></i> Vehicle
                                </div>
                                <div class="detail-value">{{ $request->vehicle_make }} {{ $request->vehicle_model }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-wrench"></i> Breakdown Type
                                </div>
                                <div class="detail-value">{{ $request->breakdown_type }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-map-marker-alt"></i> Location
                                </div>
                                <div class="detail-value">{{ Str::limit($request->location_address) }}</div>
                            </div>
                        </div>

                        <div class="job-actions">
                            <a href="{{ route('mechanic.request.show', $request->id) }}" class="btn btn-primary" style="justify-content: center;">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-hourglass-half"></i>
                <h3>No Jobs In Progress</h3>
                <p>Accept a job from pending assignments to get started</p>
            </div>
        @endif
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
