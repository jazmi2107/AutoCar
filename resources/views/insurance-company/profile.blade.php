<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Profile - AutoCar</title>
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
        
        @media (max-width: 968px) {
            .profile-grid { grid-template-columns: 1fr; }
            .profile-card { position: relative; top: 0; }
            .info-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    @include('components.insurance-header')

    <div class="container">
        <div class="page-header">
            <div>
                <div class="breadcrumb">
                    <a href="{{ route('insurance_company.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a> / Company Profile
                </div>
                <h1>Company Profile</h1>
            </div>
        </div>

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
                    $hasProfilePicture = $insurance && $insurance->profile_picture && \Storage::disk('public')->exists($insurance->profile_picture);
                    $profilePictureUrl = $hasProfilePicture 
                        ? asset('storage/' . $insurance->profile_picture) . '?v=' . time()
                        : 'https://ui-avatars.com/api/?name=' . urlencode($insurance->company_name) . '&size=150&background=f8c300&color=000&bold=true';
                @endphp
                <img src="{{ $profilePictureUrl }}" 
                     alt="Profile" class="profile-avatar" id="profileImage"
                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($insurance->company_name) }}&size=150&background=f8c300&color=000&bold=true'">
                
                <h2 class="profile-name">{{ $insurance->company_name }}</h2>
                <span class="profile-role">
                    <i class="fas fa-building"></i> Insurance Company
                </span>
                

                <div class="profile-actions">
                    <button class="btn-edit" onclick="toggleEditMode()">
                        <i class="fas fa-edit"></i> Edit Profile
                    </button>
                    <a href="{{ route('insurance_company.dashboard') }}" class="btn-secondary">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </div>
            </div>

            <!-- Profile Content -->
            <div class="profile-content">
                <!-- Company Information View -->
                <div class="content-card" id="personalInfoView">
                    <h2><i class="fas fa-building"></i> Company Information</h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-building"></i> Company Name
                            </div>
                            <div class="info-value">{{ $insurance->company_name }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-id-card"></i> Registration No.
                            </div>
                            <div class="info-value">{{ $insurance->registration_number }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-phone"></i> Phone Number
                            </div>
                            <div class="info-value {{ !$insurance->phone_number ? 'empty' : '' }}">{{ $insurance->phone_number ?? 'Not provided' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-envelope"></i> Email Address
                            </div>
                            <div class="info-value">{{ $user->email }}</div>
                        </div>
                        <div class="info-item" style="grid-column: 1 / -1;">
                            <div class="info-label">
                                <i class="fas fa-map-marker-alt"></i> Address
                            </div>
                            <div class="info-value {{ !$insurance->address ? 'empty' : '' }}">{{ $insurance->address ?? 'Not provided' }}</div>
                        </div>
                        <div class="info-item" style="grid-column: 1 / -1;">
                            <div class="info-label">
                                <i class="fas fa-globe"></i> Website
                            </div>
                            <div class="info-value {{ !$insurance->website ? 'empty' : '' }}">{{ $insurance->website ?? 'Not provided' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-calendar-alt"></i> Member Since
                            </div>
                            <div class="info-value">{{ $insurance->created_at->format('M d, Y') }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-clock"></i> Last Updated
                            </div>
                            <div class="info-value">{{ $insurance->updated_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>

                <!-- Edit Form (Hidden by default) -->
                <div class="content-card" id="personalInfoEdit" style="display: none;">
                    <h2><i class="fas fa-edit"></i> Edit Company Information</h2>
                    <form action="{{ route('insurance_company.profile.update') }}" method="POST" enctype="multipart/form-data" class="edit-form">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="profile_picture">Profile Picture</label>
                            <div style="display: flex; align-items: center; gap: 20px;">
                                @if($hasProfilePicture)
                                    <img src="{{ asset('storage/' . $insurance->profile_picture) }}" alt="Current" id="previewImage" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #f8c300;" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($insurance->company_name) }}&size=80&background=f8c300&color=000&bold=true'">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($insurance->company_name) }}&size=80&background=f8c300&color=000&bold=true" alt="Current" id="previewImage" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #f8c300;">
                                @endif
                                <div style="flex: 1;">
                                    <input type="file" id="profile_picture" name="profile_picture" accept="image/*" onchange="previewProfilePicture(event)" style="margin-bottom: 5px;">
                                    <small style="color: #888; display: block;">Max 2MB. Formats: JPG, PNG, GIF</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="company_name">Company Name *</label>
                            <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $insurance->company_name) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="registration_number">Registration Number *</label>
                            <input type="text" id="registration_number" name="registration_number" value="{{ old('registration_number', $insurance->registration_number) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="phone_number">Phone Number</label>
                            <input type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number', $insurance->phone_number) }}">
                        </div>

                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea id="address" name="address" rows="3">{{ old('address', $insurance->address) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="website">Website</label>
                            <input type="url" id="website" name="website" value="{{ old('website', $insurance->website) }}">
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
                    <form action="{{ route('insurance_company.password.update') }}" method="POST" class="edit-form">
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

    <script>
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
    </script>
</body>
</html>