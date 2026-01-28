<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - AutoCar</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .profile-hero {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url('https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80');
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

        .profile-hero h1 {
            font-size: 3rem;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .profile-section {
            padding: 80px 10%;
            background: #000;
            min-height: calc(100vh - 500px);
        }

        .profile-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: 350px 1fr;
            gap: 30px;
        }

        /* Profile Card */
        .profile-card {
            background: #1a1a1a;
            border-radius: 5px;
            padding: 40px;
            border: 2px solid #333;
            text-align: center;
            height: fit-content;
            position: sticky;
            top: 20px;
        }

        .profile-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin: 0 auto 20px;
            border: 5px solid #f8c300;
            object-fit: cover;
        }

        .profile-name {
            color: #fff;
            font-size: 1.8rem;
            margin: 0 0 10px;
            font-weight: bold;
        }

        .profile-email {
            color: #888;
            font-size: 1rem;
            margin: 0 0 20px;
        }

        .profile-rating {
            background: #222;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .profile-rating i {
            color: #f8c300;
            font-size: 1.5rem;
        }

        .profile-rating .rating-value {
            color: #fff;
            font-size: 2rem;
            font-weight: bold;
            margin: 10px 0 5px;
        }

        .profile-rating .rating-label {
            color: #888;
            font-size: 0.85rem;
        }

        .profile-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-item {
            background: #222;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #333;
        }

        .stat-number {
            color: #f8c300;
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0;
        }

        .stat-label {
            color: #888;
            font-size: 0.75rem;
            margin-top: 5px;
            text-transform: uppercase;
        }

        .profile-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .btn-edit {
            background: #f8c300;
            color: #000;
            border: none;
            padding: 15px;
            border-radius: 3px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-edit:hover {
            background: #fff;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: transparent;
            color: #888;
            border: 2px solid #333;
            padding: 15px;
            border-radius: 3px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-secondary:hover {
            border-color: #f8c300;
            color: #f8c300;
        }

        /* Profile Content */
        .profile-content {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .content-card {
            background: #1a1a1a;
            border-radius: 5px;
            padding: 30px;
            border: 2px solid #333;
        }

        .content-card h2 {
            color: #fff;
            font-size: 1.5rem;
            margin: 0 0 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f8c300;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .content-card h2 i {
            color: #f8c300;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .info-label {
            color: #888;
            font-size: 0.85rem;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-label i {
            color: #f8c300;
            width: 16px;
        }

        .info-value {
            color: #fff;
            font-size: 1.1rem;
            font-weight: bold;
            padding: 12px;
            background: #222;
            border-radius: 3px;
            border: 1px solid #333;
        }

        .info-value.empty {
            color: #666;
            font-style: italic;
            font-weight: normal;
        }

        /* Recent Requests */
        .request-item {
            background: #222;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 15px;
            border-left: 4px solid #333;
            transition: all 0.3s;
        }

        .request-item:hover {
            border-left-color: #f8c300;
            transform: translateX(5px);
        }

        .request-item.pending {
            border-left-color: #ff9800;
        }

        .request-item.in_progress {
            border-left-color: #9c27b0;
        }

        .request-item.completed {
            border-left-color: #4caf50;
        }

        .request-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .request-id {
            color: #f8c300;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .request-status {
            padding: 5px 15px;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: bold;
            text-transform: uppercase;
        }

        .request-status.pending {
            background: #ff9800;
            color: #000;
        }

        .request-status.in_progress {
            background: #9c27b0;
            color: #fff;
        }

        .request-status.completed {
            background: #4caf50;
            color: #fff;
        }

        .request-details {
            color: #ddd;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .request-details i {
            color: #f8c300;
            margin-right: 8px;
            width: 16px;
        }

        .request-date {
            color: #888;
            font-size: 0.85rem;
            margin-top: 10px;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #666;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        /* Edit Mode */
        .edit-form .form-group {
            margin-bottom: 20px;
        }

        .edit-form label {
            color: #fff;
            font-size: 0.9rem;
            margin-bottom: 8px;
            display: block;
            text-transform: uppercase;
        }

        .edit-form input,
        .edit-form select,
        .edit-form textarea {
            width: 100%;
            padding: 12px;
            background: #222;
            border: 2px solid #333;
            color: #fff;
            border-radius: 3px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .edit-form input:focus,
        .edit-form select:focus,
        .edit-form textarea:focus {
            outline: none;
            border-color: #f8c300;
            background: #2a2a2a;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn-save {
            flex: 1;
            background: #4caf50;
            color: #fff;
            border: none;
            padding: 15px;
            border-radius: 3px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
        }

        .btn-save:hover {
            background: #45a049;
            transform: translateY(-2px);
        }

        .btn-cancel {
            flex: 1;
            background: transparent;
            color: #f44336;
            border: 2px solid #f44336;
            padding: 15px;
            border-radius: 3px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
        }

        .btn-cancel:hover {
            background: #f44336;
            color: #fff;
        }

        .alert {
            padding: 15px 20px;
            margin-bottom: 30px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #4caf50;
            color: #fff;
        }

        .alert-error {
            background: #f44336;
            color: #fff;
        }

        @media (max-width: 968px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }

            .profile-card {
                position: relative;
                top: 0;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <x-user-header />

    <!-- Profile Hero -->
    <section class="profile-hero">
        <div class="hero-content">
            <h1><i class="fas fa-user-circle" style="color: #f8c300;"></i> My Profile</h1>
            <p style="font-size: 1.2rem; color: #ddd; margin: 0;">
                Manage your account information and preferences
            </p>
        </div>
    </section>

    <!-- Profile Section -->
    <section class="profile-section">
        <div class="profile-container">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="profile-grid">
                <!-- Profile Card -->
                <div class="profile-card">
                    @php
                        $hasProfilePicture = false;
                        $profilePictureUrl = 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=200&background=random';
                        
                        if($user->driver && $user->driver->profile_picture) {
                            $picturePath = storage_path('app/public/' . $user->driver->profile_picture);
                            if(file_exists($picturePath)) {
                                $hasProfilePicture = true;
                                // Add timestamp to prevent caching
                                $profilePictureUrl = asset('storage/' . $user->driver->profile_picture) . '?v=' . filemtime($picturePath);
                            }
                        }
                    @endphp
                    
                    <img src="{{ $profilePictureUrl }}" alt="Profile" class="profile-avatar" id="profileImage" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=200&background=random'">

                    <h2 class="profile-name">{{ $user->name }}</h2>
                    <p class="profile-email">
                        <i class="fas fa-user-tag"></i> {{ $user->role }}
                    </p>

                    <div class="profile-actions" style="margin-top: 30px;">
                        <button class="btn-edit" onclick="toggleEditMode()">
                            <i class="fas fa-edit"></i> Edit Profile
                        </button>
                        <button class="btn-secondary" onclick="window.location='{{ route('user.request.assistance') }}'">
                            <i class="fas fa-plus-circle"></i> New Request
                        </button>
                    </div>
                </div>

                <!-- Profile Content -->
                <div class="profile-content">
                    <!-- Personal Information -->
                    <div class="content-card" id="personalInfoView">
                        <h2><i class="fas fa-user"></i> Personal Information</h2>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-user"></i> Full Name
                                </div>
                                <div class="info-value">{{ $user->name }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-envelope"></i> Email Address
                                </div>
                                <div class="info-value">{{ $user->email }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-phone"></i> Phone Number
                                </div>
                                <div class="info-value {{ $user->driver && $user->driver->phone_number ? '' : 'empty' }}">
                                    {{ $user->driver->phone_number ?? 'Not provided' }}
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-calendar"></i> Member Since
                                </div>
                                <div class="info-value">{{ $user->created_at->format('M d, Y') }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-map-marker-alt"></i> Address
                                </div>
                                <div class="info-value {{ $user->driver && $user->driver->address ? '' : 'empty' }}">
                                    {{ $user->driver->address ?? 'Not provided' }}
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-id-card"></i> Role
                                </div>
                                <div class="info-value" style="text-transform: capitalize;">{{ $user->role }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Form (Hidden by default) -->
                    <div class="content-card" id="personalInfoEdit" style="display: none;">
                        <h2><i class="fas fa-edit"></i> Edit Personal Information</h2>
                        <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="edit-form">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group">
                                <label for="profile_picture">Profile Picture</label>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    @if($hasProfilePicture)
                                        <img src="{{ asset('storage/' . $user->driver->profile_picture) }}" alt="Current" id="previewImage" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #f8c300;" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=80&background=random'">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=80&background=random" alt="Current" id="previewImage" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #f8c300;">
                                    @endif
                                    <div style="flex: 1;">
                                        <input type="file" id="profile_picture" name="profile_picture" accept="image/*" onchange="previewProfilePicture(event)" style="margin-bottom: 5px;">
                                        <small style="color: #888; display: block;">Max 2MB. Formats: JPG, PNG, GIF</small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->driver->phone_number ?? '') }}" placeholder="+60123456789">
                            </div>

                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea id="address" name="address" rows="3" placeholder="Enter your full address">{{ old('address', $user->driver->address ?? '') }}</textarea>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn-save">
                                    <i class="fas fa-save"></i> Save Changes
                                </button>
                                <button type="button" class="btn-cancel" onclick="toggleEditMode()">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Change Password -->
                    <div class="content-card">
                        <h2><i class="fas fa-lock"></i> Change Password</h2>
                        <form action="{{ route('user.password.update') }}" method="POST" class="edit-form">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group">
                                <label for="current_password">Current Password</label>
                                <input type="password" id="current_password" name="current_password" required>
                            </div>

                            <div class="form-group">
                                <label for="password">New Password</label>
                                <input type="password" id="password" name="password" required>
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">Confirm New Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" required>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn-save">
                                    <i class="fas fa-key"></i> Update Password
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
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



        // Toggle Edit Mode
        function toggleEditMode() {
            const viewMode = document.getElementById('personalInfoView');
            const editMode = document.getElementById('personalInfoEdit');
            
            if (viewMode.style.display === 'none') {
                viewMode.style.display = 'block';
                editMode.style.display = 'none';
            } else {
                viewMode.style.display = 'none';
                editMode.style.display = 'block';
            }
        }

        // Preview profile picture before upload
        function previewProfilePicture(event) {
            const file = event.target.files[0];
            if (file) {
                // Validate file size (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB');
                    event.target.value = '';
                    return;
                }

                // Validate file type
                const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    alert('Please upload a valid image file (JPG, PNG, or GIF)');
                    event.target.value = '';
                    return;
                }

                // Preview the image
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImage').src = e.target.result;
                    document.getElementById('profileImage').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>
