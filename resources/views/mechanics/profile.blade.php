<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - AutoCar Mechanic</title>
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
        
        .profile-grid { display: grid; grid-template-columns: 350px 1fr; gap: 30px; margin-bottom: 30px; }
        
        /* Profile Card */
        .profile-card { background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%); padding: 40px; border-radius: 15px; border: 2px solid #333; text-align: center; height: fit-content; position: sticky; top: 20px; box-shadow: 0 4px 15px rgba(248, 195, 0, 0.1); }
        .profile-avatar { width: 150px; height: 150px; border-radius: 50%; margin: 0 auto 20px; border: 5px solid #f8c300; object-fit: cover; }
        .profile-name { color: #fff; font-size: 1.8rem; margin: 0 0 10px; font-weight: bold; }
        .profile-role { display: inline-block; background: rgba(248, 195, 0, 0.2); color: #f8c300; padding: 8px 20px; border-radius: 20px; font-size: 0.85rem; font-weight: bold; text-transform: uppercase; margin-bottom: 20px; border: 1px solid #f8c300; }
        .profile-email { color: #888; font-size: 1rem; margin: 10px 0 30px; }
        
        .profile-actions { display: flex; flex-direction: column; gap: 10px; margin-top: 30px; }
        .btn-edit { background: #f8c300; color: #000; border: none; padding: 15px; border-radius: 5px; font-weight: bold; cursor: pointer; transition: all 0.3s; text-transform: uppercase; display: flex; align-items: center; justify-content: center; gap: 10px; }
        .btn-edit:hover { background: #fff; transform: translateY(-2px); }
        .btn-secondary { background: #333; color: #fff; border: 2px solid #555; padding: 15px; border-radius: 5px; font-weight: bold; cursor: pointer; transition: all 0.3s; text-transform: uppercase; display: flex; align-items: center; justify-content: center; gap: 10px; text-decoration: none; }
        .btn-secondary:hover { background: #444; border-color: #666; }
        
        /* Content Cards */
        .profile-content { display: flex; flex-direction: column; gap: 30px; }
        .content-card { background: #1a1a1a; border-radius: 8px; padding: 30px; border: 2px solid #333; }
        .content-card h2 { color: #fff; font-size: 1.5rem; margin: 0 0 20px; padding-bottom: 15px; border-bottom: 2px solid #f8c300; display: flex; align-items: center; gap: 10px; }
        .content-card h2 i { color: #f8c300; }
        
        .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 25px; }
        .info-item { display: flex; flex-direction: column; gap: 8px; }
        .info-label { color: #888; font-size: 0.85rem; text-transform: uppercase; display: flex; align-items: center; gap: 8px; letter-spacing: 0.5px; }
        .info-label i { color: #f8c300; width: 16px; }
        .info-value { color: #fff; font-size: 1.1rem; font-weight: bold; padding: 12px; background: #222; border-radius: 5px; border: 1px solid #333; }
        .info-value.empty { color: #666; font-style: italic; font-weight: normal; }
        
        /* Edit Form */
        .edit-form .form-group { margin-bottom: 20px; }
        .edit-form label { color: #f8c300; font-size: 0.9rem; margin-bottom: 8px; display: block; text-transform: uppercase; font-weight: bold; }
        .edit-form input, .edit-form select, .edit-form textarea { width: 100%; padding: 12px 15px; background: #222; border: 2px solid #333; color: #fff; border-radius: 5px; font-size: 1rem; transition: all 0.3s; box-sizing: border-box; }
        .edit-form input:focus, .edit-form select:focus, .edit-form textarea:focus { outline: none; border-color: #f8c300; background: #2a2a2a; }
        
        .form-actions { display: flex; gap: 15px; margin-top: 30px; }
        .btn-save { flex: 1; background: #4caf50; color: #fff; border: none; padding: 15px; border-radius: 5px; font-weight: bold; cursor: pointer; transition: all 0.3s; text-transform: uppercase; }
        .btn-save:hover { background: #45a049; transform: translateY(-2px); }
        .btn-cancel { flex: 1; background: transparent; color: #f44336; border: 2px solid #f44336; padding: 15px; border-radius: 5px; font-weight: bold; cursor: pointer; transition: all 0.3s; text-transform: uppercase; }
        .btn-cancel:hover { background: #f44336; color: #fff; }
        
        .alert { padding: 15px 20px; margin-bottom: 30px; border-radius: 5px; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #4caf50; color: #fff; }
        .alert-error { background: #f44336; color: #fff; }
        
        .location-section { background: #222; padding: 20px; border-radius: 5px; border-left: 3px solid #f8c300; margin-bottom: 20px; }
        .location-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .location-header h3 { color: #f8c300; margin: 0; font-size: 1rem; text-transform: uppercase; }
        .btn-location { background: #2196f3; color: #fff; border: none; padding: 10px 20px; border-radius: 5px; font-weight: bold; cursor: pointer; transition: all 0.3s; display: inline-flex; align-items: center; gap: 8px; font-size: 0.9rem; }
        .btn-location:hover { background: #1976d2; transform: translateY(-2px); }
        .btn-location:disabled { background: #555; cursor: not-allowed; opacity: 0.6; }
        .location-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .location-status { color: #888; font-size: 0.9rem; margin-top: 10px; display: flex; align-items: center; gap: 8px; }
        .location-status.loading { color: #2196f3; }
        .location-status.success { color: #4caf50; }
        .location-status.error { color: #f44336; }

        /* Notification Styles */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #1a1a1a;
            border-left: 4px solid #f44336;
            padding: 20px 25px;
            border-radius: 5px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.5);
            z-index: 2000;
            display: none;
            align-items: center;
            gap: 15px;
            min-width: 300px;
            animation: slideInRight 0.3s ease;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .notification.show {
            display: flex;
        }

        .notification.error {
            border-left-color: #f44336;
        }

        .notification.warning {
            border-left-color: #ff9800;
        }

        .notification-icon {
            font-size: 1.5rem;
        }

        .notification.error .notification-icon {
            color: #f44336;
        }

        .notification.warning .notification-icon {
            color: #ff9800;
        }

        .notification-message {
            flex: 1;
            color: #fff;
        }

        .notification-close {
            background: none;
            border: none;
            color: #888;
            cursor: pointer;
            font-size: 1.2rem;
            padding: 0;
            transition: color 0.3s;
        }

        .notification-close:hover {
            color: #fff;
        }
        
        footer { background: #111; border-top: 2px solid #333; margin-top: 50px; padding: 20px 0; }
        .footer-flex { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; }
        .copyright { color: #888; font-size: 0.9rem; }
        .footer-phone { color: #f8c300; font-weight: bold; }
        .footer-phone span { color: #fff; }
        
        @media (max-width: 968px) {
            .profile-grid { grid-template-columns: 1fr; }
            .profile-card { position: relative; top: 0; }
            .info-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    @include('components.mechanic-header')

    <div class="container">

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
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
                    $hasProfilePicture = $mechanic && $mechanic->profile_picture && \Storage::disk('public')->exists($mechanic->profile_picture);
                    $profilePictureUrl = $hasProfilePicture 
                        ? asset('storage/' . $mechanic->profile_picture) . '?v=' . time()
                        : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=150&background=f8c300&color=000&bold=true';
                @endphp
                <img src="{{ $profilePictureUrl }}" 
                     alt="Profile" class="profile-avatar" id="profileImage"
                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=150&background=f8c300&color=000&bold=true'">
                
                <h2 class="profile-name">{{ $user->name }}</h2>
                <span class="profile-role">
                    <i class="fas fa-wrench"></i> {{ ucfirst($user->role) }}
                </span>

                <div class="profile-actions">
                    <button class="btn-edit" onclick="toggleEditMode()">
                        <i class="fas fa-edit"></i> Edit Profile
                    </button>
                    <a href="{{ route('mechanic.dashboard') }}" class="btn-secondary">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </div>
            </div>

            <!-- Profile Content -->
            <div class="profile-content">
                <!-- Personal Information View -->
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
                            <div class="info-value {{ !($mechanic && $mechanic->phone_number) ? 'empty' : '' }}">{{ $mechanic->phone_number ?? 'Not provided' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-map-marker-alt"></i> Address
                            </div>
                            <div class="info-value {{ !($mechanic && ($mechanic->address || ($mechanic->latitude && $mechanic->longitude))) ? 'empty' : '' }}">
                                @if($mechanic && $mechanic->address)
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <i class="fas fa-map-pin" style="color: #4caf50;"></i>
                                        <span>{{ $mechanic->address }}</span>
                                    </div>
                                @elseif($mechanic && $mechanic->latitude && $mechanic->longitude)
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <i class="fas fa-map-pin" style="color: #4caf50;"></i>
                                        <span id="locationDisplay">Loading address...</span>
                                    </div>
                                @else
                                    Not set
                                @endif
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-id-card"></i> License Number
                            </div>
                            <div class="info-value {{ !($mechanic && $mechanic->license_number) ? 'empty' : '' }}">{{ $mechanic->license_number ?? 'Not provided' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-star"></i> Rating
                            </div>
                            <div class="info-value">{{ $mechanic->rating ?? 'N/A' }} ⭐</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-calendar-alt"></i> Member Since
                            </div>
                            <div class="info-value">{{ $user->created_at->format('M d, Y') }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-clock"></i> Last Updated
                            </div>
                            <div class="info-value">{{ $user->updated_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>

                <!-- Edit Form (Hidden by default) -->
                <div class="content-card" id="personalInfoEdit" style="display: none;">
                    <h2><i class="fas fa-edit"></i> Edit Personal Information</h2>
                    <form action="{{ route('mechanic.profile.update') }}" method="POST" enctype="multipart/form-data" class="edit-form">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="profile_picture">Profile Picture</label>
                            <div style="display: flex; align-items: center; gap: 20px;">
                                @if($hasProfilePicture)
                                    <img src="{{ asset('storage/' . $mechanic->profile_picture) }}" alt="Current" id="previewImage" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #f8c300;" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=80&background=f8c300&color=000&bold=true'">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=80&background=f8c300&color=000&bold=true" alt="Current" id="previewImage" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #f8c300;">
                                @endif
                                <div style="flex: 1;">
                                    <input type="file" id="profile_picture" name="profile_picture" accept="image/*" onchange="previewProfilePicture(event)" style="margin-bottom: 5px;">
                                    <small style="color: #888; display: block;">Max 2MB. Formats: JPG, PNG, GIF</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="phone_number">Phone Number</label>
                            <input type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number', $mechanic->phone_number ?? '') }}">
                        </div>

                        <div class="form-group">
                            <label for="years_of_experience">Years of Experience *</label>
                            <input type="number" id="years_of_experience" name="years_of_experience" value="{{ old('years_of_experience', $mechanic->years_of_experience ?? '') }}" min="0" required>
                        </div>

                        <div class="location-section">
                            <div class="location-header">
                                <h3><i class="fas fa-map-pin"></i> GPS Location</h3>
                                <button type="button" class="btn-location" id="getLocationBtn" onclick="getCurrentLocation()">
                                    <i class="fas fa-location-crosshairs"></i>
                                    Get Current Location
                                </button>
                            </div>
                            <div class="form-group">
                                <label for="detected_address"><i class="fas fa-map-marker-alt"></i> Address *</label>
                                <input type="text" id="detected_address" name="address" value="{{ old('address', $mechanic->address ?? '') }}" placeholder="Enter your address or use Get Current Location" style="background: #2a2a2a; color: #fff; font-weight: 600;" required>
                            </div>
                            <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $mechanic->latitude ?? '') }}">
                            <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $mechanic->longitude ?? '') }}">
                            <div class="location-status" id="locationStatus"></div>
                            <small style="color: #888; display: block; margin-top: 10px;">
                                <i class="fas fa-info-circle"></i> Your location helps customers find you and calculate accurate distances. Click "Get Current Location" to automatically detect your position.
                            </small>
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
                    <form action="{{ route('mechanic.password.update') }}" method="POST" class="edit-form">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" id="current_password" name="current_password" required>
                        </div>

                        <div class="form-group">
                            <label for="password">New Password</label>
                            <input type="password" id="password" name="password" required minlength="8">
                            <small style="color: #888; display: block; margin-top: 5px;">Minimum 8 characters</small>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirm New Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8">
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

    <!-- Footer -->
    <footer>
        <div class="footer-bottom">
            <div class="container footer-flex">
                <div class="copyright">
                    &copy; <span id="year"></span> Auto Car Repair. All Rights Reserved.
                </div>
                <div class="footer-phone">
                    CALL TODAY: <span>+6012 284 0561</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Notification -->
    <div id="notification" class="notification">
        <div class="notification-icon">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <div class="notification-message" id="notificationMessage"></div>
        <button class="notification-close" onclick="hideNotification()">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <script>
        document.getElementById('year').textContent = new Date().getFullYear();

        // Notification functions
        function showNotification(message, type = 'error') {
            const notification = document.getElementById('notification');
            const messageElement = document.getElementById('notificationMessage');
            const icon = notification.querySelector('.notification-icon i');
            
            notification.className = `notification ${type} show`;
            messageElement.textContent = message;
            
            // Update icon based on type
            if (type === 'error') {
                icon.className = 'fas fa-exclamation-circle';
            } else if (type === 'warning') {
                icon.className = 'fas fa-exclamation-triangle';
            }
            
            // Auto hide after 5 seconds
            setTimeout(hideNotification, 5000);
        }

        function hideNotification() {
            const notification = document.getElementById('notification');
            notification.classList.remove('show');
        }

        // Password form validation
        const passwordForm = document.querySelector('form[action*="password.update"]');
        if (passwordForm) {
            passwordForm.addEventListener('submit', function(e) {
                const currentPassword = document.getElementById('current_password').value;
                const newPassword = document.getElementById('password').value;
                const confirmPassword = document.getElementById('password_confirmation').value;

                // Check if new password is same as current password
                if (currentPassword === newPassword) {
                    e.preventDefault();
                    showNotification('New password cannot be the same as current password!', 'warning');
                    return false;
                }

                // Check if passwords match
                if (newPassword !== confirmPassword) {
                    e.preventDefault();
                    showNotification('New password and confirmation do not match!', 'error');
                    return false;
                }
            });
        }

        // Toggle Edit Mode
        function toggleEditMode() {
            const viewSection = document.getElementById('personalInfoView');
            const editSection = document.getElementById('personalInfoEdit');
            
            if (viewSection.style.display === 'none') {
                viewSection.style.display = 'block';
                editSection.style.display = 'none';
            } else {
                viewSection.style.display = 'none';
                editSection.style.display = 'block';
            }
        }

        // Preview profile picture before upload
        function previewProfilePicture(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImage').src = e.target.result;
                    // Also update the main profile image
                    const profileImage = document.getElementById('profileImage');
                    if (profileImage) {
                        profileImage.src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        }

        // Get current location using Geolocation API
        function getCurrentLocation() {
            const btn = document.getElementById('getLocationBtn');
            const status = document.getElementById('locationStatus');
            const latInput = document.getElementById('latitude');
            const lonInput = document.getElementById('longitude');
            const addressInput = document.getElementById('detected_address');

            // Check if geolocation is supported
            if (!navigator.geolocation) {
                status.className = 'location-status error';
                status.innerHTML = '<i class="fas fa-exclamation-circle"></i> Geolocation is not supported by your browser';
                return;
            }

            // Show loading state
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Getting location...';
            status.className = 'location-status loading';
            status.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Detecting your location...';
            addressInput.value = 'Detecting your location...';

            // Get current position
            navigator.geolocation.getCurrentPosition(
                // Success callback
                function(position) {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;

                    // Update hidden input fields
                    latInput.value = lat.toFixed(6);
                    lonInput.value = lon.toFixed(6);

                    // Get address from coordinates using Google Maps Geocoder
                    status.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Getting address...';
                    addressInput.value = 'Getting address from coordinates...';

                    // Wait for Google Maps API to load
                    if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
                        console.log('Waiting for Google Maps API to load...');
                        const checkGoogleMaps = setInterval(() => {
                            if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
                                clearInterval(checkGoogleMaps);
                                geocodeLatLng(lat, lon, addressInput, btn, status);
                            }
                        }, 100);
                    } else {
                        geocodeLatLng(lat, lon, addressInput, btn, status);
                    }
                },
                // Error callback
                function(error) {
                    let errorMessage = '';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage = 'Location permission denied. Please allow location access in your browser settings.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage = 'Location information is unavailable. Please try again.';
                            break;
                        case error.TIMEOUT:
                            errorMessage = 'Location request timed out. Please try again.';
                            break;
                        default:
                            errorMessage = 'An unknown error occurred while getting location.';
                            break;
                    }

                    // Update button and status
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-location-crosshairs"></i> Get Current Location';
                    status.className = 'location-status error';
                    status.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + errorMessage;
                    addressInput.value = '';
                    addressInput.placeholder = 'Location detection failed. Please enter address manually.';
                },
                // Options
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        }

        // Geocode latitude/longitude to address using Google Maps API
        function geocodeLatLng(lat, lon, addressInput, btn, status) {
            const geocoder = new google.maps.Geocoder();
            const latlng = { lat: parseFloat(lat), lng: parseFloat(lon) };

            geocoder.geocode({ location: latlng }, function(results, statusCode) {
                console.log('Geocoding status:', statusCode);
                console.log('Geocoding results:', results);

                if (statusCode === 'OK' && results && results.length > 0) {
                    const address = results[0].formatted_address;
                    console.log('Detected address:', address);
                    addressInput.value = address;
                    
                    // Update button and status
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-location-crosshairs"></i> Get Current Location';
                    status.className = 'location-status success';
                    status.innerHTML = '<i class="fas fa-check-circle"></i> Location detected! ' + address;
                } else {
                    console.error('Geocoding failed:', statusCode);
                    // Fallback to coordinates if geocoding fails
                    addressInput.value = `Coordinates: ${lat.toFixed(6)}, ${lon.toFixed(6)}`;
                    
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-location-crosshairs"></i> Get Current Location';
                    status.className = 'location-status error';
                    status.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Address lookup failed: ' + statusCode;
                }
            });
        }

        // Load address from existing coordinates on page load
        @if($mechanic && $mechanic->latitude && $mechanic->longitude)
        window.addEventListener('DOMContentLoaded', function() {
            const lat = {{ $mechanic->latitude }};
            const lon = {{ $mechanic->longitude }};
            const locationDisplay = document.getElementById('locationDisplay');

            if (locationDisplay) {
                console.log('Loading address for coordinates:', lat, lon);
                
                // Wait for Google Maps API to load
                const loadAddress = () => {
                    if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
                        const geocoder = new google.maps.Geocoder();
                        const latlng = { lat: lat, lng: lon };

                        geocoder.geocode({ location: latlng }, function(results, status) {
                            console.log('Page load geocoding status:', status);
                            console.log('Page load geocoding results:', results);
                            
                            if (status === 'OK' && results && results.length > 0) {
                                locationDisplay.textContent = results[0].formatted_address;
                            } else {
                                console.error('Page load geocoding failed:', status);
                                locationDisplay.textContent = `${lat.toFixed(6)}, ${lon.toFixed(6)}`;
                            }
                        });
                    } else {
                        console.log('Google Maps not loaded yet, retrying...');
                        setTimeout(loadAddress, 100);
                    }
                };
                
                loadAddress();
            }
        });
        @endif
    </script>

    <!-- Google Maps JavaScript API for Geocoding -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCziCeDmXEcKcayGX8CkuDWQ_OBctigFW8" async defer></script>
</body>
</html>
