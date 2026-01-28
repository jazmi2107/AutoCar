<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approvals - Admin Dashboard</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #000; color: #fff; margin: 0; font-family: Arial, sans-serif; }
        .container { max-width: 1400px; margin: 0 auto; padding: 30px 20px; }
        .page-header { margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #f8c300; display: flex; justify-content: space-between; align-items: center; }
        .page-header h1 { margin: 0 0 10px; font-size: 2.5rem; }
        .page-header p { color: #888; margin: 0; }
        
        /* Tabs */
        .tabs { display: flex; gap: 10px; margin-bottom: 20px; border-bottom: 1px solid #333; }
        .tab-btn { background: #1a1a1a; color: #888; border: none; padding: 12px 25px; font-size: 1rem; cursor: pointer; border-radius: 5px 5px 0 0; transition: all 0.3s; font-weight: bold; }
        .tab-btn:hover { background: #333; color: #fff; }
        .tab-btn.active { background: #f8c300; color: #000; }
        
        .tab-content { display: none; animation: fadeIn 0.3s ease; }
        .tab-content.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

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
        .btn-danger { background: #f44336; color: #fff; }
        .btn-danger:hover { background: #d32f2f; }
        
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
    </style>
</head>
<body>
    @include('components.admin-header')

    <div class="container">
        <div class="page-header">
            <div>
                <h1><i class="fas fa-check-circle" style="color: #f8c300;"></i> Pending Approvals</h1>
                <p>Manage mechanic and insurance company registrations</p>
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

        <div class="tabs">
            <button class="tab-btn active" onclick="openTab('mechanics')">
                <i class="fas fa-wrench"></i> Mechanics 
                @if($pendingMechanics->total() > 0) <span style="background: #f44336; color: white; padding: 2px 6px; border-radius: 50%; font-size: 0.7em;">{{ $pendingMechanics->total() }}</span> @endif
            </button>
            <button class="tab-btn" onclick="openTab('insurance')">
                <i class="fas fa-building"></i> Insurance Companies
                @if($pendingInsurance->total() > 0) <span style="background: #f44336; color: white; padding: 2px 6px; border-radius: 50%; font-size: 0.7em;">{{ $pendingInsurance->total() }}</span> @endif
            </button>
        </div>

        <!-- Mechanics Tab -->
        <div id="mechanics" class="tab-content active">
            @if($pendingMechanics->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-user-check"></i>
                    <h3>No Pending Mechanic Approvals</h3>
                    <p>All mechanics have been processed.</p>
                </div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Company</th>
                            <th>Registration Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingMechanics as $mechanic)
                            <tr>
                                <td><strong>{{ $mechanic->user->name ?? ($mechanic->name ?? 'Unknown') }}</strong></td>
                                <td>{{ $mechanic->user->email ?? ($mechanic->email ?? 'N/A') }}</td>
                                <td>{{ $mechanic->driver->phone_number ?? 'N/A' }}</td>
                                <td>{{ $mechanic->company_name ?? 'Independent' }}</td>
                                <td>
                                    @if(isset($mechanic->created_at) && method_exists($mechanic->created_at, 'format'))
                                        {{ $mechanic->created_at->format('M d, Y') }}
                                    @else
                                        {{ $mechanic->created_at ?? 'N/A' }}
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button type="button" class="btn btn-success" onclick="openApproveModal('{{ route('admin.mechanics.approve', $mechanic->id) }}', '{{ addslashes($mechanic->user->name ?? ($mechanic->name ?? 'Unknown')) }}')">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                        <button type="button" class="btn btn-danger" onclick="openRejectModal('{{ route('admin.mechanics.reject', $mechanic->id) }}', '{{ addslashes($mechanic->user->name ?? ($mechanic->name ?? 'Unknown')) }}')">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="margin-top: 20px;">
                    {{ $pendingMechanics->appends(['insurance_page' => $pendingInsurance->currentPage()])->links() }}
                </div>
            @endif
        </div>

        <!-- Insurance Tab -->
        <div id="insurance" class="tab-content">
            @if($pendingInsurance->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-building-circle-check"></i>
                    <h3>No Pending Insurance Approvals</h3>
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
                        @foreach($pendingInsurance as $company)
                            <tr>
                                <td><strong>{{ $company->company_name ?? ($company->name ?? 'Unknown') }}</strong></td>
                                <td>{{ $company->user->email ?? ($company->email ?? 'N/A') }}</td>
                                <td>{{ $company->registration_number ?? 'N/A' }}</td>
                                <td>{{ $company->phone_number ?? 'N/A' }}</td>
                                <td>
                                    @if(isset($company->created_at) && method_exists($company->created_at, 'format'))
                                        {{ $company->created_at->format('M d, Y') }}
                                    @else
                                        {{ $company->created_at ?? 'N/A' }}
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button type="button" class="btn btn-success" onclick="openApproveModal('{{ route('admin.insurance.approve', $company->id) }}', '{{ addslashes($company->company_name ?? ($company->name ?? 'Unknown')) }}')">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                        <button type="button" class="btn btn-danger" onclick="openRejectModal('{{ route('admin.insurance.reject', $company->id) }}', '{{ addslashes($company->company_name ?? ($company->name ?? 'Unknown')) }}')">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="margin-top: 20px;">
                    {{ $pendingInsurance->appends(['mechanics_page' => $pendingMechanics->currentPage()])->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Universal Approve Modal -->
    <div id="approveModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">Approve Registration</div>
            <div class="modal-body">
                Are you sure you want to approve <strong id="approveName" style="color: #fff;"></strong>?
                <p style="margin-top: 10px; font-size: 0.9rem; color: #aaa;">This will grant them access to the system.</p>
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

    <!-- Universal Reject Modal -->
    <div id="rejectModal" class="modal-overlay">
        <div class="modal-content" style="border-color: #f44336;">
            <div class="modal-header" style="color: #f44336;">Reject Registration</div>
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
        function openTab(tabName) {
            // Hide all contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            // Deactivate all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            // Show selected content and activate button
            document.getElementById(tabName).classList.add('active');
            event.currentTarget.classList.add('active');
        }

        function openApproveModal(actionUrl, name) {
            document.getElementById('approveForm').action = actionUrl;
            document.getElementById('approveName').innerText = name;
            document.getElementById('approveModal').style.display = 'flex';
        }

        function openRejectModal(actionUrl, name) {
            document.getElementById('rejectForm').action = actionUrl;
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