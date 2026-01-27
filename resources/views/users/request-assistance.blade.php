<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Assistance - AutoCar</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&libraries=places"></script>
    <style>
        .request-hero {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url('https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80');
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

        .request-hero h1 {
            font-size: 3rem;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .request-form-section {
            padding: 80px 10%;
            background: #000;
        }

        .form-container {
            max-width: 900px;
            margin: 0 auto;
            background: #1a1a1a;
            padding: 50px;
            border-radius: 5px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .form-header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f8c300;
        }

        .form-header h2 {
            font-size: 2rem;
            color: #fff;
            margin: 0 0 10px;
        }

        .form-header p {
            color: #888;
            margin: 0;
        }

        .form-group {
            margin-bottom: 30px;
        }

        .form-group label {
            display: block;
            color: #fff;
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 0.95rem;
            text-transform: uppercase;
        }

        .form-group label i {
            color: #f8c300;
            margin-right: 8px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 15px;
            background: #222;
            border: 2px solid #333;
            color: #fff;
            border-radius: 3px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #f8c300;
            background: #2a2a2a;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .service-selection {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .service-option {
            background: #222;
            padding: 25px;
            text-align: center;
            border: 2px solid #333;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .service-option:hover {
            border-color: #f8c300;
            transform: translateY(-5px);
        }

        .service-option.selected {
            border-color: #f8c300;
            background: #2a2a2a;
        }

        .service-option input[type="radio"] {
            display: none;
        }

        .service-option i {
            font-size: 2.5rem;
            color: #f8c300;
            margin-bottom: 15px;
            display: block;
        }

        .service-option h4 {
            color: #fff;
            margin: 0;
            font-size: 0.9rem;
            text-transform: uppercase;
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

        .btn-submit {
            background: #f8c300;
            color: #000;
            border: none;
            padding: 18px 50px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 3px;
            text-transform: uppercase;
            font-size: 1.1rem;
            transition: all 0.3s;
            width: 100%;
            margin-top: 20px;
        }

        .btn-submit:hover {
            background: #fff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(248, 195, 0, 0.3);
        }

        .btn-submit:disabled {
            background: #666;
            cursor: not-allowed;
            transform: none;
        }

        .required {
            color: #f44336;
        }

        #map {
            height: 400px;
            width: 100%;
            border-radius: 5px;
            border: 2px solid #333;
            margin-top: 10px;
            display: none;
        }

        #map.active {
            display: block;
        }

        .mechanic-filters {
            background: #1a1a1a;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 30px;
            border: 1px solid #333;
            box-shadow: 0 4px 6px rgba(0,0,0,0.3);
        }

        .mechanic-filters h4 {
            color: #f8c300;
            margin: 0 0 25px;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid #333;
            padding-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-group {
            margin-bottom: 25px;
            background: rgba(255, 255, 255, 0.02);
            padding: 15px;
            border-radius: 6px;
        }

        .filter-group:last-child {
            margin-bottom: 0;
        }

        .filter-group label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #ccc;
            margin-bottom: 15px;
            font-size: 0.85rem;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .filter-group input[type="range"] {
            -webkit-appearance: none;
            width: 100%;
            height: 6px;
            background: #333;
            border-radius: 3px;
            outline: none;
            transition: background 0.3s;
            /* Reset conflicting styles from .form-group input */
            padding: 0;
            border: none;
            margin: 10px 0;
            display: block;
        }

        .filter-group input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            background: #f8c300;
            cursor: pointer;
            border-radius: 50%;
            border: 2px solid #000;
            box-shadow: 0 0 0 3px rgba(248, 195, 0, 0.2);
            transition: transform 0.1s, box-shadow 0.2s;
            margin-top: -7px; /* Center thumb on track */
        }
        
        .filter-group input[type="range"]::-webkit-slider-runnable-track {
            width: 100%;
            height: 6px;
            cursor: pointer;
            background: #333;
            border-radius: 3px;
        }

        .filter-group input[type="range"]::-webkit-slider-thumb:hover {
            transform: scale(1.1);
            box-shadow: 0 0 0 5px rgba(248, 195, 0, 0.3);
        }

        .filter-group input[type="range"]::-moz-range-thumb {
            width: 20px;
            height: 20px;
            background: #f8c300;
            cursor: pointer;
            border-radius: 50%;
            border: 2px solid #000;
            box-shadow: 0 0 0 3px rgba(248, 195, 0, 0.2);
            transition: transform 0.1s, box-shadow 0.2s;
        }
        
        .filter-group input[type="range"]::-moz-range-track {
            width: 100%;
            height: 6px;
            cursor: pointer;
            background: #333;
            border-radius: 3px;
        }

        .filter-value {
            color: #f8c300;
            font-weight: bold;
            background: rgba(248, 195, 0, 0.1);
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 0.9rem;
            min-width: 60px;
            text-align: center;
        }

        .mechanic-option {
            background: #2a2a2a;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 2px solid #333;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }

        .mechanic-option:hover {
            border-color: #f8c300;
            transform: translateX(5px);
        }

        .mechanic-option.selected {
            border-color: #f8c300;
            background: #333;
        }

        .mechanic-option.recommended {
            /* No special border or background - looks like other mechanics */
        }

        .mechanic-option.recommended::before {
            content: "RECOMMENDED";
            position: absolute;
            top: -10px;
            right: 10px;
            background: #4caf50;
            color: #fff;
            padding: 3px 10px;
            font-size: 0.7rem;
            border-radius: 3px;
            font-weight: bold;
        }

        .mechanic-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .mechanic-details h5 {
            color: #fff;
            margin: 0 0 5px;
            font-size: 1rem;
        }

        .mechanic-details p {
            color: #888;
            margin: 0;
            font-size: 0.85rem;
        }

        .mechanic-stats {
            text-align: right;
        }

        .mechanic-stats .rating {
            color: #f8c300;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .mechanic-stats .distance {
            color: #4caf50;
            font-size: 0.85rem;
        }

        .no-mechanics {
            text-align: center;
            padding: 30px;
            color: #888;
        }

        .location-controls {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .btn-secondary {
            background: #333;
            color: #fff;
            border: 2px solid #555;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 3px;
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .btn-secondary:hover {
            background: #444;
            border-color: #f8c300;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .service-selection {
                grid-template-columns: 1fr;
            }

            .form-container {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>

    <!-- Standardized User Header -->
    <x-user-header />

    <!-- Request Hero -->
    <section class="request-hero">
        <div class="hero-content">
            <h1><i class="fas fa-car-crash" style="color: #f8c300;"></i> Request Assistance</h1>
            <p style="font-size: 1.2rem; color: #ddd; margin: 0;">
                Need help? Fill out the form below and we'll dispatch assistance immediately
            </p>
        </div>
    </section>

    <!-- Request Form -->
    <section class="request-form-section">
        <div class="form-container">
            <div class="form-header">
                <h2>Submit Assistance Request</h2>
                <p>Please provide accurate information to help us serve you better</p>
            </div>

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
                        <strong>Please fix the following errors:</strong>
                        <ul style="margin: 10px 0 0 20px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('user.request.assistance.store') }}" method="POST" id="assistanceForm">
                @csrf

                <!-- Service Type Selection -->
                <div class="form-group">
                    <label><i class="fas fa-tools"></i> Service Type <span class="required">*</span></label>
                    <div class="service-selection">
                        <label class="service-option">
                            <input type="radio" name="breakdown_type" value="Engine Problem" required>
                            <i class="fas fa-screwdriver-wrench"></i>
                            <h4>Engine Problem</h4>
                        </label>
                        <label class="service-option">
                            <input type="radio" name="breakdown_type" value="Battery & Electrical" required>
                            <i class="fas fa-battery-full"></i>
                            <h4>Battery & Electrical</h4>
                        </label>
                        <label class="service-option">
                            <input type="radio" name="breakdown_type" value="Flat Tire" required>
                            <i class="fas fa-life-ring"></i>
                            <h4>Flat Tire</h4>
                        </label>
                        <label class="service-option">
                            <input type="radio" name="breakdown_type" value="Lock Out" required>
                            <i class="fas fa-unlock"></i>
                            <h4>Lock Out</h4>
                        </label>
                        <label class="service-option">
                            <input type="radio" name="breakdown_type" value="Accident" required>
                            <i class="fas fa-car-crash"></i>
                            <h4>Accident</h4>
                        </label>
                        <label class="service-option">
                            <input type="radio" name="breakdown_type" value="Transmission Problem" required>
                            <i class="fas fa-gears"></i>
                            <h4>Transmission</h4>
                        </label>
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Your Name <span class="required">*</span></label>
                        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-phone"></i> Phone Number <span class="required">*</span></label>
                        <input type="tel" name="phone_number" value="{{ old('phone_number', Auth::user()->phone) }}" placeholder="+60123456789" required>
                    </div>
                </div>

                <!-- Vehicle Information -->
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-id-card"></i> Plate Number <span class="required">*</span></label>
                        <input type="text" name="plate_number" value="{{ old('plate_number') }}" placeholder="ABC 1234" required>
                    </div>
                            <div class="form-group">
                                <label><i class="fas fa-car-side"></i> Select Car Brand <span class="required">*</span></label>
                                <select id="vehicle_make" name="vehicle_make" class="form-select" required>
                                    <option value="" selected disabled>Select Brand</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label><i class="fas fa-car"></i> Select Car Model <span class="required">*</span></label>
                                <select id="vehicle_model" class="form-select @error('vehicle_model') is-invalid @enderror" name="vehicle_model">
                                    <option value="" selected disabled>Select Model</option>
                                </select>
                            </div>
                </div>

                <!-- Insurance Selection -->
                <div class="form-group">
                    <label><i class="fas fa-shield-alt"></i> Insurance Company <span class="required">*</span></label>
                    <select name="insurance_company_id" id="insuranceSelect" required>
                        <option value="">-- Select Insurance Company --</option>
                        @foreach($insuranceCompanies as $company)
                            <option value="{{ $company->id }}" {{ old('insurance_company_id') == $company->id ? 'selected' : '' }}>
                                {{ $company->company_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Location -->
                <div class="form-group">
                    <label><i class="fas fa-map-marker-alt"></i> Current Location <span class="required">*</span></label>
                    <input type="text" name="location_address" id="locationInput" value="{{ old('location_address') }}" placeholder="Click 'Use My Location' button below" required readonly>
                    <div class="location-controls">
                        <button type="button" class="btn-secondary" id="useCurrentLocation">
                            <i class="fas fa-location-crosshairs"></i> Use My Current Location
                        </button>
                        <button type="button" class="btn-secondary" id="searchLocation" style="display: none;">
                            <i class="fas fa-search"></i> Search Address
                        </button>
                    </div>
                    <div id="map"></div>
                </div>

                <!-- Location Coordinates (Hidden) -->
                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">

                <!-- Mechanic Selection -->
                <div class="form-group" id="mechanicSection" style="display: none;">
                    <label><i class="fas fa-user-cog"></i> Select Mechanic</label>
                    
                    <div class="mechanic-filters" id="mechanicFilters">
                        <h4><i class="fas fa-filter"></i> Filter Mechanics</h4>
                        
                        <div class="filter-group">
                            <label>
                                Minimum Experience: <span class="filter-value" id="expValue">0 years</span>
                            </label>
                            <input type="range" id="expFilter" min="0" max="20" value="0" step="1">
                        </div>

                        <div class="filter-group">
                            <label>
                                Minimum Rating: <span class="filter-value" id="ratingValue">0.0 ★</span>
                            </label>
                            <input type="range" id="ratingFilter" min="0" max="5" value="0" step="0.5">
                        </div>

                        <div class="filter-group">
                            <label>
                                Maximum Distance: <span class="filter-value" id="distanceValue">Unlimited</span>
                            </label>
                            <input type="range" id="distanceFilter" min="5" max="100" value="100" step="5">
                        </div>
                    </div>

                    <div id="mechanicsList"></div>
                    <input type="hidden" name="mechanic_id" id="selectedMechanicId">
                    <input type="hidden" name="distance_fee" id="selectedDeliveryFee">
                    <input type="hidden" name="night_surcharge" id="selectedNightSurcharge">
                    <input type="hidden" name="total_cost" id="selectedTotalCost">
                </div>

                <!-- Additional Notes -->
                <div class="form-group">
                    <label><i class="fas fa-comment"></i> Additional Notes</label>
                    <textarea name="notes" placeholder="Provide any additional information that might help us assist you better">{{ old('notes') }}</textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit">
                    <i class="fas fa-paper-plane"></i> Submit Request
                </button>
            </form>
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

        // Service Type Selection
        document.querySelectorAll('.service-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.service-option').forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');
                this.querySelector('input[type="radio"]').checked = true;
                
                // Reload mechanics with new breakdown type if insurance is selected
                const insuranceId = document.getElementById('insuranceSelect').value;
                if (insuranceId && allMechanics.length > 0) {
                    // Trigger change event to reload with AI recommendation
                    document.getElementById('insuranceSelect').dispatchEvent(new Event('change'));
                }
            });
        });

        // Map and Location Variables
        let map, marker, userLat, userLng;
        let allMechanics = [];
        let mapInitialized = false;

        // Use Current Location Button
        document.getElementById('useCurrentLocation').addEventListener('click', function() {
            if (navigator.geolocation) {
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Getting location...';
                this.disabled = true;
                const btn = this;
                
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    // Initialize map if not done yet
                    if (!mapInitialized) {
                        initializeMap(lat, lng);
                    } else {
                        marker.setLatLng([lat, lng]);
                        map.setView([lat, lng], 13);
                    }
                    
                    updateLocation(lat, lng);
                    btn.innerHTML = '<i class="fas fa-check-circle"></i> Location Set';
                    btn.disabled = false;
                    document.getElementById('searchLocation').style.display = 'inline-block';
                }, function(error) {
                    alert('Could not get your location. Please enable location services in your browser.');
                    btn.innerHTML = '<i class="fas fa-location-crosshairs"></i> Use My Current Location';
                    btn.disabled = false;
                });
            } else {
                alert('Geolocation is not supported by your browser');
            }
        });

        // Initialize Map with Google Maps
        function initializeMap(lat, lng) {
            document.getElementById('map').classList.add('active');
            
            try {
                // Initialize Google Map
                map = new google.maps.Map(document.getElementById('map'), {
                    center: { lat: lat, lng: lng },
                    zoom: 13,
                    mapTypeControl: true,
                    streetViewControl: false,
                    fullscreenControl: true
                });

                // Create draggable marker
                marker = new google.maps.Marker({
                    position: { lat: lat, lng: lng },
                    map: map,
                    draggable: true,
                    title: 'Your Location'
                });

                // Marker drag event
                marker.addListener('dragend', function(e) {
                    updateLocation(e.latLng.lat(), e.latLng.lng());
                });

                // Map click event
                map.addListener('click', function(e) {
                    marker.setPosition(e.latLng);
                    updateLocation(e.latLng.lat(), e.latLng.lng());
                });

                mapInitialized = true;
            } catch (error) {
                console.error('Google Maps initialization failed:', error);
                alert('Map service temporarily unavailable. Location features may be limited.');
            }
        }

        // Search Location Button with Google Geocoding
        document.getElementById('searchLocation').addEventListener('click', function() {
            const query = document.getElementById('locationInput').value;
            if (query && query !== "Click 'Use My Location' button below") {
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Searching...';
                this.disabled = true;
                const btn = this;
                
                try {
                    const geocoder = new google.maps.Geocoder();
                    geocoder.geocode({ 
                        address: query,
                        region: 'MY' // Prioritize Malaysia results
                    }, function(results, status) {
                        if (status === 'OK' && results[0]) {
                            const lat = results[0].geometry.location.lat();
                            const lng = results[0].geometry.location.lng();
                            
                            if (!mapInitialized) {
                                initializeMap(lat, lng);
                            } else {
                                marker.setPosition({ lat: lat, lng: lng });
                                map.setCenter({ lat: lat, lng: lng });
                            }
                            
                            updateLocation(lat, lng);
                        } else {
                            console.error('Geocoding failed:', status);
                            alert('Location not found. Please try a different address.');
                        }
                        btn.innerHTML = '<i class="fas fa-search"></i> Search Address';
                        btn.disabled = false;
                    });
                } catch (error) {
                    console.error('Geocoding error:', error);
                    alert('Search service temporarily unavailable. Please use the "Use My Location" button.');
                    btn.innerHTML = '<i class="fas fa-search"></i> Search Address';
                    btn.disabled = false;
                }
            }
        });

        // Update Location with Google Geocoding (Reverse Geocode)
        function updateLocation(lat, lng) {
            userLat = lat;
            userLng = lng;
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
            
            const locationInput = document.getElementById('locationInput');
            locationInput.removeAttribute('readonly');
            locationInput.value = 'Getting address...';
            locationInput.style.fontStyle = 'italic';
            locationInput.style.color = '#888';
            
            try {
                // Use Google Geocoding API (Reverse Geocode)
                const geocoder = new google.maps.Geocoder();
                geocoder.geocode({ location: { lat: lat, lng: lng } }, function(results, status) {
                    if (status === 'OK' && results[0]) {
                        locationInput.value = results[0].formatted_address;
                        locationInput.style.fontStyle = 'normal';
                        locationInput.style.color = '#fff';
                    } else {
                        console.error('Google Geocoding failed:', status);
                        // Fallback to backend (OSM)
                        fallbackReverseGeocode(lat, lng, locationInput);
                    }
                });
            } catch (error) {
                console.error('Google Geocoding error:', error);
                // Fallback to backend
                fallbackReverseGeocode(lat, lng, locationInput);
            }

            // Recalculate distances if mechanics are loaded
            if (allMechanics.length > 0) {
                renderMechanics();
            }
        }

        // Fallback reverse geocoding using backend (OSM)
        function fallbackReverseGeocode(lat, lng, locationInput) {
            fetch(`{{ route('user.reverse.geocode') }}?lat=${lat}&lng=${lng}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.address) {
                        locationInput.value = data.address;
                        locationInput.style.fontStyle = 'normal';
                        locationInput.style.color = '#fff';
                    } else {
                        locationInput.value = `Coordinates: ${lat.toFixed(6)}, ${lng.toFixed(6)} (Address not available)`;
                        locationInput.style.fontStyle = 'normal';
                        locationInput.style.color = '#f8c300';
                    }
                })
                .catch(error => {
                    console.error('Fallback geocoding error:', error);
                    locationInput.value = `Coordinates: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                    locationInput.style.fontStyle = 'normal';
                    locationInput.style.color = '#f44336';
                });
        }

        // Calculate distance between two points (Haversine formula)
        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371; // Radius of Earth in km
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                    Math.sin(dLon/2) * Math.sin(dLon/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return R * c;
        }

        // Load Mechanics based on Insurance Company
        document.getElementById('insuranceSelect').addEventListener('change', function() {
            const insuranceId = this.value;
            const mechanicSection = document.getElementById('mechanicSection');
            const mechanicsList = document.getElementById('mechanicsList');
            
            if (!insuranceId) {
                mechanicSection.style.display = 'none';
                return;
            }

            mechanicSection.style.display = 'block';
            mechanicsList.innerHTML = '<div class="no-mechanics"><i class="fas fa-spinner fa-spin"></i> AI is analyzing mechanics...</div>';
            
            // Get selected breakdown type
            const breakdownType = document.querySelector('input[name="breakdown_type"]:checked')?.value || 'general';
            
            // Build URL with location and breakdown type for AI analysis
            let url = `{{ url('/user/mechanics') }}/${insuranceId}`;
            const params = new URLSearchParams();
            
            if (userLat && userLng) {
                params.append('lat', userLat);
                params.append('lng', userLng);
            }
            params.append('breakdown_type', breakdownType);
            
            if (params.toString()) {
                url += '?' + params.toString();
            }
            
            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    allMechanics = data;
                    if (data.length === 0) {
                        mechanicsList.innerHTML = '<div class="no-mechanics"><i class="fas fa-exclamation-circle"></i> No mechanics available for this insurance company</div>';
                    } else {
                        renderMechanics();
                    }
                })
                .catch(error => {
                    console.error('Error loading mechanics:', error);
                    mechanicsList.innerHTML = '<div class="no-mechanics"><i class="fas fa-times-circle"></i> Error loading mechanics. Please refresh and try again.</div>';
                });
        });

        // Filter change handlers
        document.getElementById('expFilter').addEventListener('input', function() {
            document.getElementById('expValue').textContent = this.value + ' years';
            if (allMechanics.length > 0) renderMechanics();
        });

        document.getElementById('ratingFilter').addEventListener('input', function() {
            document.getElementById('ratingValue').textContent = this.value + ' ★';
            if (allMechanics.length > 0) renderMechanics();
        });

        document.getElementById('distanceFilter').addEventListener('input', function() {
            const val = parseInt(this.value);
            document.getElementById('distanceValue').textContent = val === 100 ? 'Unlimited' : val + ' km';
            if (allMechanics.length > 0) renderMechanics();
        });

        // Calculate delivery fee based on distance
        function calculateDeliveryFee(distance) {
            const baseFee = 20; // RM20 base fee
            const perKmRate = 2.50; // RM2.50 per km
            return baseFee + (distance * perKmRate);
        }

        // Check if night surcharge applies (10PM to 7AM)
        function isNightTime() {
            const hour = new Date().getHours();
            return hour >= 22 || hour < 7; // 10PM (22:00) to 7AM
        }

        // Calculate night surcharge (30% of delivery fee)
        function calculateNightSurcharge(deliveryFee) {
            return isNightTime() ? deliveryFee * 0.30 : 0;
        }

        // Render mechanics with filters
        function renderMechanics() {
            const minExp = parseInt(document.getElementById('expFilter').value);
            const minRating = parseFloat(document.getElementById('ratingFilter').value);
            const maxDistance = parseInt(document.getElementById('distanceFilter').value);

            let mechanics = allMechanics.map(m => {
                const mech = {...m};
                // If distance already calculated by backend, use it; otherwise calculate on frontend
                if (!m.distance && userLat && userLng && m.latitude && m.longitude) {
                    mech.distance = calculateDistance(userLat, userLng, parseFloat(m.latitude), parseFloat(m.longitude));
                } else if (!m.distance) {
                    mech.distance = 999;
                }
                
                // Calculate pricing
                if (mech.distance < 999) {
                    mech.deliveryFee = calculateDeliveryFee(mech.distance);
                    mech.nightSurcharge = calculateNightSurcharge(mech.deliveryFee);
                    mech.totalCost = mech.deliveryFee + mech.nightSurcharge;
                }
                
                return mech;
            });

            // Apply filters
            mechanics = mechanics.filter(m => {
                const expMatch = m.years_of_experience >= minExp;
                const ratingMatch = parseFloat(m.rating) >= minRating;
                const distanceMatch = maxDistance === 100 ? true : m.distance <= maxDistance;
                return expMatch && ratingMatch && distanceMatch;
            });

            // Sort: AI recommended first, then by score
            mechanics.sort((a, b) => {
                // AI recommended mechanics come first
                if (a.ai_recommended && !b.ai_recommended) return -1;
                if (!a.ai_recommended && b.ai_recommended) return 1;
                if (a.ai_alternative && !b.ai_alternative) return -1;
                if (!a.ai_alternative && b.ai_alternative) return 1;
                
                // Otherwise sort by score
                const scoreA = (a.years_of_experience * 2) + (parseFloat(a.rating) * 20) + (a.distance < 999 ? Math.max(0, 100 - a.distance) : 0);
                const scoreB = (b.years_of_experience * 2) + (parseFloat(b.rating) * 20) + (b.distance < 999 ? Math.max(0, 100 - b.distance) : 0);
                return scoreB - scoreA;
            });

            const mechanicsList = document.getElementById('mechanicsList');
            
            if (mechanics.length === 0) {
                mechanicsList.innerHTML = '<div class="no-mechanics"><i class="fas fa-filter"></i> No mechanics match your filters. Try adjusting the criteria.</div>';
                return;
            }

            mechanicsList.innerHTML = '';
            
            // Show recommendation message - either AI or Fallback
            if (mechanics[0]?.ai_recommended && mechanics[0]?.ai_reason) {
                const reasonDiv = document.createElement('div');
                const isAI = mechanics[0]?.ai_used === true;
                const isFallback = mechanics[0]?.fallback_used === true;
                
                // Different styling for AI vs Fallback
                if (isAI) {
                    reasonDiv.style.cssText = 'background: #1a3a1a; border: 2px solid #4caf50; padding: 15px; border-radius: 5px; margin-bottom: 15px; color: #fff;';
                    reasonDiv.innerHTML = `
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-robot" style="color: #4caf50; font-size: 1.5rem;"></i>
                            <div>
                                <strong style="color: #4caf50; display: block; margin-bottom: 5px;">🤖 AI Recommendation</strong>
                                <span style="color: #ddd;">${mechanics[0].ai_reason}</span>
                            </div>
                        </div>
                    `;
                } else if (isFallback) {
                    reasonDiv.style.cssText = 'background: #2a2a1a; border: 2px solid #f8c300; padding: 15px; border-radius: 5px; margin-bottom: 15px; color: #fff;';
                    reasonDiv.innerHTML = `
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-calculator" style="color: #f8c300; font-size: 1.5rem;"></i>
                            <div>
                                <strong style="color: #f8c300; display: block; margin-bottom: 5px;">📊 Smart Recommendation</strong>
                                <span style="color: #ddd;">${mechanics[0].ai_reason}</span>
                            </div>
                        </div>
                    `;
                } else {
                    reasonDiv.style.cssText = 'background: #1a3a1a; border: 2px solid #4caf50; padding: 15px; border-radius: 5px; margin-bottom: 15px; color: #fff;';
                    reasonDiv.innerHTML = `
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-lightbulb" style="color: #4caf50; font-size: 1.5rem;"></i>
                            <div>
                                <strong style="color: #4caf50; display: block; margin-bottom: 5px;">💡 Recommendation</strong>
                                <span style="color: #ddd;">${mechanics[0].ai_reason}</span>
                            </div>
                        </div>
                    `;
                }
                mechanicsList.appendChild(reasonDiv);
            }
            
            mechanics.forEach((mechanic, index) => {
                const div = document.createElement('div');
                const isRecommended = mechanic.ai_recommended || (index === 0 && !mechanics.some(m => m.ai_recommended));
                div.className = 'mechanic-option' + (isRecommended ? ' recommended' : '');
                div.dataset.mechanicId = mechanic.id;
                
                const distanceText = mechanic.distance < 999 ? 
                    `<div class="distance"><i class="fas fa-location-dot"></i> ${mechanic.distance.toFixed(1)} km away</div>` : 
                    '<div class="distance"><i class="fas fa-question-circle"></i> Set location to see distance</div>';

                // Pricing display
                const pricingHTML = mechanic.distance < 999 ? `
                    <div class="mechanic-pricing" style="margin-top: 10px; padding: 10px; background: rgba(248, 195, 0, 0.1); border-radius: 3px; border-left: 3px solid #f8c300;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <span style="color: #ddd; font-size: 0.85rem;"><i class="fas fa-truck"></i> Delivery Fee:</span>
                            <span style="color: #f8c300; font-weight: bold;">RM ${mechanic.deliveryFee.toFixed(2)}</span>
                        </div>
                        ${mechanic.nightSurcharge > 0 ? `
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <span style="color: #ddd; font-size: 0.85rem;"><i class="fas fa-moon"></i> Night Surcharge (30%):</span>
                            <span style="color: #ff9800; font-weight: bold;">RM ${mechanic.nightSurcharge.toFixed(2)}</span>
                        </div>
                        ` : ''}
                        <div style="display: flex; justify-content: space-between; padding-top: 8px; border-top: 1px solid rgba(248, 195, 0, 0.3);">
                            <span style="color: #fff; font-size: 0.9rem; font-weight: bold;"><i class="fas fa-calculator"></i> Total Cost:</span>
                            <span style="color: #4caf50; font-weight: bold; font-size: 1.1rem;">RM ${mechanic.totalCost.toFixed(2)}</span>
                        </div>
                    </div>
                ` : '';

                div.innerHTML = `
                    <div class="mechanic-info">
                        <div class="mechanic-details">
                            <h5>${mechanic.user.name}</h5>
                            <p><i class="fas fa-briefcase"></i> ${mechanic.years_of_experience} years experience</p>
                        </div>
                        <div class="mechanic-stats">
                            <div class="rating"><i class="fas fa-star"></i> ${mechanic.rating}</div>
                            ${distanceText}
                        </div>
                    </div>
                    ${pricingHTML}
                `;

                div.addEventListener('click', function() {
                    document.querySelectorAll('.mechanic-option').forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                    document.getElementById('selectedMechanicId').value = this.dataset.mechanicId;
                    
                    // Store pricing data
                    if (mechanic.distance < 999) {
                        document.getElementById('selectedDeliveryFee').value = mechanic.deliveryFee.toFixed(2);
                        document.getElementById('selectedNightSurcharge').value = mechanic.nightSurcharge.toFixed(2);
                        document.getElementById('selectedTotalCost').value = mechanic.totalCost.toFixed(2);
                    }
                });

                mechanicsList.appendChild(div);
            });

            // Auto-select recommended mechanic
            if (mechanics.length > 0) {
                document.getElementById('selectedMechanicId').value = mechanics[0].id;
                
                // Store pricing for recommended mechanic
                if (mechanics[0].distance < 999) {
                    document.getElementById('selectedDeliveryFee').value = mechanics[0].deliveryFee.toFixed(2);
                    document.getElementById('selectedNightSurcharge').value = mechanics[0].nightSurcharge.toFixed(2);
                    document.getElementById('selectedTotalCost').value = mechanics[0].totalCost.toFixed(2);
                }
                
                mechanicsList.firstChild?.nextElementSibling?.classList.add('selected') || mechanicsList.firstChild?.classList.add('selected');
            }
        }

        // NHTSA API Integration for Vehicle Selection
            const makeSelect = document.getElementById('vehicle_make');
            const modelSelect = document.getElementById('vehicle_model');

        if (makeSelect && modelSelect) {
                // Fetch Local Vehicle Data (Generated from Python Script)
                // Use asset() helper to ensure correct path regardless of deployment subdirectory
                fetch("{{ asset('vehicles.json') }}")
                    .then(response => {
                        if (!response.ok) throw new Error("HTTP error " + response.status);
                        return response.json();
                    })
                    .then(data => {
                        window.vehicleData = data; // Store globally for easy access
                        
                        // Populate Makes (Sorted Alphabetically)
                        const makes = Object.keys(data).sort((a, b) => a.localeCompare(b));
                        
                        makes.forEach(make => {
                            const option = document.createElement('option');
                            option.value = make;
                            option.textContent = make;
                            makeSelect.appendChild(option);
                        });
                        console.log('Vehicle data loaded successfully');
                    })
                    .catch(err => {
                        console.error('Error fetching vehicle data:', err);
                        // Fallback in case of error
                        const fallbackMakes = ['Perodua', 'Proton', 'Honda', 'Toyota', 'Nissan', 'Mazda'];
                        fallbackMakes.forEach(make => {
                            const option = document.createElement('option');
                            option.value = make;
                            option.textContent = make;
                            makeSelect.appendChild(option);
                        });
                    });

                // 2. Fetch Models when Make is selected
                makeSelect.addEventListener('change', function() {
                    const make = this.value;
                    modelSelect.innerHTML = '<option value="" selected disabled>Select Model</option>'; // Clear previous models
                    
                    if (make && window.vehicleData && window.vehicleData[make]) {
                        // Populate Models from local data
                        window.vehicleData[make].forEach(model => {
                            const option = document.createElement('option');
                            option.value = model;
                            option.textContent = model;
                            modelSelect.appendChild(option);
                        });
                    }
                });
            }

    </script>
</body>
</html>
