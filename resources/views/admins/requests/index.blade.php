<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Management - AutoCar Admin</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #000; color: #fff; margin: 0; font-family: Arial, sans-serif; }
        .container { max-width: 1600px; margin: 0 auto; padding: 30px 20px; }
        
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #f8c300; }
        .page-header h1 { margin: 0; font-size: 2rem; }
        .breadcrumb { color: #888; font-size: 0.9rem; margin-bottom: 5px; }
        .breadcrumb a { color: #f8c300; text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }
        
        .filter-tabs { display: flex; gap: 15px; margin-bottom: 30px; border-bottom: 2px solid #333; flex-wrap: wrap; }
        .filter-tab { padding: 15px 25px; background: transparent; border: none; color: #888; cursor: pointer; font-weight: bold; transition: all 0.3s; position: relative; font-size: 0.95rem; }
        .filter-tab:hover { color: #f8c300; }
        .filter-tab.active { color: #f8c300; }
        .filter-tab.active::after { content: ''; position: absolute; bottom: -2px; left: 0; right: 0; height: 2px; background: #f8c300; }
        
        .table-container { background: #1a1a1a; border-radius: 8px; border: 2px solid #333; overflow: hidden; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: linear-gradient(135deg, #f8c300 0%, #f5a623 100%); }
        thead th { padding: 15px; text-align: left; color: #000; font-weight: bold; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; }
        tbody tr { border-bottom: 1px solid #333; transition: all 0.3s; }
        tbody tr:hover { background: rgba(248, 195, 0, 0.1); }
        tbody tr:last-child { border-bottom: none; }
        tbody td { padding: 15px; color: #fff; }
        .user-name { color: #fff; font-weight: bold; margin: 0; }
        .user-info { color: #888; font-size: 0.85rem; margin: 3px 0 0; }
        .status-badge { display: inline-block; padding: 6px 12px; border-radius: 4px; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; min-width: 100px; text-align: center; }
        .status-pending { background: #ff9800; color: #000; }
        .status-assigned { background: #f8c300; color: #000; }
        .status-in_progress, .status-in-progress { background: #9c27b0; color: #fff; }
        .status-completed { background: #4caf50; color: #fff; }
        .status-cancelled { background: #f44336; color: #fff; }
        
        .btn { display: inline-block; padding: 8px 15px; border-radius: 5px; text-decoration: none; font-weight: bold; transition: all 0.3s; cursor: pointer; border: none; text-align: center; font-size: 0.85rem; }
        .btn i { margin-right: 5px; }
        .btn-view { background: #f8c300; color: #000; }
        .btn-view:hover { background: #fff; }
        
        .alert { padding: 15px 20px; margin-bottom: 20px; border-radius: 5px; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #4caf50; color: #fff; }
        .alert-error { background: #f44336; color: #fff; }
        .empty-state { text-align: center; padding: 60px 20px; color: #666; }
        .empty-state i { font-size: 4rem; margin-bottom: 20px; display: block; color: #555; }
        
        .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%); padding: 20px; border-radius: 8px; border: 2px solid #333; text-align: center; }
        .stat-card i { font-size: 2rem; margin-bottom: 10px; color: #f8c300; }
        .stat-card .stat-value { font-size: 2rem; font-weight: bold; color: #fff; margin-bottom: 5px; }
        .stat-card .stat-label { font-size: 0.85rem; color: #888; text-transform: uppercase; }
        
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 30px;
        }

        .pagination a,
        .pagination span {
            padding: 8px 16px;
            background: #1a1a1a;
            border: 1px solid #333;
            color: #ccc;
            text-decoration: none;
            border-radius: 4px;
            transition: all 0.3s;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .pagination a:hover {
            border-color: #f8c300;
            color: #f8c300;
            background: rgba(248, 195, 0, 0.05);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .pagination .active {
            background: #f8c300;
            color: #000;
            border-color: #f8c300;
            box-shadow: 0 4px 10px rgba(248, 195, 0, 0.3);
        }

        .pagination span:not(.active) {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        footer { background: #111; border-top: 2px solid #333; margin-top: 50px; padding: 20px 0; }
        .footer-flex { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; }
        .copyright { color: #888; font-size: 0.9rem; }
        .footer-phone { color: #f8c300; font-weight: bold; }
        .footer-phone span { color: #fff; }
    </style>
</head>
<body>
    @include('components.admin-header')

    <div class="container">
        <div class="page-header">
            <div>
                <div class="breadcrumb">
                    <i class="fas fa-clipboard-list"></i> Assistance Requests
                </div>
                <h1>Request Management</h1>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i>{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error"><i class="fas fa-exclamation-triangle"></i>{{ session('error') }}</div>
        @endif

        <div class="filter-tabs">
            <button class="filter-tab {{ !request('status') ? 'active' : '' }}" onclick="window.location='{{ route('admin.requests') }}'">
                All ({{ $requests->total() }})
            </button>
            <button class="filter-tab {{ request('status') == 'assigned' ? 'active' : '' }}" onclick="window.location='{{ route('admin.requests', ['status' => 'assigned']) }}'">
                Assigned
            </button>
            <button class="filter-tab {{ request('status') == 'in_progress' ? 'active' : '' }}" onclick="window.location='{{ route('admin.requests', ['status' => 'in_progress']) }}'">
                In Progress
            </button>
            <button class="filter-tab {{ request('status') == 'completed' ? 'active' : '' }}" onclick="window.location='{{ route('admin.requests', ['status' => 'completed']) }}'">
                Completed
            </button>
            <button class="filter-tab {{ request('status') == 'cancelled' ? 'active' : '' }}" onclick="window.location='{{ route('admin.requests', ['status' => 'cancelled']) }}'">
                Cancelled
            </button>
        </div>

        @if($requests->isEmpty())
            <div class="table-container">
                <div class="empty-state">
                    <i class="fas fa-clipboard-list"></i>
                    <h3>No Requests Found</h3>
                    <p>No requests match your current filter</p>
                </div>
            </div>
        @else
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Service Type</th>
                            <th>Vehicle</th>
                            <th>Location</th>
                            <th>Mechanic</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $req)
                            <tr>
                                <td><strong>#{{ $req->id }}</strong></td>
                                <td>
                                    <p class="user-name">{{ $req->name }}</p>
                                    <p class="user-info">{{ $req->phone_number }}</p>
                                </td>
                                <td>{{ $req->breakdown_type }}</td>
                                <td>
                                    <p class="user-name">{{ $req->vehicle_make ?? 'N/A' }}</p>
                                    <p class="user-info">{{ $req->vehicle_model }}</p>
                                    <p class="user-info">{{ $req->plate_number }}</p>
                                </td>
                                <td style="max-width: 200px;">
                                    <p class="user-info" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $req->location_address }}
                                    </p>
                                </td>
                                <td>
                                    @if($req->mechanic)
                                        <p class="user-name">{{ $req->mechanic->user->name }}</p>
                                        <p class="user-info">{{ $req->mechanic->insuranceCompany->company_name ?? 'N/A' }}</p>
                                    @else
                                        <span style="color: #888;">Not assigned</span>
                                    @endif
                                </td>
                                <td><span class="status-badge status-{{ strtolower(str_replace(' ', '-', $req->status)) }}">{{ $req->status }}</span></td>
                                <td>
                                    {{ $req->created_at->format('M d, Y') }}<br>
                                    <span class="user-info">{{ $req->created_at->format('h:i A') }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.requests.show', $req->id) }}" class="btn btn-view">
                                        <i class="fas fa-eye"></i> View Details
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($requests->hasPages())
                <div class="pagination">
                    @if($requests->onFirstPage())
                        <span class="disabled"><i class="fas fa-chevron-left"></i> Prev</span>
                    @else
                        <a href="{{ $requests->appends(request()->query())->previousPageUrl() }}"><i class="fas fa-chevron-left"></i> Prev</a>
                    @endif
                    
                    @foreach($requests->getUrlRange(1, $requests->lastPage()) as $page => $url)
                        @if($page == $requests->currentPage())
                            <span class="active">{{ $page }}</span>
                        @else
                            <a href="{{ $requests->appends(request()->query())->url($page) }}">{{ $page }}</a>
                        @endif
                    @endforeach
                    
                    @if($requests->hasMorePages())
                        <a href="{{ $requests->appends(request()->query())->nextPageUrl() }}">Next <i class="fas fa-chevron-right"></i></a>
                    @else
                        <span class="disabled">Next <i class="fas fa-chevron-right"></i></span>
                    @endif
                </div>
            @endif
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
                    CALL TODAY: <span>+6012 284 0561</span>
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
