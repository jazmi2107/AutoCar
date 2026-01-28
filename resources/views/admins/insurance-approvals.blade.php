<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insurance Company Approvals - AutoCar Admin</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #000; color: #fff; margin: 0; font-family: Arial, sans-serif; }
        .container { max-width: 1400px; margin: 0 auto; padding: 30px 20px; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #f8c300; }
        .page-header h1 { margin: 0; font-size: 2.5rem; }
        
        .btn { display: inline-block; padding: 10px 20px; border-radius: 3px; text-decoration: none; font-weight: bold; transition: all 0.3s; cursor: pointer; border: none; text-align: center; font-size: 0.85rem; }
        .btn-primary { background: #f8c300; color: #000; }
        .btn-primary:hover { background: #fff; }
        .btn-back { background: #333; color: #fff; }
        .btn-back:hover { background: #444; }
        
        .alert { padding: 15px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid; }
        .alert-success { background: #1a4d2e; border-left-color: #4caf50; color: #4caf50; }
        .alert-danger { background: #4d1a1a; border-left-color: #f44336; color: #f44336; }
        .alert-info { background: #1a3a4d; border-left-color: #2196f3; color: #2196f3; }
        
        .tabs-nav { background: #1a1a1a; border-radius: 5px 5px 0 0; padding: 0; margin-bottom: 0; display: flex; border: 2px solid #333; border-bottom: none; }
        .tabs-nav button { flex: 1; padding: 15px; border: none; background: #1a1a1a; cursor: pointer; font-size: 1rem; font-weight: 600; color: #888; border-bottom: 3px solid transparent; transition: all 0.3s; }
        .tabs-nav button:first-child { border-radius: 5px 0 0 0; }
        .tabs-nav button:last-child { border-radius: 0 5px 0 0; }
        .tabs-nav button.active { color: #f8c300; border-bottom-color: #f8c300; background: #2a2a2a; }
        .tabs-nav button:hover:not(.active) { background: #252525; color: #fff; }
        
        .tab-content { display: none; background: #1a1a1a; border-radius: 0 0 5px 5px; padding: 30px; border: 2px solid #333; border-top: none; }
        .tab-content.active { display: block; }
        
        .cards-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .company-card { background: #2a2a2a; border: 2px solid #333; border-radius: 5px; padding: 20px; transition: all 0.3s; }
        .company-card:hover { border-color: #f8c300; transform: translateY(-3px); }
        
        .company-header { display: flex; gap: 15px; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #333; }
        .company-logo { width: 80px; height: 80px; border-radius: 5px; border: 2px solid #333; object-fit: cover; }
        .company-logo-placeholder { width: 80px; height: 80px; border-radius: 5px; background: #1a1a1a; border: 2px solid #333; display: flex; align-items: center; justify-content: center; }
        .company-logo-placeholder i { font-size: 2rem; color: #666; }
        .company-info h3 { margin: 0 0 8px; font-size: 1.2rem; color: #fff; }
        
        .status-badge { display: inline-block; padding: 5px 12px; border-radius: 3px; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; }
        .status-pending { background: #ff9800; color: #000; }
        .status-approved { background: #4caf50; color: #fff; }
        .status-rejected { background: #f44336; color: #fff; }
        
        .detail-row { margin-bottom: 15px; }
        .detail-label { font-size: 0.8rem; color: #888; text-transform: uppercase; margin-bottom: 5px; display: block; }
        .detail-value { color: #ddd; }
        .detail-value a { color: #f8c300; text-decoration: none; }
        .detail-value a:hover { color: #fff; text-decoration: underline; }
        
        .action-buttons { display: flex; gap: 10px; margin-top: 20px; }
        .btn-approve { background: #4caf50; color: #fff; flex: 1; }
        .btn-approve:hover { background: #388e3c; }
        .btn-reject { background: #f44336; color: #fff; flex: 1; }
        .btn-reject:hover { background: #c62828; }
        
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background: rgba(0,0,0,0.8); }
        .modal.show { display: flex; align-items: center; justify-content: center; }
        .modal-content { background: #1a1a1a; border: 2px solid #333; border-radius: 5px; padding: 0; width: 90%; max-width: 500px; }
        .modal-header { padding: 20px; border-bottom: 2px solid #333; display: flex; justify-content: space-between; align-items: center; }
        .modal-header h2 { margin: 0; font-size: 1.3rem; color: #fff; }
        .modal-close { background: none; border: none; color: #888; font-size: 1.5rem; cursor: pointer; padding: 0; width: 30px; height: 30px; }
        .modal-close:hover { color: #fff; }
        .modal-body { padding: 20px; }
        .modal-footer { padding: 20px; border-top: 1px solid #333; display: flex; gap: 10px; justify-content: flex-end; }
        
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; color: #fff; font-weight: bold; }
        .form-group label .required { color: #f44336; }
        .form-group textarea { width: 100%; padding: 12px; background: #000; border: 2px solid #333; border-radius: 3px; color: #fff; font-family: Arial, sans-serif; resize: vertical; }
        .form-group textarea:focus { outline: none; border-color: #f8c300; }
        .form-group small { color: #888; font-size: 0.85rem; }
        
        .btn-cancel { background: #666; color: #fff; }
        .btn-cancel:hover { background: #777; }
        
        .pagination { display: flex; justify-content: center; gap: 5px; margin-top: 20px; }
        .pagination a, .pagination span { padding: 8px 12px; background: #2a2a2a; color: #fff; border: 1px solid #333; border-radius: 3px; text-decoration: none; }
        .pagination a:hover { background: #333; border-color: #f8c300; }
        .pagination .active { background: #f8c300; color: #000; border-color: #f8c300; }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-shield-alt"></i> Insurance Company Approvals</h1>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-back">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <div class="tabs-nav">
            <button class="tab-button active" data-tab="pending">
                <i class="fas fa-clock"></i> Pending
                @if($pendingCompanies->total() > 0)
                    <span class="status-badge status-pending" style="margin-left: 8px;">{{ $pendingCompanies->total() }}</span>
                @endif
            </button>
            <button class="tab-button" data-tab="approved">
                <i class="fas fa-check-circle"></i> Approved
                @if($approvedCompanies->total() > 0)
                    <span class="status-badge status-approved" style="margin-left: 8px;">{{ $approvedCompanies->total() }}</span>
                @endif
            </button>
            <button class="tab-button" data-tab="rejected">
                <i class="fas fa-times-circle"></i> Rejected
                @if($rejectedCompanies->total() > 0)
                    <span class="status-badge status-rejected" style="margin-left: 8px;">{{ $rejectedCompanies->total() }}</span>
                @endif
            </button>
        </div>

        <!-- Pending Tab -->
        <div class="tab-content active" id="pending">
            @if($pendingCompanies->count() > 0)
                <div class="cards-grid">
                    @foreach($pendingCompanies as $company)
                        <div class="company-card">
                            <div class="company-header">
                                @if($company->profile_picture)
                                    <img src="{{ asset('storage/' . $company->profile_picture) }}" alt="{{ $company->company_name }}" class="company-logo">
                                @else
                                    <div class="company-logo-placeholder">
                                        <i class="fas fa-building"></i>
                                    </div>
                                @endif
                                <div class="company-info">
                                    <h3>{{ $company->company_name }}</h3>
                                    <span class="status-badge status-pending">Pending Approval</span>
                                </div>
                            </div>

                            <div class="detail-row">
                                <span class="detail-label"><i class="fas fa-id-card"></i> Registration Number</span>
                                <span class="detail-value">{{ $company->registration_number }}</span>
                            </div>

                            @if($company->phone_number)
                                <div class="detail-row">
                                    <span class="detail-label"><i class="fas fa-phone"></i> Phone</span>
                                    <span class="detail-value">{{ $company->phone_number }}</span>
                                </div>
                            @endif

                            @if($company->address)
                                <div class="detail-row">
                                    <span class="detail-label"><i class="fas fa-map-marker-alt"></i> Address</span>
                                    <span class="detail-value">{{ $company->address }}</span>
                                </div>
                            @endif

                            @if($company->website)
                                <div class="detail-row">
                                    <span class="detail-label"><i class="fas fa-globe"></i> Website</span>
                                    <span class="detail-value"><a href="{{ $company->website }}" target="_blank">{{ $company->website }}</a></span>
                                </div>
                            @endif

                            <div class="action-buttons">
                                <form action="{{ route('admin.insurance.approve', $company->id) }}" method="POST" style="flex: 1;">
                                    @csrf
                                    <button type="submit" class="btn btn-approve" style="width: 100%;">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                </form>
                                <button type="button" class="btn btn-reject" onclick="openRejectModal({{ $company->id }}, '{{ addslashes($company->company_name) }}')">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            </div>
                        </div>

                        <!-- Reject Modal -->
                        <div class="modal" id="rejectModal{{ $company->id }}">
                            <div class="modal-content">
                                <form action="{{ route('admin.insurance.reject', $company->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h2>Reject {{ $company->company_name }}</h2>
                                        <button type="button" class="modal-close" onclick="closeRejectModal({{ $company->id }})">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="rejection_reason{{ $company->id }}">
                                                Rejection Reason <span class="required">*</span>
                                            </label>
                                            <textarea 
                                                class="form-control" 
                                                id="rejection_reason{{ $company->id }}" 
                                                name="rejection_reason" 
                                                rows="4" 
                                                required 
                                                maxlength="500" 
                                                placeholder="Please provide a clear reason for rejection..."
                                            ></textarea>
                                            <small>Maximum 500 characters</small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-cancel" onclick="closeRejectModal({{ $company->id }})">Cancel</button>
                                        <button type="submit" class="btn btn-reject">
                                            <i class="fas fa-times"></i> Reject Company
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pagination">
                    {{ $pendingCompanies->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No pending insurance companies at the moment.
                </div>
            @endif
        </div>

        <!-- Approved Tab -->
        <div class="tab-content" id="approved">
            @if($approvedCompanies->count() > 0)
                <div class="cards-grid">
                    @foreach($approvedCompanies as $company)
                        <div class="company-card">
                            <div class="company-header">
                                @if($company->profile_picture)
                                    <img src="{{ asset('storage/' . $company->profile_picture) }}" alt="{{ $company->company_name }}" class="company-logo">
                                @else
                                    <div class="company-logo-placeholder">
                                        <i class="fas fa-building"></i>
                                    </div>
                                @endif
                                <div class="company-info">
                                    <h3>{{ $company->company_name }}</h3>
                                    <span class="status-badge status-approved">Approved</span>
                                </div>
                            </div>

                            <div class="detail-row">
                                <span class="detail-label"><i class="fas fa-id-card"></i> Registration Number</span>
                                <span class="detail-value">{{ $company->registration_number }}</span>
                            </div>

                            @if($company->phone_number)
                                <div class="detail-row">
                                    <span class="detail-label"><i class="fas fa-phone"></i> Phone</span>
                                    <span class="detail-value">{{ $company->phone_number }}</span>
                                </div>
                            @endif

                            @if($company->website)
                                <div class="detail-row">
                                    <span class="detail-label"><i class="fas fa-globe"></i> Website</span>
                                    <span class="detail-value"><a href="{{ $company->website }}" target="_blank">{{ $company->website }}</a></span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="pagination">
                    {{ $approvedCompanies->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No approved insurance companies yet.
                </div>
            @endif
        </div>

        <!-- Rejected Tab -->
        <div class="tab-content" id="rejected">
            @if($rejectedCompanies->count() > 0)
                <div class="cards-grid">
                    @foreach($rejectedCompanies as $company)
                        <div class="company-card">
                            <div class="company-header">
                                @if($company->profile_picture)
                                    <img src="{{ asset('storage/' . $company->profile_picture) }}" alt="{{ $company->company_name }}" class="company-logo">
                                @else
                                    <div class="company-logo-placeholder">
                                        <i class="fas fa-building"></i>
                                    </div>
                                @endif
                                <div class="company-info">
                                    <h3>{{ $company->company_name }}</h3>
                                    <span class="status-badge status-rejected">Rejected</span>
                                </div>
                            </div>

                            <div class="detail-row">
                                <span class="detail-label"><i class="fas fa-id-card"></i> Registration Number</span>
                                <span class="detail-value">{{ $company->registration_number }}</span>
                            </div>

                            @if($company->rejection_reason)
                                <div class="detail-row">
                                    <span class="detail-label"><i class="fas fa-exclamation-triangle"></i> Rejection Reason</span>
                                    <span class="detail-value" style="color: #f44336;">{{ $company->rejection_reason }}</span>
                                </div>
                            @endif

                            <div class="action-buttons">
                                <form action="{{ route('admin.insurance.approve', $company->id) }}" method="POST" style="width: 100%;">
                                    @csrf
                                    <button type="submit" class="btn btn-approve" style="width: 100%;">
                                        <i class="fas fa-check"></i> Approve Company
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pagination">
                    {{ $rejectedCompanies->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No rejected insurance companies.
                </div>
            @endif
        </div>
    </div>

    <script>
        // Tab switching
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', function() {
                const tabName = this.getAttribute('data-tab');
                
                // Remove active class from all buttons and tabs
                document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
                
                // Add active class to clicked button and corresponding tab
                this.classList.add('active');
                document.getElementById(tabName).classList.add('active');
            });
        });

        // Modal functions
        function openRejectModal(id, name) {
            document.getElementById('rejectModal' + id).classList.add('show');
        }

        function closeRejectModal(id) {
            document.getElementById('rejectModal' + id).classList.remove('show');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('show');
            }
        }
    </script>
</body>
</html>
