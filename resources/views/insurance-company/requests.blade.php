<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assistance Requests - AutoCar</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #000; color: #fff; margin: 0; font-family: Arial, sans-serif; }
        .container { max-width: 1400px; margin: 0 auto; padding: 30px 20px; }
        .page-header { margin-bottom: 40px; padding-bottom: 20px; border-bottom: 2px solid #f8c300; }
        .page-header h1 { margin: 0 0 10px; font-size: 2.5rem; }
        .page-header p { color: #888; margin: 0; }
        
        .filter-card { background: #1a1a1a; padding: 20px; border-radius: 5px; border: 2px solid #333; margin-bottom: 30px; display: flex; gap: 15px; align-items: flex-end; }
        .filter-group { display: flex; flex-direction: column; gap: 5px; flex: 1; }
        .filter-group label { color: #888; font-size: 0.85rem; font-weight: bold; }
        .filter-group select, .filter-group input { background: #000; color: #fff; border: 1px solid #333; padding: 10px; border-radius: 3px; outline: none; }
        .filter-group select:focus, .filter-group input:focus { border-color: #f8c300; }
        
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

        .pagination-container { display: flex; flex-direction: column; align-items: center; margin-top: 20px; gap: 10px; }
        .custom-pagination { display: flex; gap: 5px; align-items: center; }
        .page-link { display: flex; align-items: center; justify-content: center; min-width: 35px; height: 35px; padding: 0 10px; border-radius: 3px; background: #1a1a1a; border: 1px solid #333; color: #fff; text-decoration: none; transition: all 0.3s; }
        .page-link:hover:not(.disabled) { border-color: #f8c300; color: #f8c300; }
        .page-link.active { background: #f8c300; color: #000; border-color: #f8c300; font-weight: bold; }
        .page-link.disabled { opacity: 0.5; cursor: not-allowed; }
        .page-dots { color: #666; margin: 0 5px; }
        .pagination-info { color: #666; font-size: 0.9rem; }
    </style>
</head>
<body>
    @include('components.insurance-header')

    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-list" style="color: #f8c300;"></i> Assistance Requests</h1>
            <p>View all assistance requests from users with {{ $insurance->company_name }} insurance</p>
        </div>

       

        @if($requests->count() > 0)
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
                    @foreach($requests as $request)
                        <tr>
                            <td>#{{ $request->id }}</td>
                            <td>
                                <div>{{ $request->user->name }}</div>
                                <div style="font-size: 0.8em; color: #666;">{{ $request->user->email }}</div>
                            </td>
                            <td>
                                @if($request->mechanic)
                                    <div>{{ $request->mechanic->user->name }}</div>
                                    <div style="font-size: 0.8em; color: #666;">{{ $request->mechanic->user->phone }}</div>
                                @else
                                    <span style="color: #666;">Not Assigned</span>
                                @endif
                            </td>
                            
                            <td>
                                <span class="status-badge {{ $request->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                </span>
                            </td>
                            <td>{{ $request->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <a href="{{ route('insurance_company.request.show', $request->id) }}" class="btn btn-secondary">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="pagination-container">
                {{ $requests->links('pagination.custom') }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-folder-open"></i>
                <p>No requests found matching your criteria</p>
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
