<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Requests - AutoCar</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .requests-hero {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url('https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80');
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

        .requests-hero h1 {
            font-size: 3rem;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .requests-section {
            padding: 80px 10%;
            background: #000;
            min-height: calc(100vh - 500px);
        }

        .requests-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .requests-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f8c300;
        }

        .requests-header h2 {
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

        .requests-grid {
            display: grid;
            gap: 20px;
        }

        .request-card {
            background: #1a1a1a;
            border-radius: 5px;
            padding: 30px;
            border: 2px solid #333;
            transition: all 0.3s;
            position: relative;
        }

        .request-card:hover {
            border-color: #f8c300;
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(248, 195, 0, 0.2);
        }

        .request-header-row {
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

        .status-pending {
            background: #ff9800;
            color: #000;
        }

        .status-assigned {
            background: #f8c300;
            color: #000;
        }

        .status-in_progress {
            background: #9c27b0;
            color: #fff;
        }

        .status-completed {
            background: #4caf50;
            color: #fff;
        }

        .status-cancelled {
            background: #f44336;
            color: #fff;
        }

        .request-details {
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

        .request-location {
            background: #222;
            padding: 15px;
            border-radius: 3px;
            margin-bottom: 20px;
            border-left: 3px solid #f8c300;
        }

        .request-location i {
            color: #f8c300;
            margin-right: 10px;
        }

        .request-location span {
            color: #ddd;
        }

        .request-notes {
            background: #222;
            padding: 15px;
            border-radius: 3px;
            margin-bottom: 20px;
        }

        .request-notes h4 {
            color: #f8c300;
            margin: 0 0 10px;
            font-size: 0.9rem;
            text-transform: uppercase;
        }

        .request-notes p {
            color: #ddd;
            margin: 0;
            line-height: 1.6;
        }

        .request-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 20px;
            border-top: 1px solid #333;
        }

        .request-date {
            color: #888;
            font-size: 0.85rem;
        }

        .request-date i {
            margin-right: 5px;
        }

        .request-actions {
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

        .btn-view {
            background: #2196F3;
            color: #fff;
        }

        .btn-view:hover {
            background: #1976D2;
            transform: translateY(-2px);
        }

        .btn-cancel {
            background: transparent;
            border: 2px solid #f44336;
            color: #f44336;
        }

        .btn-cancel:hover {
            background: #f44336;
            color: #fff;
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

        @media (max-width: 768px) {
            .requests-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 20px;
            }

            .filter-tabs {
                flex-wrap: wrap;
            }

            .request-details {
                grid-template-columns: 1fr;
            }

            .request-footer {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }

        /* Cancel Modal Styles */
        .cancel-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .cancel-modal.show {
            opacity: 1;
        }

        .modal-content {
            background: #1a1a1a;
            border: 2px solid #f44336;
            border-radius: 8px;
            padding: 40px;
            max-width: 500px;
            width: 90%;
            text-align: center;
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }

        .cancel-modal.show .modal-content {
            transform: scale(1);
        }

        .modal-icon {
            font-size: 4rem;
            color: #f44336;
            margin-bottom: 20px;
        }

        .modal-content h3 {
            color: #fff;
            font-size: 1.8rem;
            margin: 0 0 15px;
        }

        .modal-content p {
            color: #888;
            font-size: 1.1rem;
            margin: 0 0 30px;
            line-height: 1.6;
        }

        .modal-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .modal-btn {
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }

        .modal-cancel {
            background: #333;
            color: #fff;
            border: 2px solid #555;
        }

        .modal-cancel:hover {
            background: #444;
            border-color: #666;
        }

        .modal-confirm {
            background: #f44336;
            color: #fff;
        }

        .modal-confirm:hover {
            background: #d32f2f;
        }

        .modal-confirm:disabled {
            background: #666;
            cursor: not-allowed;
        }
    </style>
</head>
<body>

    <!-- Standardized User Header -->
    <x-user-header />

    <!-- Requests Hero -->
    <section class="requests-hero">
        <div class="hero-content">
            <h1><i class="fas fa-list-check" style="color: #f8c300;"></i> My Requests</h1>
            <p style="font-size: 1.2rem; color: #ddd; margin: 0;">
                Track and manage all your assistance requests
            </p>
        </div>
    </section>

    <!-- Requests Section -->
    <section class="requests-section">
        <div class="requests-container">
            <div class="requests-header">
                <h2>All Requests</h2>
                <div class="filter-tabs">
                    <button class="filter-tab active" data-status="all">All</button>
                    <button class="filter-tab" data-status="pending">Pending</button>
                    <button class="filter-tab" data-status="assigned">Assigned</button>
                    <button class="filter-tab" data-status="in_progress">In Progress</button>
                </div>
            </div>

            @if($requests->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>No Requests Yet</h3>
                    <p>You haven't submitted any assistance requests yet</p>
                    <a href="{{ route('user.request.assistance') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Create New Request
                    </a>
                </div>
            @else
                <div class="requests-grid">
                    @foreach($requests as $request)
                        <div class="request-card" data-status="{{ $request->status }}">
                            <div class="request-header-row">
                                <h3 class="request-id">Request #{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}</h3>
                                <span class="status-badge status-{{ $request->status }}">
                                    @if($request->status === 'pending')
                                        <i class="fas fa-clock"></i> Pending
                                    @elseif($request->status === 'assigned')
                                        <i class="fas fa-user-check"></i> Assigned
                                    @elseif($request->status === 'in_progress')
                                        <i class="fas fa-cog fa-spin"></i> In Progress
                                    @elseif($request->status === 'completed')
                                        <i class="fas fa-check-circle"></i> Completed
                                    @else
                                        <i class="fas fa-times-circle"></i> Cancelled
                                    @endif
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
                                            <p class="detail-label">Assigned Mechanic</p>
                                            <p class="detail-value">{{ $request->mechanic->user->name }}</p>
                                            <p style="color: #888; font-size: 0.85rem; margin-top: 2px;">
                                                <i class="fas fa-star" style="color: #f8c300;"></i> {{ $request->mechanic->rating }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="request-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $request->location_address }}</span>
                            </div>

                            @if($request->notes)
                                <div class="request-notes">
                                    <h4><i class="fas fa-comment"></i> Additional Notes</h4>
                                    <p>{{ $request->notes }}</p>
                                </div>
                            @endif

                            <div class="request-footer">
                                <div class="request-date">
                                    <i class="fas fa-calendar"></i>
                                    Submitted {{ $request->created_at->diffForHumans() }}
                                    <span style="color: #666; margin: 0 5px;">•</span>
                                    {{ $request->created_at->format('M d, Y h:i A') }}
                                </div>
                                <div class="request-actions">
                                    <a href="{{ route('user.track.request', $request->id) }}" class="btn btn-view">
                                        <i class="fas fa-map-marked-alt"></i> Track
                                    </a>
                                    @if($request->status === 'pending')
                                        <button class="btn btn-cancel" onclick="showCancelModal({{ $request->id }})">
                                            <i class="fas fa-times"></i> Cancel
                                        </button>
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
                
                // Filter cards
                document.querySelectorAll('.request-card').forEach(card => {
                    if (status === 'all' || card.dataset.status === status) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // Cancel Request with Modal Alert
        let cancelRequestId = null;

        function showCancelModal(requestId) {
            cancelRequestId = requestId;
            const modal = document.getElementById('cancelModal');
            modal.style.display = 'flex';
            setTimeout(() => modal.classList.add('show'), 10);
        }

        function closeCancelModal() {
            const modal = document.getElementById('cancelModal');
            modal.classList.remove('show');
            setTimeout(() => modal.style.display = 'none', 300);
            cancelRequestId = null;
        }

        function confirmCancel() {
            if (!cancelRequestId) return;

            const confirmBtn = document.querySelector('.modal-confirm');
            confirmBtn.disabled = true;
            confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Cancelling...';

            fetch(`{{ url('user/request') }}/${cancelRequestId}/cancel`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeCancelModal();
                    // Show success message
                    const successAlert = document.createElement('div');
                    successAlert.innerHTML = `
                        <div style="position: fixed; top: 20px; right: 20px; z-index: 10000; background: #28a745; color: white; padding: 20px 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.3); display: flex; align-items: center; gap: 15px;">
                            <i class="fas fa-check-circle" style="font-size: 24px;"></i>
                            <span>Request cancelled successfully!</span>
                        </div>
                    `;
                    document.body.appendChild(successAlert);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    alert('Failed to cancel request. Please try again.');
                    confirmBtn.disabled = false;
                    confirmBtn.innerHTML = '<i class="fas fa-check"></i> Yes, Cancel';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = '<i class="fas fa-check"></i> Yes, Cancel';
            });
        }

        // Close modal on outside click
        window.onclick = function(event) {
            const modal = document.getElementById('cancelModal');
            if (event.target === modal) {
                closeCancelModal();
            }
        }
    </script>

    <!-- Cancel Confirmation Modal -->
    <div id="cancelModal" class="cancel-modal">
        <div class="modal-content">
            <div class="modal-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3>Cancel Request?</h3>
            <p>Are you sure you want to cancel this assistance request? This action cannot be undone and you will need to submit a new request if needed.</p>
            <div class="modal-actions">
                <button class="modal-btn modal-cancel" onclick="closeCancelModal()">
                    <i class="fas fa-times"></i> No, Keep It
                </button>
                <button class="modal-btn modal-confirm" onclick="confirmCancel()">
                    <i class="fas fa-check"></i> Yes, Cancel
                </button>
            </div>
        </div>
    </div>
</body>
</html>
