<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mechanic - AutoCar Admin</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #000; color: #fff; margin: 0; font-family: Arial, sans-serif; }
        .container { max-width: 1200px; margin: 0 auto; padding: 30px 20px; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #f8c300; }
        .page-header h1 { margin: 0; font-size: 2rem; }
        .breadcrumb { color: #888; font-size: 0.9rem; margin-bottom: 5px; }
        .breadcrumb a { color: #f8c300; text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }
        
        .form-card { background: #1a1a1a; padding: 30px; border-radius: 8px; border: 2px solid #333; }
        .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
        .form-group { margin-bottom: 20px; }
        .form-group.full-width { grid-column: 1 / -1; }
        .form-group label { display: block; margin-bottom: 8px; color: #f8c300; font-weight: bold; font-size: 0.9rem; text-transform: uppercase; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px 15px; background: #222; border: 2px solid #333; color: #fff; border-radius: 5px; font-size: 0.95rem; box-sizing: border-box; }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: #f8c300; }
        .form-group textarea { resize: vertical; min-height: 100px; }
        .form-group select option { background: #222; color: #fff; }
        
        .info-box { background: #2a2a2a; padding: 15px 20px; border-radius: 5px; border-left: 4px solid #2196F3; margin-bottom: 25px; }
        .info-box i { color: #2196F3; margin-right: 10px; }
        .info-box p { margin: 0; color: #ccc; font-size: 0.9rem; line-height: 1.5; }
        
        .alert { padding: 15px 20px; margin-bottom: 20px; border-radius: 5px; display: flex; align-items: center; gap: 10px; }
        .alert-danger { background: #f44336; color: #fff; }
        .alert ul { margin: 0; padding-left: 20px; }
        
        .btn { display: inline-block; padding: 12px 30px; border-radius: 5px; text-decoration: none; font-weight: bold; transition: all 0.3s; cursor: pointer; border: none; text-align: center; font-size: 0.95rem; }
        .btn-primary { background: #f8c300; color: #000; }
        .btn-primary:hover { background: #fff; }
        .btn-secondary { background: #333; color: #fff; border: 2px solid #555; }
        .btn-secondary:hover { background: #444; border-color: #666; }
        
        .form-actions { display: flex; gap: 15px; justify-content: flex-end; margin-top: 30px; padding-top: 20px; border-top: 2px solid #333; }
    </style>
</head>
<body>
    @include('components.admin-header')

    <div class="container">
        <div class="page-header">
            <div>
                <div class="breadcrumb">
                    <a href="{{ route('admin.mechanics') }}"><i class="fas fa-user-cog"></i> Mechanics</a> / Edit Mechanic
                </div>
                <h1>Edit Mechanic: {{ $mechanic->user->name }}</h1>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    <strong>There were some errors with your submission:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="info-box">
            <i class="fas fa-info-circle"></i>
            <p>Update mechanic details. Changes to approval status will affect the mechanic's access to the system.</p>
        </div>

        <div class="form-card">
            <form action="{{ route('admin.mechanics.update', $mechanic->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name"><i class="fas fa-user"></i> Full Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $mechanic->user->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="email"><i class="fas fa-envelope"></i> Email Address *</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $mechanic->user->email) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="phone_number"><i class="fas fa-phone"></i> Phone Number</label>
                        <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $mechanic->phone_number) }}">
                    </div>

                    <div class="form-group">
                        <label for="license_number"><i class="fas fa-id-card"></i> License Number</label>
                        <input type="text" id="license_number" name="license_number" value="{{ old('license_number', $mechanic->license_number) }}">
                    </div>

                    <div class="form-group">
                        <label for="years_of_experience"><i class="fas fa-briefcase"></i> Years of Experience</label>
                        <input type="number" id="years_of_experience" name="years_of_experience" value="{{ old('years_of_experience', $mechanic->years_of_experience) }}" min="0">
                    </div>

                    <div class="form-group">
                        <label for="insurance_company_id"><i class="fas fa-shield-alt"></i> Insurance Company</label>
                        <select id="insurance_company_id" name="insurance_company_id">
                            <option value="">None</option>
                            @foreach($insuranceCompanies as $company)
                                <option value="{{ $company->id }}" {{ old('insurance_company_id', $mechanic->insurance_company_id) == $company->id ? 'selected' : '' }}>
                                    {{ $company->company_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="availability_status"><i class="fas fa-clock"></i> Availability Status *</label>
                        <select id="availability_status" name="availability_status" required>
                            <option value="available" {{ old('availability_status', $mechanic->availability_status) == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="busy" {{ old('availability_status', $mechanic->availability_status) == 'busy' ? 'selected' : '' }}>Busy</option>
                            <option value="offline" {{ old('availability_status', $mechanic->availability_status) == 'offline' ? 'selected' : '' }}>Offline</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="approval_status"><i class="fas fa-check-circle"></i> Approval Status *</label>
                        <select id="approval_status" name="approval_status" required>
                            <option value="pending" {{ old('approval_status', $mechanic->approval_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ old('approval_status', $mechanic->approval_status) == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ old('approval_status', $mechanic->approval_status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label for="address"><i class="fas fa-map-marker-alt"></i> Address</label>
                        <textarea id="address" name="address" rows="3">{{ old('address', $mechanic->address) }}</textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.mechanics') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Mechanic
                    </button>
                </div>
            </form>
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
