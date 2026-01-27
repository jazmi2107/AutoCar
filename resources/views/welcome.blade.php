<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>AutoCar</title> 
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head> 
<body> 

    <x-user-header /> 

    <section class="hero" style="background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1502877338535-766e1452684a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80');"> 
        <div class="hero-content"> 
            <h1 class="logo">Auto<span>Car</span></h1> 
            <p style="font-size: 20px; max-width: 1000px; margin: 0 auto 20px auto;">
                We are dedicated to providing top-quality auto repair and maintenance services. 
                Our team of certified technicians uses the latest technology to diagnose and 
                fix your vehicle correctly the first time.
            </p>
            <a href="{{ route('login') }}" class="btn-more">GET STARTED</a>
        </div>  
    </section>    

    <section class="featured-services"> 
        <div class="service-card"> 
            <img src="https://i.pinimg.com/736x/29/53/a8/2953a8d047b23b11a406e626ed8a144e.jpg" alt="24/7 Emergency Towing"> 
            <div class="card-text"> 
                <h3>24/7 EMERGENCY TOWING</h3> 
                <p>Fast response towing service available round-the-clock. We’ll get your vehicle to safety anywhere, anytime.</p> 
            </div> 
        </div> 
        <div class="service-card"> 
            <img src="https://i.pinimg.com/736x/c8/1f/2f/c81f2fe4f000c8ec86fbce75f546cf60.jpg" alt="On-Site Roadside Assistance"> 
            <div class="card-text"> 
                <h3>ROADSIDE ASSIST</h3> 
                <p>Stuck on the road? Our mobile crew reaches you quickly to jump-start, change tyres, or deliver fuel.</p> 
            </div> 
        </div> 
        <div class="service-card"> 
            <img src="https://i.pinimg.com/1200x/99/0e/cf/990ecf02f0c47f917a5f43c004a1e5a9.jpg" alt="Instant Mechanic Hotline"> 
            <div class="card-text"> 
                <h3>INSURANCE COVERED</h3> 
                <p>We cover all major types of auto insurance, including liability, collision, and comprehensive coverage.</p> 
            </div> 
        </div> 
    </section>

    <section class="our-services">
        <h2 class="section-title">Our Services</h2>
        <div class="services-grid">
            <div class="service-item">
                <i class="fas fa-screwdriver-wrench service-icon"></i>
                <div class="service-text">
                    <h3>Engine Problem</h3>
                    <p>Expert diagnosis and repair for all engine issues, from minor noises to major overhauls.</p>
                </div>
            </div>
            <div class="service-item">
                <i class="fas fa-battery-full service-icon"></i>
                <div class="service-text">
                    <h3>Battery & Electrical</h3>
                    <p>Complete electrical system service including battery testing, replacement, and alternator repairs.</p>
                </div>
            </div>
            <div class="service-item">
                <i class="fas fa-life-ring service-icon"></i>
                <div class="service-text">
                    <h3>Flat Tire</h3>
                    <p>Quick and reliable flat tire repair and replacement services to get you back on the road safely.</p>
                </div>
            </div>
            <div class="service-item">
                <i class="fas fa-unlock service-icon"></i>
                <div class="service-text">
                    <h3>Lock Out</h3>
                    <p>Emergency lockout assistance to help you regain access to your vehicle quickly and without damage.</p>
                </div>
            </div>
            <div class="service-item">
                <i class="fas fa-car-crash service-icon"></i>
                <div class="service-text">
                    <h3>Accident</h3>
                    <p>Comprehensive collision repair services to restore your vehicle's safety and aesthetics.</p>
                </div>
            </div>
            <div class="service-item">
                <i class="fas fa-gears service-icon"></i>
                <div class="service-text">
                    <h3>Transmission Problem</h3>
                    <p>Specialized transmission diagnostics and repair for smooth shifting and reliable operation.</p>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-bottom">
            <div class="container footer-flex">
                <div class="copyright">
                    &copy; <span id="year"></span> AutoCar. All Rights Reserved.
                </div>
                <div class="footer-phone">
                    CALL TODAY: <span>{{ config('site.phone') }}</span>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>
</body> 
</html>