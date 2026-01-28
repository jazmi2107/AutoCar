@php
    // Fresh load of user with driver or mechanic relationship
    $user = $user ?? Auth::user();
    $size = $size ?? 40;
    $hasProfilePicture = false;
    $profilePicturePath = null;
    $cacheBuster = '';
    
    // Check if it's a Firebase user (no local relationships)
    if ($user instanceof \App\Models\FirebaseUser) {
        // Firebase user handling
        if (!empty($user->photoUrl)) {
            $hasProfilePicture = true;
            $profilePicturePath = $user->photoUrl;
        } elseif (!empty($user->profile_image)) {
            $hasProfilePicture = true;
            $profilePicturePath = $user->profile_image;
        }
    } else {
        // Traditional MySQL User Handling
        // Check for driver profile picture
        if($user && $user->driver && $user->driver->profile_picture) {
            $picturePath = storage_path('app/public/' . $user->driver->profile_picture);
            if(file_exists($picturePath)) {
                $hasProfilePicture = true;
                $profilePicturePath = asset('storage/' . $user->driver->profile_picture);
                $cacheBuster = '?v=' . $user->driver->updated_at->timestamp;
            }
        }
        // Check for mechanic profile picture
        elseif($user && $user->mechanic && $user->mechanic->profile_picture) {
            $picturePath = storage_path('app/public/' . $user->mechanic->profile_picture);
            if(file_exists($picturePath)) {
                $hasProfilePicture = true;
                $profilePicturePath = asset('storage/' . $user->mechanic->profile_picture);
                $cacheBuster = '?v=' . $user->mechanic->updated_at->timestamp;
            }
        }
    }
@endphp

@if($hasProfilePicture)
    <img src="{{ Str::startsWith($profilePicturePath, 'http') ? $profilePicturePath : asset('storage/' . $profilePicturePath) }}{{ $cacheBuster }}" alt="Profile" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->displayName ?? $user->name ?? 'User') }}&size={{ $size }}&background=random'" {{ $attributes }}>
@else
    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->displayName ?? $user->name ?? 'User') }}&size={{ $size }}&background=random" alt="Profile" {{ $attributes }}>
@endif
