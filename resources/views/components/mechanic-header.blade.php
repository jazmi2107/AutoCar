<style>
    header { background: #000; border-bottom: 3px solid #f8c300; }
    .top-bar { display: flex; justify-content: space-between; align-items: center; padding: 15px 30px; border-bottom: 1px solid #333; }
    
    .logo-container { display: flex; align-items: center; gap: 15px; text-decoration: none; }
    .logo-text { font-size: 28px; font-weight: bold; text-transform: uppercase; color: white; }
    .logo-text span { color: #F8B400; }
    .logo-divider { width: 1px; height: 30px; background: rgba(255,255,255,0.3); }
    .logo-img { height: 50px; width: auto; display: block; }
    .logo-icon-fallback { font-size: 32px; color: #f8c300; display: none; }
    
    .header-right { display: flex; align-items: center; gap: 20px; }
    .header-info-group { display: flex; align-items: center; gap: 20px; }
    .contact-phones { color: #f8c300; font-weight: bold; font-size: 1.1rem; }
    .contact-phones i { margin-right: 8px; }
    .user-menu-container { position: relative; }
    .profile-trigger { background: none; border: 2px solid #f8c300; border-radius: 50%; padding: 2px; cursor: pointer; transition: all 0.3s; }
    .profile-trigger:hover { border-color: #fff; transform: scale(1.05); }
    .profile-trigger img { border-radius: 50%; display: block; }
    .dropdown-menu { display: none; position: absolute; top: 100%; right: 0; background: #1a1a1a; border: 2px solid #f8c300; border-radius: 5px; min-width: 200px; margin-top: 10px; z-index: 1000; box-shadow: 0 5px 20px rgba(0,0,0,0.5); }
    .dropdown-menu.show { display: block; }
    .dropdown-menu a { display: block; padding: 12px 20px; color: #fff; text-decoration: none; transition: all 0.3s; border-bottom: 1px solid #333; }
    .dropdown-menu a:last-child { border-bottom: none; }
    .dropdown-menu a:hover { background: #f8c300; color: #000; }
    nav { background: #0a0a0a; }
    nav ul { list-style: none; margin: 0; padding: 0; display: flex; justify-content: center; }
    nav li { margin: 0; }
    nav a { display: block; padding: 15px 30px; color: #fff; text-decoration: none; font-weight: bold; transition: all 0.3s; border-bottom: 3px solid transparent; }
    nav a:hover, nav a.active { color: #f8c300; border-bottom-color: #f8c300; background: rgba(248, 195, 0, 0.1); }
    
    @media (max-width: 768px) {
        .top-bar { flex-direction: column; gap: 15px; padding: 15px; }
        .logo-container { width: 100%; justify-content: center; }
        .header-right { flex-direction: column; width: 100%; }
        .header-info-group { flex-direction: column; width: 100%; gap: 10px; }
        nav ul { flex-direction: column; }
        nav a { padding: 12px 20px; }
    }
</style>

<header>
    <div class="top-bar">
        <div class="logo">
            <a href="{{ url('/') }}" class="logo-container">
                <div class="logo-text">
                    Auto<span>Car</span>
                </div>
                <div class="logo-divider"></div>
                <img src="{{ asset('images/autocar-logo.png') }}" 
                     alt="" 
                     class="logo-img"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <i class="fas fa-car logo-icon-fallback"></i>
            </a>
        </div>
        <div class="header-right">
            <div class="header-info-group">
                <div class="contact-phones">
                    <span><i class="fas fa-phone"></i> {{ config('site.phone', '+6012 284 0561') }}</span>
                </div>
            </div>
            <div class="user-menu-container">
                <button class="profile-trigger" id="userMenuBtn">
                    @php
                        $mechanic = Auth::user()->mechanic;
                        $profilePicture = $mechanic && $mechanic->profile_picture && Storage::disk('public')->exists($mechanic->profile_picture)
                            ? asset('storage/' . $mechanic->profile_picture)
                            : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=f8c300&color=000&size=40&bold=true';
                    @endphp
                    <img src="{{ $profilePicture }}" alt="Profile" style="width: 40px; height: 40px; object-fit: cover;" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=f8c300&color=000&size=40&bold=true'">
                </button>
                <div class="dropdown-menu" id="userDropdown">
                    <a href="{{ route('mechanic.profile') }}"><i class="fas fa-user"></i> My Profile</a>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
    <nav>
        <ul>
            <li><a href="{{ route('mechanic.dashboard') }}" class="{{ request()->routeIs('mechanic.dashboard') ? 'active' : '' }}">DASHBOARD</a></li>
            <li><a href="{{ route('mechanic.assigned_jobs') }}" class="{{ request()->routeIs('mechanic.assigned_jobs') ? 'active' : '' }}">ASSIGNED JOB</a></li>
            <li><a href="{{ route('mechanic.job_history') }}" class="{{ request()->routeIs('mechanic.job_history') ? 'active' : '' }}">JOB HISTORY</a></li>
        </ul>
    </nav>
</header>

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
