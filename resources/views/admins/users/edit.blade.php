<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - AutoCar Admin</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #000; color: #fff; margin: 0; font-family: Arial, sans-serif; }
        .container { max-width: 900px; margin: 0 auto; padding: 30px 20px; }
        .page-header { margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #f8c300; }
        .page-header h1 { margin: 0 0 10px; font-size: 2.5rem; }
        .breadcrumb { color: #888; font-size: 0.9rem; }
        .breadcrumb a { color: #f8c300; text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }
        
        .info-box { background: #1a2a3a; padding: 15px 20px; border-left: 4px solid #2196F3; margin-bottom: 20px; border-radius: 3px; }
        .info-box p { margin: 0; color: #ddd; font-size: 0.9rem; }
        
        .form-card { background: #1a1a1a; padding: 40px; border-radius: 5px; border: 2px solid #333; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; }
        .form-group { margin-bottom: 25px; }
        .form-group label { display: block; color: #f8c300; font-weight: bold; margin-bottom: 10px; font-size: 0.9rem; }
        .form-group input, .form-group select { width: 100%; padding: 12px 15px; background: #222; border: 2px solid #333; color: #fff; border-radius: 3px; font-size: 1rem; transition: all 0.3s; }
        .form-group input:focus, .form-group select:focus { outline: none; border-color: #f8c300; background: #2a2a2a; }
        .form-group input:disabled { opacity: 0.5; cursor: not-allowed; background: #1a1a1a; }
        .required { color: #f44336; }
        .form-actions { display: flex; gap: 15px; margin-top: 30px; }
        .btn { padding: 12px 25px; border: none; border-radius: 3px; cursor: pointer; font-weight: bold; transition: all 0.3s; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-size: 1rem; }
        .btn-primary { background: #f8c300; color: #000; }
        .btn-primary:hover { background: #fff; }
        .btn-secondary { background: #333; border: 2px solid #555; color: #fff; }
        .btn-secondary:hover { border-color: #f8c300; }
        .alert { padding: 15px 20px; margin-bottom: 20px; border-radius: 5px; }
        .alert-error { background: #f44336; color: #fff; }
        .alert ul { margin: 10px 0 0 20px; }
        .form-help { color: #888; font-size: 0.85rem; margin-top: 5px; font-style: italic; }
    </style>
</head>
<body>
    @include('components.admin-header')

    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-user-edit" style="color: #f8c300;"></i> Edit User</h1>
            <p class="breadcrumb">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a> / 
                <a href="{{ route('admin.users') }}">Users</a> / 
                Edit: {{ $user->name }}
            </p>
        </div>

        @if($errors->any())
            <div class="alert alert-error">
                <strong><i class="fas fa-exclamation-triangle"></i> Please fix the following errors:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="info-box">
            <p><i class="fas fa-info-circle"></i> Leave password fields empty if you don't want to change the password.</p>
        </div>

        <div class="form-card">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name"><i class="fas fa-user"></i> Full Name <span class="required">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="email"><i class="fas fa-envelope"></i> Email Address <span class="required">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="password"><i class="fas fa-lock"></i> New Password</label>
                        <input type="password" id="password" name="password">
                        <p class="form-help">Leave blank to keep current password</p>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation"><i class="fas fa-lock"></i> Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation">
                        <p class="form-help">Must match new password if changing</p>
                    </div>

                    <div class="form-group">
                        <label for="phone"><i class="fas fa-phone"></i> Phone Number</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->driver->phone_number ?? '') }}" placeholder="+60123456789">
                    </div>

                    <div class="form-group">
                        <label for="role"><i class="fas fa-user-tag"></i> Role <span class="required">*</span></label>
                        <select id="role" name="role" required {{ $user->id == auth()->id() ? 'disabled' : '' }}>
                            <option value="">-- Select Role --</option>
                            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                            <option value="mechanic" {{ old('role', $user->role) == 'mechanic' ? 'selected' : '' }}>Mechanic</option>
                            <option value="insurance" {{ old('role', $user->role) == 'insurance' ? 'selected' : '' }}>Insurance</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @if($user->id == auth()->id())
                            <input type="hidden" name="role" value="{{ $user->role }}">
                            <p class="form-help">You cannot change your own role</p>
                        @endif
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update User
                    </button>
                    <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Users
                    </a>
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
