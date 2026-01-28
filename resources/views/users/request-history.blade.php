<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request History - AutoCar</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .history-hero {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url('https://images.unsplash.com/photo-1549194388-f61be84a6e9e?auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            padding: 20px;
        }

        .history-hero h1 {
            font-size: 3rem;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .history-section {
            padding: 80px 10%;
            background: #000;
            min-height: calc(100vh - 500px);
        }

        .history-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .history-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f8c300;
        }

        .history-header h2 {
            color: #fff;
            font-size: 2rem;
            margin: 0;
        }

        .filter-tabs {
            display: flex;
            gap: 15px;
        }

        .filter-tab {
            padding: 10px 25px;
            background: #1a1a1a;
            border: 2px solid #333;
            color: #888;
            cursor: pointer;
            border-radius: 3px;
            transition: all 0.3s;
            font-size: 0.9rem;
            text-transform: uppercase;
            font-weight: bold;
        }

        .filter-tab:hover {
            border-color: #f8c300;
            color: #fff;
        }

        .filter-tab.active {
            background: #f8c300;
            border-color: #f8c300;
            color: #000;
        }

        .timeline {
            position: relative;
            padding-left: 50px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #333;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 30px;
        }

        .timeline-marker {
            position: absolute;
            left: -38px;
            top: 25px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 3px solid #f8c300;
            background: #000;
            z-index: 1;
        }

        .timeline-marker.completed {
            background: #4caf50;
            border-color: #4caf50;
        }

        .timeline-marker.cancelled {
            background: #f44336;
            border-color: #f44336;
        }

        .history-card {
            background: #1a1a1a;
            border-radius: 5px;
            padding: 30px;
            border: 2px solid #333;
            transition: all 0.3s;
            position: relative;
        }

        .history-card:hover {
            border-color: #f8c300;
            transform: translateX(10px);
            box-shadow: 0 5px 20px rgba(248, 195, 0, 0.2);
        }

        .history-card.completed {
            border-left: 4px solid #4caf50;
        }

        .history-card.cancelled {
            border-left: 4px solid #f44336;
        }

        .card-header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .request-id {
            font-size: 1.5rem;
            color: #f8c300;
            font-weight: bold;
            margin: 0;
        }

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

        .status-completed {
            background: #4caf50;
            color: #fff;
        }

        .status-cancelled {
            background: #f44336;
            color: #fff;
        }

        .card-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .detail-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .detail-item i {
            color: #f8c300;
            margin-top: 3px;
            width: 20px;
        }

        .detail-label {
            color: #888;
            font-size: 0.85rem;
            margin: 0;
        }

        .detail-value {
            color: #fff;
            font-size: 1rem;
            margin: 5px 0 0;
            font-weight: bold;
        }

        .card-location {
            background: #222;
            padding: 15px;
            border-radius: 3px;
            margin-bottom: 20px;
            border-left: 3px solid #f8c300;
        }

        .card-location i {
            color: #f8c300;
            margin-right: 10px;
        }

        .card-location span {
            color: #ddd;
        }

        .completion-info {
            background: linear-gradient(135deg, #1a3a1a 0%, #1a1a1a 100%);
            padding: 15px;
            border-radius: 3px;
            margin-bottom: 20px;
            border: 1px solid #4caf50;
        }

        .completion-info.cancelled-info {
            background: linear-gradient(135deg, #3a1a1a 0%, #1a1a1a 100%);
            border-color: #f44336;
        }

        .completion-info h4 {
            color: #4caf50;
            margin: 0 0 10px;
            font-size: 0.9rem;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .cancelled-info h4 {
            color: #f44336;
        }

        .completion-info p {
            color: #ddd;
            margin: 0;
            line-height: 1.6;
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 20px;
            border-top: 1px solid #333;
        }

        .date-info {
            color: #888;
            font-size: 0.85rem;
        }

        .date-info i {
            margin-right: 5px;
        }

        .date-info .date-item {
            display: block;
            margin-bottom: 5px;
        }

        .date-item:last-child {
            margin-bottom: 0;
        }

        .card-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 3px;
            font-size: 0.9rem;
            font-weight: bold;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-details {
            background: #2a2a2a;
            color: #f8c300;
            border: 2px solid #f8c300;
        }

        .btn-details:hover {
            background: #f8c300;
            color: #000;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(248, 195, 0, 0.3);
        }

        .btn-details i {
            font-size: 1rem;
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: #1a1a1a;
            border-radius: 5px;
            border: 2px dashed #333;
        }

        .empty-state i {
            font-size: 5rem;
            color: #333;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            color: #888;
            font-size: 1.5rem;
            margin: 0 0 10px;
        }

        .empty-state p {
            color: #666;
            margin: 0 0 30px;
        }

        .btn-primary {
            background: #f8c300;
            color: #000;
            padding: 15px 40px;
            font-size: 1rem;
        }

        .btn-primary:hover {
            background: #fff;
            transform: translateY(-2px);
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 40px;
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

        .stats-summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: #1a1a1a;
            padding: 25px;
            border-radius: 5px;
            border: 2px solid #333;
            text-align: center;
            transition: all 0.3s;
        }

        .stat-card:hover {
            border-color: #f8c300;
            transform: translateY(-3px);
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
            margin: 0;
        }

        .stat-label {
            color: #888;
            font-size: 0.9rem;
            margin-top: 5px;
            text-transform: uppercase;
        }

        @media (max-width: 768px) {
            .history-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 20px;
            }

            .filter-tabs {
                flex-wrap: wrap;
            }

            .card-details {
                grid-template-columns: 1fr;
            }

            .card-footer {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .stats-summary {
                grid-template-columns: 1fr;
            }

            .timeline {
                padding-left: 30px;
            }

            .timeline::before {
                left: 10px;
            }

            .timeline-marker {
                left: -28px;
            }
        }
    </style>
</head>
<body>

    <x-user-header />

    <!-- History Hero -->
    <section class="history-hero">
        <div class="hero-content">
            <h1><i class="fas fa-history" style="color: #f8c300;"></i> Request History</h1>
            <p style="font-size: 1.2rem; color: #ddd; margin: 0;">
                View your completed and cancelled assistance requests
            </p>
        </div>
    </section>

    <!-- History Section -->
    <section class="history-section">
        <div class="history-container">
            <!-- Statistics Summary -->
            <div class="stats-summary">
                <div class="stat-card">
                    <i class="fas fa-list-check"></i>
                    <p class="stat-number">{{ $requests->total() }}</p>
                    <p class="stat-label">Total History</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-check-circle" style="color: #4caf50;"></i>
                    <p class="stat-number">{{ $requests->where('status', 'completed')->count() }}</p>
                    <p class="stat-label">Completed</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-times-circle" style="color: #f44336;"></i>
                    <p class="stat-number">{{ $requests->where('status', 'cancelled')->count() }}</p>
                    <p class="stat-label">Cancelled</p>
                </div>
            </div>

            <div class="history-header">
                <h2>Request Timeline</h2>
                <div class="filter-tabs">
                    <button class="filter-tab active" data-status="all">All</button>
                    <button class="filter-tab" data-status="completed">Completed</button>
                    <button class="filter-tab" data-status="cancelled">Cancelled</button>
                </div>
            </div>

            @if($requests->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-clock-rotate-left"></i>
                    <h3>No History Available</h3>
                    <p>You don't have any completed or cancelled requests yet</p>
                    <a href="{{ route('user.request.assistance') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Create New Request
                    </a>
                </div>
            @else
                <div class="timeline">
                    @foreach($requests as $request)
                        <div class="timeline-item" data-status="{{ $request->status }}">
                            <div class="timeline-marker {{ $request->status }}"></div>
                            
                            <div class="history-card {{ $request->status }}">
                                <div class="card-header-row">
                                    <h3 class="request-id">Request #{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}</h3>
                                    <span class="status-badge status-{{ $request->status }}">
                                        @if($request->status === 'completed')
                                            <i class="fas fa-check-circle"></i> Completed
                                        @else
                                            <i class="fas fa-times-circle"></i> Cancelled
                                        @endif
                                    </span>
                                </div>

                                <div class="card-details">
                                    <div class="detail-item">
                                        <i class="fas fa-tools"></i>
                                        <div>
                                            <p class="detail-label">Breakdown Type</p>
                                            <p class="detail-value">{{ $request->breakdown_type }}</p>
                                        </div>
                                    </div>

                                    <div class="detail-item">
                                        <i class="fas fa-car"></i>
                                        <div>
                                            <p class="detail-label">Vehicle</p>
                                            <p class="detail-value">{{ $request->plate_number }}</p>
                                            @if($request->vehicle_model)
                                                <p style="color: #888; font-size: 0.85rem; margin-top: 2px;">{{ $request->vehicle_model }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="detail-item">
                                        <i class="fas fa-shield-alt"></i>
                                        <div>
                                            <p class="detail-label">Insurance Company</p>
                                            <p class="detail-value">{{ $request->insuranceCompany->company_name ?? $request->insurance_name ?? 'N/A' }}</p>
                                        </div>
                                    </div>

                                    @if($request->mechanic)
                                        <div class="detail-item">
                                            <i class="fas fa-user-cog"></i>
                                            <div>
                                                <p class="detail-label">Mechanic</p>
                                                <p class="detail-value">{{ $request->mechanic->user->name }}</p>
                                                <p style="color: #888; font-size: 0.85rem; margin-top: 2px;">
                                                    <i class="fas fa-star" style="color: #f8c300;"></i> {{ $request->mechanic->rating }}
                                                    <span style="margin: 0 5px;">•</span>
                                                    {{ $request->mechanic->years_of_experience }} years exp
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="card-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ $request->location_address }}</span>
                                </div>

                                @if($request->status === 'completed')
                                    <div class="completion-info">
                                        <h4>
                                            <i class="fas fa-check-circle"></i>
                                            Service Completed Successfully
                                        </h4>
                                        <p>
                                            <i class="fas fa-calendar-check"></i>
                                            Completed on {{ $request->updated_at->format('M d, Y \a\t h:i A') }}
                                        </p>
                                        @if($request->notes)
                                            <p style="margin-top: 10px; padding-top: 10px; border-top: 1px solid rgba(76, 175, 80, 0.3);">
                                                <strong>Service Notes:</strong> {{ $request->notes }}
                                            </p>
                                        @endif
                                    </div>
                                @else
                                    <div class="completion-info cancelled-info">
                                        <h4>
                                            <i class="fas fa-times-circle"></i>
                                            Request Cancelled
                                        </h4>
                                        <p>
                                            <i class="fas fa-calendar-times"></i>
                                            Cancelled on {{ $request->updated_at->format('M d, Y \a\t h:i A') }}
                                        </p>
                                        @if($request->notes)
                                            <p style="margin-top: 10px; padding-top: 10px; border-top: 1px solid rgba(244, 67, 54, 0.3);">
                                                <strong>Reason:</strong> {{ $request->notes }}
                                            </p>
                                        @endif
                                    </div>
                                @endif

                                <div class="card-footer">
                                    <div class="date-info">
                                        <span class="date-item">
                                            <i class="fas fa-calendar"></i>
                                            <strong>Submitted:</strong> {{ $request->created_at->format('M d, Y h:i A') }}
                                        </span>
                                        <span class="date-item">
                                            <i class="fas fa-clock"></i>
                                            <strong>Duration:</strong> {{ $request->created_at->diffForHumans($request->updated_at, true) }}
                                        </span>
                                    </div>
                                    @if($request->status === 'completed')
                                        <div class="card-actions">
                                            <a href="{{ route('user.request.details', $request->id) }}" class="btn btn-details">
                                                <i class="fas fa-eye"></i>
                                                View Details
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($requests->hasPages())
                    <div class="pagination">
                        @if($requests->onFirstPage())
                            <span class="disabled"><i class="fas fa-chevron-left"></i></span>
                        @else
                            <a href="{{ $requests->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a>
                        @endif

                        @for($i = 1; $i <= $requests->lastPage(); $i++)
                            @if($i == $requests->currentPage())
                                <span class="active">{{ $i }}</span>
                            @else
                                <a href="{{ $requests->url($i) }}">{{ $i }}</a>
                            @endif
                        @endfor

                        @if($requests->hasMorePages())
                            <a href="{{ $requests->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
                        @else
                            <span class="disabled"><i class="fas fa-chevron-right"></i></span>
                        @endif
                    </div>
                @endif
            @endif
        </div>
    </section>

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

        // Filter Tabs
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                const status = this.dataset.status;
                
                // Update active tab
                document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                // Filter timeline items
                document.querySelectorAll('.timeline-item').forEach(item => {
                    if (status === 'all' || item.dataset.status === status) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>
