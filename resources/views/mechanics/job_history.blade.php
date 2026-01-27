<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job History - AutoCar Mechanic</title>
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

        .history-table {
            background: #1a1a1a;
            border: 2px solid #333;
            border-radius: 5px;
            overflow: hidden;
        }

        .history-item {
            padding: 25px;
            border-bottom: 1px solid #333;
            transition: all 0.3s;
        }

        .history-item:last-child {
            border-bottom: none;
        }

        .history-item:hover {
            background: #222;
        }

        .history-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .history-id {
            font-size: 1.2rem;
            color: #f8c300;
            font-weight: bold;
        }

        .history-status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: bold;
            text-transform: uppercase;
            min-width: 100px;
            text-align: center;
        }

        .history-status.completed {
            background: #4caf50;
            color: #fff;
        }

        .history-status.cancelled {
            background: #f44336;
            color: #fff;
        }

        .history-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .detail-label {
            color: #888;
            font-size: 0.8rem;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .detail-label i {
            color: #f8c300;
            width: 14px;
        }

        .detail-value {
            color: #fff;
            font-size: 1rem;
        }

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

        .empty-state {
            text-align: center;
            padding: 80px 20px;
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
    </style>
</head>
<body>
    @include('components.mechanic-header')

    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-history" style="color: #f8c300;"></i> Job History</h1>
            <p>View all your completed assistance requests</p>
        </div>

        @if($jobHistory->count() > 0)
            <div class="history-table">
                @foreach($jobHistory as $job)
                    <div class="history-item">
                        <div class="history-header">
                            <div class="history-id">Request #{{ $job->id }}</div>
                            <div class="history-status {{ $job->status }}">{{ ucfirst($job->status) }}</div>
                        </div>

                        <div class="history-details">
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-user"></i> Customer
                                </div>
                                <div class="detail-value">{{ $job->user->name }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-car"></i> Vehicle
                                </div>
                                <div class="detail-value">{{ $job->vehicle_make }} {{ $job->vehicle_model }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-wrench"></i> Issue Type
                                </div>
                                <div class="detail-value">{{ $job->issue_type }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-calendar"></i> Completed On
                                </div>
                                <div class="detail-value">{{ $job->updated_at->format('M d, Y g:i A') }}</div>
                            </div>
                            <div class="detail-item" style="grid-column: 1 / -1;">
                                <div class="detail-label">
                                    <i class="fas fa-map-marker-alt"></i> Location
                                </div>
                                <div class="detail-value">{{ $job->location }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pagination">
                @if ($jobHistory->onFirstPage())
                    <span>&laquo; Previous</span>
                @else
                    <a href="{{ $jobHistory->previousPageUrl() }}">&laquo; Previous</a>
                @endif

                @foreach ($jobHistory->getUrlRange(1, $jobHistory->lastPage()) as $page => $url)
                    @if ($page == $jobHistory->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($jobHistory->hasMorePages())
                    <a href="{{ $jobHistory->nextPageUrl() }}">Next &raquo;</a>
                @else
                    <span>Next &raquo;</span>
                @endif
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-clipboard-list"></i>
                <h3>No Job History</h3>
                <p>Your completed jobs will appear here</p>
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
