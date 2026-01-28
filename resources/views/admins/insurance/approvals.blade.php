<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insurance Company Approvals - Admin Dashboard</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #000; color: #fff; margin: 0; font-family: Arial, sans-serif; }
        .container { max-width: 1400px; margin: 0 auto; padding: 30px 20px; }
        .page-header { margin-bottom: 40px; padding-bottom: 20px; border-bottom: 2px solid #f8c300; display: flex; justify-content: space-between; align-items: center; }
        .page-header h1 { margin: 0 0 10px; font-size: 2.5rem; }
        .page-header p { color: #888; margin: 0; }
        
        table { width: 100%; background: #1a1a1a; border-collapse: collapse; border-radius: 5px; overflow: hidden; border: 2px solid #333; margin-bottom: 30px; }
        thead { background: #2a2a2a; }
        th { padding: 15px; text-align: left; color: #f8c300; font-weight: bold; text-transform: uppercase; font-size: 0.9rem; border-bottom: 2px solid #333; }
        td { padding: 15px; color: #ddd; border-bottom: 1px solid #333; }
        tbody tr { transition: background 0.3s; }
        tbody tr:hover { background: #2a2a2a; }
        tbody tr:last-child td { border-bottom: none; }
        td i { color: #f8c300; margin-right: 8px; }
        
        .btn { display: inline-block; padding: 8px 15px; border-radius: 3px; text-decoration: none; font-weight: bold; transition: all 0.3s; cursor: pointer; border: none; text-align: center; font-size: 0.85rem; }
        .btn-success { background: #4caf50; color: #fff; }
        .btn-success:hover { background: #66bb6a; }
        .btn-secondary { background: #333; color: #fff; border: 2px solid #555; }
        .btn-secondary:hover { border-color: #f8c300; }
        
        .empty-state { text-align: center; padding: 40px 20px; color: #666; background: #1a1a1a; border-radius: 5px; border: 2px solid #333; }
        .empty-state i { font-size: 3rem; margin-bottom: 15px; color: #333; }
        .empty-state p { margin: 0; font-size: 1rem; }
        
        .alert { padding: 15px 20px; margin-bottom: 20px; border-radius: 5px; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #4caf50; color: #fff; }
        .alert-error { background: #f44336; color: #fff; }
        
        .action-buttons { display: flex; gap: 5px; }

        /* Pagination styles matching the dark theme */
        .pagination { display: flex; padding-left: 0; list-style: none; }
        .page-link { position: relative; display: block; padding: 0.5rem 0.75rem; margin-left: -1px; line-height: 1.25; color: #f8c300; background-color: #1a1a1a; border: 1px solid #333; text-decoration: none; }
        .page-link:hover { z-index: 2; color: #fff; text-decoration: none; background-color: #333; border-color: #f8c300; }
        .page-item.active .page-link { z-index: 3; color: #000; background-color: #f8c300; border-color: #f8c300; }
        .page-item.disabled .page-link { color: #666; pointer-events: none; cursor: auto; background-color: #1a1a1a; border-color: #333; }
        
        /* Modal Styles */
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.8); z-index: 1000; justify-content: center; align-items: center; }
        .modal-content { background: #1a1a1a; padding: 25px; border-radius: 5px; border: 2px solid #f8c300; width: 400px; max-width: 90%; position: relative; animation: slideIn 0.3s ease; }
        @keyframes slideIn { from { transform: translateY(-20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .modal-header { margin-bottom: 20px; border-bottom: 1px solid #333; padding-bottom: 10px; font-size: 1.2rem; font-weight: bold; color: #f8c300; }
        .modal-body { margin-bottom: 20px; color: #ddd; }
        .modal-footer { display: flex; justify-content: flex-end; gap: 10px; }
        .form-control { width: 100%; padding: 10px; background: #333; border: 1px solid #555; color: #fff; border-radius: 3px; margin-top: 10px; box-sizing: border-box; }
        .form-control:focus { outline: none; border-color: #f8c300; }
        .text-danger { color: #f44336; }
        .btn-danger { background: #f44336; color: #fff; }
        .btn-danger:hover { background: #d32f2f; }
    </style>
</head>
<body>
    @include('components.admin-header')

    <div class="container">
        <div class="page-header">
            <div>
                <h1><i class="fas fa-building" style="color: #f8c300;"></i> Insurance Company Approvals</h1>
                <p>Review and approve new insurance company registrations</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
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
                <i class="fas fa-building"></i>
                <h3>No Pending Approvals</h3>
                <p>All insurance companies have been processed.</p>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Company Name</th>
                        <th>Email</th>
                        <th>Registration No.</th>
                        <th>Phone</th>
                        <th>Registration Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($companies as $company)
                        <tr>
                            <td><strong>{{ $company->company_name }}</strong></td>
                            <td>{{ $company->user->email }}</td>
                            <td>{{ $company->registration_number }}</td>
                            <td>{{ $company->phone_number ?? 'N/A' }}</td>
                            <td>{{ $company->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button type="button" class="btn btn-success" onclick="openApproveModal({{ $company->id }}, '{{ addslashes($company->company_name) }}')">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                    <button type="button" class="btn btn-danger" onclick="openRejectModal({{ $company->id }}, '{{ addslashes($company->company_name) }}')">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div style="margin-top: 20px;">
                {{ $companies->links() }}
            </div>
        @endif
    </div>

    <!-- Approve Modal -->
    <div id="approveModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">Approve Company</div>
            <div class="modal-body">
                Are you sure you want to approve <strong id="approveName" style="color: #fff;"></strong>?
                <p style="margin-top: 10px; font-size: 0.9rem; color: #aaa;">This will grant them access to the insurance company dashboard.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('approveModal')">Cancel</button>
                <form id="approveForm" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">Confirm Approve</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="modal-overlay">
        <div class="modal-content" style="border-color: #f44336;">
            <div class="modal-header" style="color: #f44336;">Reject Company</div>
            <div class="modal-body">
                Are you sure you want to reject <strong id="rejectName" style="color: #fff;"></strong>?
                <form id="rejectForm" method="POST">
                    @csrf
                    <textarea name="rejection_reason" class="form-control" rows="3" placeholder="Reason for rejection (optional)"></textarea>
                    <div class="modal-footer" style="margin-top: 20px; padding: 0;">
                        <button type="button" class="btn btn-secondary" onclick="closeModal('rejectModal')">Cancel</button>
                        <button type="submit" class="btn btn-danger">Confirm Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openApproveModal(id, name) {
            document.getElementById('approveForm').action = "{{ url('admin/insurance') }}/" + id + "/approve";
            document.getElementById('approveName').innerText = name;
            document.getElementById('approveModal').style.display = 'flex';
        }

        function openRejectModal(id, name) {
            document.getElementById('rejectForm').action = "{{ url('admin/insurance') }}/" + id + "/reject";
            document.getElementById('rejectName').innerText = name;
            document.getElementById('rejectModal').style.display = 'flex';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal-overlay')) {
                event.target.style.display = 'none';
            }
        }
    </script>

    <script>
        // User Dropdown Toggle if header component needs it, though it should be handled in the component or layout
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