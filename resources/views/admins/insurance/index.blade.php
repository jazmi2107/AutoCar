<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insurance Company Management - AutoCar Admin</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #000; color: #fff; margin: 0; font-family: Arial, sans-serif; }
        .container { max-width: 1400px; margin: 0 auto; padding: 30px 20px; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #f8c300; }
        .page-header h1 { margin: 0; font-size: 2.5rem; }
        
        .stats-overview { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-mini { background: #1a1a1a; padding: 20px; border-radius: 5px; border: 2px solid #333; transition: all 0.3s; }
        .stat-mini:hover { border-color: #f8c300; transform: translateY(-3px); }
        .stat-mini i { font-size: 2rem; color: #ff9800; margin-bottom: 10px; }
        .stat-mini h3 { margin: 0; font-size: 1.8rem; color: #fff; }
        .stat-mini p { margin: 5px 0 0; color: #888; font-size: 0.85rem; text-transform: uppercase; }
        
        table { width: 100%; background: #1a1a1a; border-collapse: collapse; border-radius: 5px; overflow: hidden; border: 2px solid #333; }
        thead { background: #2a2a2a; }
        th { padding: 15px; text-align: left; color: #f8c300; font-weight: bold; text-transform: uppercase; font-size: 0.9rem; border-bottom: 2px solid #333; }
        td { padding: 15px; color: #ddd; border-bottom: 1px solid #333; }
        tbody tr { transition: background 0.3s; }
        tbody tr:hover { background: #2a2a2a; }
        tbody tr:last-child td { border-bottom: none; }
        
        .company-info { display: flex; align-items: center; gap: 15px; }
        .company-avatar { width: 45px; height: 45px; border-radius: 50%; border: 2px solid #ff9800; }
        .company-details h4 { margin: 0; color: #fff; font-size: 1rem; }
        .company-details p { margin: 3px 0 0; color: #888; font-size: 0.85rem; }
        
        .status-badge { display: inline-block; padding: 5px 12px; border-radius: 3px; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; }
        .status-pending { background: #ff9800; color: #000; }
        .status-approved { background: #4caf50; color: #fff; }
        .status-rejected { background: #f44336; color: #fff; }
        
        .btn { display: inline-block; padding: 10px 20px; border-radius: 3px; text-decoration: none; font-weight: bold; transition: all 0.3s; cursor: pointer; border: none; text-align: center; font-size: 0.85rem; }
        .btn-primary { background: #f8c300; color: #000; }
        .btn-primary:hover { background: #fff; }
        .btn-view { background: #4caf50; color: #fff; padding: 8px 15px; }
        .btn-view:hover { background: #388e3c; }
        .btn-approve { background: #4caf50; color: #fff; padding: 8px 15px; }
        .btn-approve:hover { background: #388e3c; }
        .btn-reject { background: transparent; border: 2px solid #f44336; color: #f44336; padding: 6px 13px; }
        .btn-reject:hover { background: #f44336; color: #fff; }
        
        .action-buttons { display: flex; gap: 8px; }
        
        .alert { padding: 15px 20px; margin-bottom: 20px; border-radius: 5px; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #4caf50; color: #fff; }
        .alert-error { background: #f44336; color: #fff; }
        .filter-section { margin-bottom: 25px; }
        .filter-form { background: #1a1a1a; padding: 20px; border-radius: 5px; border: 2px solid #333; }
        .filter-group { display: flex; gap: 15px; align-items: center; }
        .filter-group input[type="text"] { flex: 2; padding: 12px 15px; background: #222; border: 2px solid #333; color: #fff; border-radius: 3px; font-size: 0.95rem; }
        .filter-group input[type="text"]:focus { outline: none; border-color: #f8c300; }
        .filter-group select { flex: 1; padding: 12px 15px; background: #222; border: 2px solid #333; color: #fff; border-radius: 3px; font-size: 0.95rem; }
        .filter-group select:focus { outline: none; border-color: #f8c300; }
        .btn-filter { padding: 12px 25px; background: #f8c300; color: #000; border: none; border-radius: 3px; font-weight: bold; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.3s; }
        .btn-filter:hover { background: #fff; }
        .btn-clear { padding: 12px 25px; background: #333; color: #fff; border: 2px solid #555; border-radius: 3px; font-weight: bold; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: all 0.3s; }
        .btn-clear:hover { border-color: #f44336; color: #f44336; }
        
        .empty-state { text-align: center; padding: 60px 20px; color: #666; background: #1a1a1a; border-radius: 5px; border: 2px solid #333; }
        .empty-state i { font-size: 4rem; margin-bottom: 20px; color: #333; }
        .empty-state h3 { color: #888; margin: 0 0 10px; }
        .empty-state p { color: #666; margin: 0; }
        
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
        
        /* Delete Confirmation Modal */
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.85); z-index: 1000; align-items: center; justify-content: center; }
        .modal-overlay.show { display: flex; }
        .modal-content { background: #1a1a1a; border: 2px solid #f8c300; border-radius: 8px; padding: 30px; max-width: 450px; width: 90%; box-shadow: 0 10px 40px rgba(248, 195, 0, 0.3); animation: modalSlideIn 0.3s ease; }
        @keyframes modalSlideIn { from { opacity: 0; transform: translateY(-30px); } to { opacity: 1; transform: translateY(0); } }
        .modal-header { display: flex; align-items: center; gap: 15px; margin-bottom: 20px; }
        .modal-header i { font-size: 2.5rem; color: #f44336; }
        .modal-header h3 { margin: 0; color: #fff; font-size: 1.5rem; }
        .modal-body { margin-bottom: 25px; }
        .modal-body p { color: #ccc; margin: 0 0 10px; line-height: 1.6; }
        .modal-body .user-highlight { background: #2a2a2a; padding: 12px; border-radius: 5px; border-left: 3px solid #f8c300; margin-top: 15px; }
        .modal-body .user-highlight strong { color: #f8c300; }
        .modal-footer { display: flex; gap: 12px; justify-content: flex-end; }
        .modal-btn { padding: 12px 25px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; transition: all 0.3s; font-size: 0.95rem; }
        .modal-btn-cancel { background: #333; color: #fff; border: 2px solid #555; }
        .modal-btn-cancel:hover { background: #444; border-color: #666; }
        .modal-btn-confirm { background: #f44336; color: #fff; }
        .modal-btn-confirm:hover { background: #d32f2f; }
        .btn-edit { background: #2196F3; color: #fff; padding: 8px 15px; }
        .btn-edit:hover { background: #1976D2; }
        .btn-delete { background: transparent; border: 2px solid #f44336; color: #f44336; padding: 6px 13px; }
        .btn-delete:hover { background: #f44336; color: #fff; }
    </style>
</head>
<body>
    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal-content">
            <div class="modal-header">
                <i class="fas fa-exclamation-triangle"></i>
                <h3>Confirm Deletion</h3>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this insurance company?</p>
                <div class="user-highlight">
                    <strong id="deleteCompanyName"></strong>
                </div>
                <p style="color: #f44336; margin-top: 15px; font-size: 0.9rem;">
                    <i class="fas fa-info-circle"></i> This action cannot be undone.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn modal-btn-cancel" onclick="closeDeleteModal()">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="button" class="modal-btn modal-btn-confirm" onclick="confirmDelete()">
                    <i class="fas fa-trash"></i> Delete Company
                </button>
            </div>
        </div>
    </div>

    @include('components.admin-header')

    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-shield-alt" style="color: #ff9800;"></i> Insurance Company Management</h1>
            <a href="{{ route('admin.insurance.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Add New Company
            </a>
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

        

        @if($companies->isEmpty())
            <div class="empty-state">
                <i class="fas fa-shield-alt"></i>
                <h3>No Insurance Companies Found</h3>
                <p>{{ request('search') || request('status') ? 'Try adjusting your filters' : 'No insurance companies registered yet' }}</p>
            </div>
        @else
            <div class="stats-overview">
                <div class="stat-mini">
                    <i class="fas fa-shield-alt"></i>
                    <h3>{{ $companies->total() }}</h3>
                    <p>Total Companies</p>
                </div>
                <div class="stat-mini">
                    <i class="fas fa-check-circle"></i>
                    <h3>{{ $companies->where('approval_status', 'approved')->count() }}</h3>
                    <p>Approved</p>
                </div>
                <div class="stat-mini">
                    <i class="fas fa-times-circle"></i>
                    <h3>{{ $companies->where('approval_status', 'rejected')->count() }}</h3>
                    <p>Rejected</p>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th style="width: 25%;">Company</th>
                        <th style="width: 15%;">Email</th>
                        <th style="width: 13%;">Phone</th>
                        <th style="width: 10%;">Mechanics</th>
                        <th style="width: 12%;">Status</th>
                        <th style="width: 10%;">Registered</th>
                        <th style="width: 15%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($companies as $company)
                        <tr>
                            <td>
                                <div class="company-info">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($company->company_name) }}&background=ff9800" alt="Avatar" class="company-avatar">
                                    <div class="company-details">
                                        <h4>{{ $company->company_name }}</h4>
                                        <p>{{ $company->registration_number ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $company->user->email }}</td>
                            <td><i class="fas fa-phone" style="color: #ff9800; margin-right: 5px;"></i>{{ $company->phone_number ?? 'N/A' }}</td>
                            <td><i class="fas fa-users" style="color: #f8c300; margin-right: 5px;"></i>{{ $company->mechanics_count }}</td>
                            <td><span class="status-badge status-{{ $company->approval_status }}">{{ ucfirst($company->approval_status) }}</span></td>
                            <td>{{ $company->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.insurance.show', $company->id) }}" class="btn btn-view">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="{{ route('admin.insurance.edit', $company->id) }}" class="btn btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form id="delete-form-{{ $company->id }}" action="{{ route('admin.insurance.delete', $company->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-delete" onclick="showDeleteModal({{ $company->id }}, '{{ addslashes($company->company_name) }}', '{{ addslashes($company->registration_number ?? 'N/A') }}')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($companies->hasPages())
                <div class="pagination">
                    @if($companies->onFirstPage())
                        <span class="disabled"><i class="fas fa-chevron-left"></i> Prev</span>
                    @else
                        <a href="{{ $companies->appends(request()->query())->previousPageUrl() }}"><i class="fas fa-chevron-left"></i> Prev</a>
                    @endif
                    
                    @foreach($companies->getUrlRange(1, $companies->lastPage()) as $page => $url)
                        @if($page == $companies->currentPage())
                            <span class="active">{{ $page }}</span>
                        @else
                            <a href="{{ $companies->appends(request()->query())->url($page) }}">{{ $page }}</a>
                        @endif
                    @endforeach
                    
                    @if($companies->hasMorePages())
                        <a href="{{ $companies->appends(request()->query())->nextPageUrl() }}">Next <i class="fas fa-chevron-right"></i></a>
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

        // Delete Modal Functions
        let deleteFormId = null;

        function showDeleteModal(companyId, companyName, registrationNumber) {
            deleteFormId = 'delete-form-' + companyId;
            document.getElementById('deleteCompanyName').innerHTML = companyName + '<br><span style="color: #888; font-size: 0.9rem;">' + registrationNumber + '</span>';
            document.getElementById('deleteModal').classList.add('show');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('show');
            deleteFormId = null;
        }

        function confirmDelete() {
            if (deleteFormId) {
                document.getElementById(deleteFormId).submit();
            }
        }

        // Close modal on overlay click
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
            }
        });
    </script>
</body>
</html>
