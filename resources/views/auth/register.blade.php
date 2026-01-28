<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - AutoCar</title>
    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card" style="max-width: 500px;">
            <div class="auth-header">
                <h2>Auto<span>Car</span></h2>
                <p>Create your account</p>
            </div>

            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf
                <input type="hidden" name="role" id="roleInput" value="user">

                <!-- Role Selection Tabs -->
                <div class="role-tabs">
                    <div class="role-tab active" data-role="user" onclick="selectRole('user')">
                        <i class="fas fa-user"></i> User
                    </div>
                    <div class="role-tab" data-role="mechanic" onclick="selectRole('mechanic')">
                        <i class="fas fa-wrench"></i> Mechanic
                    </div>
                    <div class="role-tab" data-role="insurance_company" onclick="selectRole('insurance_company')">
                        <i class="fas fa-building"></i> Insurance
                    </div>
                </div>

                <!-- USER FORM -->
                <div id="form-user" class="role-form form-section">
                    <!-- Name -->
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Full Name">
                    </div>

                    <!-- Email -->
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email Address">
                    </div>

                    <!-- Password -->
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password">
                            </div>
                        </div>
                    </div>

                    <!-- Phone & Address -->
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        <input type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number') }}" placeholder="Phone Number">
                    </div>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                        <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" placeholder="Address">
                    </div>

                    <!-- DOB -->
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" class="form-control @error('date_of_birth') is-invalid @enderror" name="date_of_birth" value="{{ old('date_of_birth') }}" placeholder="Date of Birth">
                    </div>

                    <!-- Vehicle Info -->
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-car-side"></i></span>
                                <select id="vehicle_make" name="vehicle_make" class="form-select">
                                    <option value="" selected disabled>Select Brand</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-car"></i></span>
                                <select id="vehicle_model" class="form-select @error('vehicle_model') is-invalid @enderror" name="vehicle_model">
                                    <option value="" selected disabled>Select Model</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                        <input type="text" class="form-control @error('plate_number') is-invalid @enderror" name="plate_number" value="{{ old('plate_number') }}" placeholder="Plate Number">
                    </div>
                </div>

                <!-- MECHANIC FORM -->
                <div id="form-mechanic" class="role-form form-section hidden-field">
                    <!-- Name -->
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Full Name">
                    </div>

                    <!-- Email -->
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email Address">
                    </div>

                    <!-- Password -->
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password">
                            </div>
                        </div>
                    </div>

                    <!-- Phone & Address -->
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        <input type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number') }}" placeholder="Phone Number">
                    </div>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                        <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" placeholder="Address">
                    </div>

                    <!-- DOB -->
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" class="form-control @error('date_of_birth') is-invalid @enderror" name="date_of_birth" value="{{ old('date_of_birth') }}" placeholder="Date of Birth">
                    </div>

                    <!-- Professional Info -->
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                <input type="text" class="form-control @error('license_number') is-invalid @enderror" name="license_number" value="{{ old('license_number') }}" placeholder="License No.">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-briefcase"></i></span>
                                <input type="number" class="form-control @error('years_of_experience') is-invalid @enderror" name="years_of_experience" value="{{ old('years_of_experience') }}" placeholder="Experience (Yrs)">
                            </div>
                        </div>
                    </div>

                    <!-- Insurance Selection -->
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                        <select class="form-select @error('insurance_company_id') is-invalid @enderror" name="insurance_company_id">
                            <option value="" selected disabled>Select Insurance Company</option>
                            @foreach($insuranceCompanies as $company)
                                <option value="{{ $company->id }}" {{ old('insurance_company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->company_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- INSURANCE FORM -->
                <div id="form-insurance_company" class="role-form form-section hidden-field">
                    <!-- Company Name -->
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                        <input type="text" class="form-control @error('company_name') is-invalid @enderror" name="company_name" id="company_name_input" value="{{ old('company_name') }}" placeholder="Company Name" oninput="syncInsuranceName(this.value)">
                        <input type="hidden" name="name" id="insurance_hidden_name" value="{{ old('name') }}">
                    </div>

                    <!-- Email -->
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Company Email">
                    </div>

                    <!-- Password -->
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password">
                            </div>
                        </div>
                    </div>

                    <!-- Contact -->
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        <input type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number') }}" placeholder="Phone Number">
                    </div>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                        <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" placeholder="Address">
                    </div>

                    <!-- Company Details -->
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-registered"></i></span>
                        <input type="text" class="form-control @error('registration_number') is-invalid @enderror" name="registration_number" value="{{ old('registration_number') }}" placeholder="Registration Number">
                    </div>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-globe"></i></span>
                        <input type="url" class="form-control @error('website') is-invalid @enderror" name="website" value="{{ old('website') }}" placeholder="Website URL">
                    </div>
                </div>

                <!-- Error Messages Display -->
                @if ($errors->any())
                    <div class="alert alert-danger" style="background: rgba(255, 68, 68, 0.1); border: 1px solid #ff4444; color: #ff4444; padding: 10px; border-radius: 5px; font-size: 0.85rem; margin-bottom: 15px; text-align: left;">
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row mb-0">
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 1.1rem;">
                        {{ __('Register') }}
                    </button>
                </div>
            </form>

            <div class="auth-footer">
                <p>Already have an account? <a href="{{ route('login') }}">Login Here</a></p>
                <p><a href="{{ url('/') }}"><i class="fas fa-arrow-left"></i> Back to Home</a></p>
            </div>
        </div>
    </div>

    <script>
        function selectRole(role) {
            // Update Hidden Input
            document.getElementById('roleInput').value = role;

            // Update Tabs UI
            document.querySelectorAll('.role-tab').forEach(tab => {
                tab.classList.remove('active');
                if(tab.dataset.role === role) tab.classList.add('active');
            });

            // Toggle Forms
            const forms = ['user', 'mechanic', 'insurance_company'];
            forms.forEach(formRole => {
                const formEl = document.getElementById('form-' + formRole);
                if (formRole === role) {
                    formEl.classList.remove('hidden-field');
                    toggleInputs(formEl, true);
                } else {
                    formEl.classList.add('hidden-field');
                    toggleInputs(formEl, false);
                }
            });
        }

        function toggleInputs(container, enable) {
            container.querySelectorAll('input, select').forEach(el => {
                el.disabled = !enable;
            });
        }

        function syncInsuranceName(val) {
            document.getElementById('insurance_hidden_name').value = val;
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize with default or old role
            const oldRole = "{{ old('role', 'user') }}";
            selectRole(oldRole);
            
            // Initial sync for insurance name if value exists
            const companyNameInput = document.getElementById('company_name_input');
            if(companyNameInput && companyNameInput.value) {
                syncInsuranceName(companyNameInput.value);
            }

            // NHTSA API Integration for Vehicle Selection
            const makeSelect = document.getElementById('vehicle_make');
            const modelSelect = document.getElementById('vehicle_model');

            if (makeSelect && modelSelect) {
                // Fetch Local Vehicle Data (Generated from Python Script)
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

                // Handle Make Selection Change
                makeSelect.addEventListener('change', function() {
                    const selectedMake = this.value;
                    modelSelect.innerHTML = '<option value="" selected disabled>Select Model</option>';
                    
                    if (window.vehicleData && window.vehicleData[selectedMake]) {
                        const models = window.vehicleData[selectedMake].sort((a, b) => a.localeCompare(b));
                        models.forEach(model => {
                            const option = document.createElement('option');
                            option.value = model;
                            option.textContent = model;
                            modelSelect.appendChild(option);
                        });
                    }
                });
            }
        });
    </script>
</body>
</html>