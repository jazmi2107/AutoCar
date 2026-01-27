<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $company->company_name }} - AutoCar Admin</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #000; color: #fff; margin: 0; font-family: Arial, sans-serif; }
        .container { max-width: 1400px; margin: 0 auto; padding: 30px 20px; }
        
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #f8c300; }
        .page-header h1 { margin: 0; font-size: 2rem; }
        .breadcrumb { color: #888; font-size: 0.9rem; margin-bottom: 5px; }
        .breadcrumb a { color: #f8c300; text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }
        
        .profile-card { background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%); padding: 40px; border-radius: 15px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(248, 195, 0, 0.1); border: 2px solid #333; }
        .profile-header { display: flex; gap: 30px; align-items: center; margin-bottom: 30px; }
        .profile-avatar { width: 120px; height: 120px; border-radius: 50%; border: 4px solid #ff9800; object-fit: cover; flex-shrink: 0; }
        .profile-info h2 { margin: 0 0 10px 0; font-size: 2rem; color: #fff; }
        .profile-meta { display: flex; gap: 20px; flex-wrap: wrap; margin-top: 15px; }
        .profile-meta span { display: inline-flex; align-items: center; gap: 8px; padding: 8px 15px; background: rgba(255, 152, 0, 0.2); border-radius: 20px; font-size: 0.9rem; color: #ff9800; }
        
        .status-badge { display: inline-block; padding: 5px 15px; border-radius: 20px; font-size: 0.85rem; font-weight: bold; text-transform: uppercase; }
        .status-badge.approved { background: rgba(76, 175, 80, 0.2); color: #4caf50; border: 1px solid #4caf50; }
        .status-badge.pending { background: rgba(255, 152, 0, 0.2); color: #ff9800; border: 1px solid #ff9800; }
        .status-badge.rejected { background: rgba(244, 67, 54, 0.2); color: #f44336; border: 1px solid #f44336; }
        
        .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .info-item { background: #1a1a1a; padding: 20px; border-radius: 8px; border-left: 4px solid #ff9800; }
        .info-item label { display: block; color: #888; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px; }
        .info-item .value { color: #fff; font-size: 1.1rem; word-break: break-word; }
        .info-item i { color: #ff9800; margin-right: 8px; }
        .info-item.full-width { grid-column: 1 / -1; }
        
        .stats-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%); padding: 25px; border-radius: 10px; text-align: center; box-shadow: 0 4px 10px rgba(255, 152, 0, 0.3); }
        .stat-card i { font-size: 2.5rem; margin-bottom: 15px; color: rgba(0, 0, 0, 0.3); }
        .stat-card .stat-value { font-size: 2.5rem; font-weight: bold; margin-bottom: 5px; color: #000; }
        .stat-card .stat-label { font-size: 0.95rem; color: rgba(0, 0, 0, 0.7); text-transform: uppercase; font-weight: bold; }
        
        .section-title { font-size: 1.5rem; margin: 30px 0 20px 0; padding-bottom: 10px; border-bottom: 2px solid #ff9800; color: #ff9800; display: flex; align-items: center; gap: 10px; }
        
        .table-container { background: #1a1a1a; border-radius: 8px; overflow: hidden; border: 2px solid #333; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%); color: #000; padding: 15px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; }
        td { padding: 15px; border-bottom: 1px solid #333; color: #fff; }
        tbody tr:hover { background: rgba(255, 152, 0, 0.1); }
        tbody tr:last-child td { border-bottom: none; }
        
        .no-data { text-align: center; padding: 40px; color: #888; font-size: 1.1rem; }
        .no-data i { font-size: 3rem; margin-bottom: 15px; display: block; color: #555; }
        
        .btn { display: inline-block; padding: 12px 30px; border-radius: 5px; text-decoration: none; font-weight: bold; transition: all 0.3s; cursor: pointer; border: none; text-align: center; font-size: 0.95rem; }
        .btn i { margin-right: 8px; }
        .btn-primary { background: #f8c300; color: #000; }
        .btn-primary:hover { background: #fff; }
        .btn-secondary { background: #333; color: #fff; border: 2px solid #555; }
        .btn-secondary:hover { background: #444; border-color: #666; }
        
        .action-buttons { display: flex; gap: 15px; margin-top: 30px; flex-wrap: wrap; }
    </style>
</head>
<body>
    @include('components.admin-header')

    <div class="container">
        <div class="page-header">
            <div>
                <div class="breadcrumb">
                    <a href="{{ route('admin.insurance') }}"><i class="fas fa-shield-alt"></i> Insurance Companies</a> / Company Details
                </div>
                <h1>Insurance Company Details</h1>
            </div>
        </div>

        <!-- Profile Card -->
        <div class="profile-card">
            <div class="profile-header">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($company->company_name) }}&size=120&background=ff9800&color=000&bold=true" 
                     alt="{{ $company->company_name }}" 
                     class="profile-avatar">
                <div class="profile-info">
                    <h2>{{ $company->company_name }}</h2>
                    <span class="status-badge {{ $company->approval_status }}">
                        @if($company->approval_status === 'approved')
                            <i class="fas fa-check-circle"></i> Approved
                        @elseif($company->approval_status === 'rejected')
                            <i class="fas fa-times-circle"></i> Rejected
                        @else
                            <i class="fas fa-clock"></i> Pending
                        @endif
                    </span>
                    <div class="profile-meta">
                        <span><i class="fas fa-id-card"></i> Reg: {{ $company->registration_number }}</span>
                        <span><i class="fas fa-calendar-alt"></i> Member Since {{ $company->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <label><i class="fas fa-envelope"></i> Email Address</label>
                    <div class="value">{{ $company->user->email }}</div>
                </div>

                <div class="info-item">
                    <label><i class="fas fa-phone"></i> Phone Number</label>
                    <div class="value">{{ $company->phone_number ?? 'Not Provided' }}</div>
                </div>

                <div class="info-item">
                    <label><i class="fas fa-id-card"></i> Registration Number</label>
                    <div class="value">{{ $company->registration_number }}</div>
                </div>

                <div class="info-item">
                    <label><i class="fas fa-globe"></i> Website</label>
                    <div class="value">
                        @if($company->website)
                            <a href="{{ $company->website }}" target="_blank" style="color: #ff9800;">{{ $company->website }}</a>
                        @else
                            Not Provided
                        @endif
                    </div>
                </div>

                <div class="info-item full-width">
                    <label><i class="fas fa-map-marker-alt"></i> Address</label>
                    <div class="value">{{ $company->address ?? 'No address provided' }}</div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="stats-container">
            <div class="stat-card">
                <i class="fas fa-users"></i>
                <div class="stat-value">{{ $mechanics->total() }}</div>
                <div class="stat-label">Total Mechanics</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-check-circle"></i>
                <div class="stat-value">{{ $mechanics->where('approval_status', 'approved')->count() }}</div>
                <div class="stat-label">Approved Mechanics</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-clock"></i>
                <div class="stat-value">{{ $mechanics->where('approval_status', 'pending')->count() }}</div>
                <div class="stat-label">Pending Mechanics</div>
            </div>
        </div>

        <!-- Mechanics List -->
        <h3 class="section-title"><i class="fas fa-users"></i> Associated Mechanics</h3>
        
        <div class="table-container">
            @if($mechanics->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>License Number</th>
                            <th>Experience</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mechanics as $mechanic)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($mechanic->user->name) }}&size=40&background=9c27b0&color=fff" 
                                         alt="{{ $mechanic->user->name }}" 
                                         style="border-radius: 50%; width: 40px; height: 40px;">
                                    <span>{{ $mechanic->user->name }}</span>
                                </div>
                            </td>
                            <td>{{ $mechanic->user->email }}</td>
                            <td>{{ $mechanic->phone_number ?? 'N/A' }}</td>
                            <td>{{ $mechanic->license_number ?? 'N/A' }}</td>
                            <td>{{ $mechanic->years_of_experience ?? 0 }} years</td>
                            <td>
                                <span class="status-badge {{ $mechanic->approval_status }}">
                                    {{ ucfirst($mechanic->approval_status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.mechanics.show', $mechanic->id) }}" class="btn btn-secondary" style="padding: 8px 15px; font-size: 0.85rem;">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <div style="padding: 20px;">
                    {{ $mechanics->links() }}
                </div>
            @else
                <div class="no-data">
                    <i class="fas fa-users-slash"></i>
                    <p>No mechanics associated with this company yet.</p>
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="{{ route('admin.insurance.edit', $company->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Company
            </a>
            <a href="{{ route('admin.insurance') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Insurance
            </a>
        </div>
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
    </div>

    <script>
        function showRejectModal() {
            document.getElementById('rejectModal').style.display = 'flex';
        }
        function hideRejectModal() {
            document.getElementById('rejectModal').style.display = 'none';
        }
    </script>
</body>
</html>
