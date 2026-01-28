<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Details - AutoCar</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .details-hero {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url('https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            padding: 20px;
        }

        .details-hero h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .details-section {
            padding: 60px 10%;
            background: #000;
            min-height: calc(100vh - 450px);
        }

        .details-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #f8c300;
            text-decoration: none;
            font-size: 0.95rem;
            margin-bottom: 30px;
            transition: all 0.3s;
        }

        .back-button:hover {
            color: #fff;
            transform: translateX(-5px);
        }

        .details-card {
            background: #1a1a1a;
            border-radius: 8px;
            padding: 40px;
            border: 2px solid #333;
        }

        .details-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f8c300;
        }

        .request-id {
            font-size: 2rem;
            color: #f8c300;
            font-weight: bold;
            margin: 0;
        }

        .status-badge {
            padding: 10px 25px;
            border-radius: 25px;
            background: #4caf50;
            color: #fff;
            font-size: 0.9rem;
            font-weight: bold;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
            margin-bottom: 30px;
        }

        .detail-box {
            background: #222;
            padding: 20px;
            border-radius: 5px;
            border-left: 3px solid #f8c300;
        }

        .detail-box h3 {
            color: #f8c300;
            font-size: 0.85rem;
            text-transform: uppercase;
            margin: 0 0 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .detail-box h3 i {
            font-size: 1rem;
        }

        .detail-box p {
            color: #fff;
            font-size: 1.1rem;
            margin: 0;
            font-weight: 600;
        }

        .detail-box .sub-info {
            color: #888;
            font-size: 0.9rem;
            margin-top: 5px;
            font-weight: normal;
        }

        .location-box {
            background: #222;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
            border-left: 3px solid #f8c300;
        }

        .location-box h3 {
            color: #f8c300;
            font-size: 0.85rem;
            text-transform: uppercase;
            margin: 0 0 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .location-address {
            color: #ddd;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 10px;
            line-height: 1.6;
        }

        .completion-box {
            background: linear-gradient(135deg, #1a3a1a 0%, #1a1a1a 100%);
            padding: 25px;
            border-radius: 5px;
            margin-bottom: 30px;
            border: 2px solid #4caf50;
        }

        .completion-box h3 {
            color: #4caf50;
            font-size: 1rem;
            text-transform: uppercase;
            margin: 0 0 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .completion-box p {
            color: #ddd;
            margin: 0 0 10px;
            line-height: 1.6;
        }

        .completion-box p:last-child {
            margin-bottom: 0;
        }

        .notes-box {
            background: #222;
            padding: 20px;
            border-radius: 5px;
            margin-top: 15px;
            border-left: 3px solid #4caf50;
        }

        .notes-box strong {
            color: #4caf50;
            display: block;
            margin-bottom: 10px;
        }

        .rating-section {
            background: linear-gradient(135deg, #2a2a1a 0%, #1a1a1a 100%);
            padding: 30px;
            border-radius: 5px;
            border: 2px solid #f8c300;
            text-align: center;
        }

        .rating-section h3 {
            color: #fff;
            font-size: 1.3rem;
            margin: 0 0 15px;
        }

        .rating-section p {
            color: #ddd;
            margin: 0 0 25px;
        }

        .rating-display {
            margin-bottom: 25px;
        }

        .stars-display {
            display: inline-flex;
            gap: 8px;
            font-size: 2rem;
        }

        .stars-display i {
            color: #f8c300;
        }

        .review-text {
            background: #222;
            padding: 20px;
            border-radius: 5px;
            color: #ddd;
            font-style: italic;
            margin-top: 20px;
            border-left: 3px solid #f8c300;
        }

        .btn {
            padding: 15px 40px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-rate {
            background: #f8c300;
            color: #000;
        }

        .btn-rate:hover {
            background: #fff;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(248, 195, 0, 0.4);
        }

        /* Rating Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            animation: fadeIn 0.3s ease;
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background: #1a1a1a;
            padding: 40px;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            border: 2px solid #f8c300;
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .modal-header h2 {
            color: #fff;
            font-size: 1.8rem;
            margin: 0 0 10px;
        }

        .modal-header p {
            color: #888;
            margin: 0;
        }

        .stars-container {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin: 30px 0;
        }

        .star {
            font-size: 3rem;
            color: #333;
            cursor: pointer;
            transition: all 0.2s;
        }

        .star:hover,
        .star.active {
            color: #f8c300;
            transform: scale(1.2);
        }

        .rating-label {
            text-align: center;
            color: #f8c300;
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 20px;
            min-height: 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            color: #f8c300;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .form-group textarea {
            width: 100%;
            padding: 15px;
            background: #222;
            border: 2px solid #333;
            color: #fff;
            border-radius: 5px;
            font-size: 0.95rem;
            resize: vertical;
            min-height: 100px;
            transition: border-color 0.3s;
        }

        .form-group textarea:focus {
            outline: none;
            border-color: #f8c300;
        }

        .modal-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .modal-actions button {
            flex: 1;
            padding: 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            transition: all 0.3s;
        }

        .btn-submit {
            background: #4caf50;
            color: #fff;
        }

        .btn-submit:hover {
            background: #45a049;
            transform: translateY(-2px);
        }

        .btn-skip {
            background: #333;
            color: #fff;
        }

        .btn-skip:hover {
            background: #444;
        }

        /* Notification Styles */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #1a1a1a;
            border-left: 4px solid #4caf50;
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

        .notification.success {
            border-left-color: #4caf50;
        }

        .notification.error {
            border-left-color: #f44336;
        }

        .notification.info {
            border-left-color: #2196f3;
        }

        .notification-icon {
            font-size: 1.5rem;
        }

        .notification.success .notification-icon {
            color: #4caf50;
        }

        .notification.error .notification-icon {
            color: #f44336;
        }

        .notification.info .notification-icon {
            color: #2196f3;
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

        @media (max-width: 768px) {
            .details-grid {
                grid-template-columns: 1fr;
            }

            .details-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <x-user-header />

    <!-- Details Hero -->
    <section class="details-hero">
        <div class="hero-content">
            <h1><i class="fas fa-file-alt" style="color: #f8c300;"></i> Request Details</h1>
            <p style="font-size: 1.1rem; color: #ddd; margin: 0;">
                Completed Assistance Request Information
            </p>
        </div>
    </section>

    <!-- Details Section -->
    <section class="details-section">
        <div class="details-container">
            <a href="{{ route('user.request.history') }}" class="back-button">
                <i class="fas fa-arrow-left"></i> Back to History
            </a>

            <div class="details-card">
                <div class="details-header">
                    <h2 class="request-id">Request #{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}</h2>
                    <span class="status-badge">
                        <i class="fas fa-check-circle"></i> Completed
                    </span>
                </div>

                <!-- Request Details Grid -->
                <div class="details-grid">
                    <div class="detail-box">
                        <h3><i class="fas fa-tools"></i> Breakdown Type</h3>
                        <p>{{ $request->breakdown_type }}</p>
                    </div>

                    <div class="detail-box">
                        <h3><i class="fas fa-car"></i> Vehicle Information</h3>
                        <p>{{ $request->plate_number }}</p>
                        @if($request->vehicle_model)
                            <p class="sub-info">{{ $request->vehicle_model }}</p>
                        @endif
                        @if($request->vehicle_make)
                            <p class="sub-info">{{ $request->vehicle_make }}</p>
                        @endif
                    </div>

                    <div class="detail-box">
                        <h3><i class="fas fa-shield-alt"></i> Insurance Company</h3>
                        <p>{{ $request->insuranceCompany->company_name ?? $request->insurance_name ?? 'N/A' }}</p>
                    </div>

                    @if($request->mechanic)
                        <div class="detail-box">
                            <h3><i class="fas fa-user-cog"></i> Assigned Mechanic</h3>
                            <p>{{ $request->mechanic->user->name }}</p>
                            <p class="sub-info">
                                <i class="fas fa-star" style="color: #f8c300;"></i> {{ $request->mechanic->rating }}
                                <span style="margin: 0 5px;">•</span>
                                {{ $request->mechanic->years_of_experience }} years experience
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Location Information -->
                <div class="location-box">
                    <h3><i class="fas fa-map-marker-alt"></i> Breakdown Location</h3>
                    <p class="location-address">
                        <i class="fas fa-location-dot"></i>
                        {{ $request->location_address }}
                    </p>
                </div>

                <!-- Completion Information -->
                <div class="completion-box">
                    <h3>
                        <i class="fas fa-check-circle"></i>
                        Service Completed Successfully
                    </h3>
                    <p>
                        <i class="fas fa-calendar-check"></i>
                        <strong>Completed on:</strong> {{ $request->updated_at->format('F d, Y \a\t h:i A') }}
                    </p>
                    <p>
                        <i class="fas fa-calendar"></i>
                        <strong>Submitted on:</strong> {{ $request->created_at->format('F d, Y \a\t h:i A') }}
                    </p>
                    <p>
                        <i class="fas fa-clock"></i>
                        <strong>Service Duration:</strong> {{ $request->created_at->diffForHumans($request->updated_at, true) }}
                    </p>
                    @if($request->notes)
                        <div class="notes-box">
                            <strong>Service Notes:</strong>
                            {{ $request->notes }}
                        </div>
                    @endif
                </div>

                <!-- Rating Section -->
                @if($request->mechanic_rating)
                    <!-- Already Rated -->
                    <div class="rating-section">
                        <h3>Your Rating</h3>
                        <p>You rated this service on {{ $request->rated_at->format('F d, Y') }}</p>
                        <div class="rating-display">
                            <div class="stars-display">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $request->mechanic_rating)
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star" style="color: #333;"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>
                        @if($request->mechanic_review)
                            <div class="review-text">
                                "{{ $request->mechanic_review }}"
                            </div>
                        @endif
                    </div>
                @else
                    <!-- Not Yet Rated -->
                    <div class="rating-section">
                        <h3>Rate This Service</h3>
                        <p>Help us improve by rating your experience with {{ $request->mechanic->user->name ?? 'the mechanic' }}</p>
                        <button class="btn btn-rate" id="rateBtn">
                            <i class="fas fa-star"></i>
                            Rate Mechanic
                        </button>
                    </div>
                @endif
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

    <!-- Rating Modal -->
    <div id="ratingModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Rate Your Experience</h2>
                <p>How would you rate {{ $request->mechanic->user->name ?? 'the mechanic' }}?</p>
            </div>
            
            <form id="ratingForm">
                @csrf
                <div class="rating-label" id="ratingLabel">Select a rating</div>
                
                <div class="stars-container">
                    <i class="far fa-star star" data-rating="1"></i>
                    <i class="far fa-star star" data-rating="2"></i>
                    <i class="far fa-star star" data-rating="3"></i>
                    <i class="far fa-star star" data-rating="4"></i>
                    <i class="far fa-star star" data-rating="5"></i>
                </div>
                
                <input type="hidden" name="rating" id="ratingInput" value="0">
                
                <div class="form-group">
                    <label for="review">Review (Optional)</label>
                    <textarea 
                        name="review" 
                        id="review" 
                        placeholder="Share your experience with the service..."
                        maxlength="500"
                    ></textarea>
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn-skip" id="skipBtn">Skip for Now</button>
                    <button type="submit" class="btn-submit">Submit Rating</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Notification -->
    <div id="notification" class="notification">
        <div class="notification-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="notification-message" id="notificationMessage"></div>
        <button class="notification-close" onclick="hideNotification()">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <script>
        document.getElementById('year').textContent = new Date().getFullYear();



        // Rating Modal
        const modal = document.getElementById('ratingModal');
        const rateBtn = document.getElementById('rateBtn');
        const skipBtn = document.getElementById('skipBtn');
        const stars = document.querySelectorAll('.star');
        const ratingInput = document.getElementById('ratingInput');
        const ratingLabel = document.getElementById('ratingLabel');
        const ratingForm = document.getElementById('ratingForm');

        const ratingLabels = {
            1: 'Poor',
            2: 'Fair',
            3: 'Good',
            4: 'Very Good',
            5: 'Excellent'
        };

        if (rateBtn) {
            rateBtn.addEventListener('click', () => {
                modal.classList.add('show');
            });
        }

        if (skipBtn) {
            skipBtn.addEventListener('click', () => {
                modal.classList.remove('show');
            });
        }

        // Star rating interaction
        let selectedRating = 0;

        stars.forEach((star, index) => {
            star.addEventListener('mouseover', () => {
                updateStars(index + 1, true);
            });

            star.addEventListener('mouseout', () => {
                updateStars(selectedRating, false);
            });

            star.addEventListener('click', () => {
                selectedRating = index + 1;
                ratingInput.value = selectedRating;
                updateStars(selectedRating, false);
                ratingLabel.textContent = ratingLabels[selectedRating];
            });
        });

        function updateStars(rating, isHover) {
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.remove('far');
                    star.classList.add('fas', 'active');
                } else {
                    star.classList.remove('fas', 'active');
                    star.classList.add('far');
                }
            });
        }

        // Submit rating
        if (ratingForm) {
            ratingForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                if (selectedRating === 0) {
                    showNotification('Please select a rating', 'error');
                    return;
                }

                const formData = new FormData(ratingForm);
                
                try {
                    const response = await fetch('{{ route('user.rate.request', $request->id) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            rating: selectedRating,
                            review: document.getElementById('review').value
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        showNotification('Thank you for your rating!', 'success');
                        modal.classList.remove('show');
                        
                        // Reload page after 2 seconds
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        showNotification(data.message || 'Failed to submit rating', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showNotification('An error occurred. Please try again.', 'error');
                }
            });
        }

        // Notification functions
        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            const messageElement = document.getElementById('notificationMessage');
            const icon = notification.querySelector('.notification-icon i');
            
            notification.className = `notification ${type} show`;
            messageElement.textContent = message;
            
            // Update icon based on type
            if (type === 'success') {
                icon.className = 'fas fa-check-circle';
            } else if (type === 'error') {
                icon.className = 'fas fa-exclamation-circle';
            } else if (type === 'info') {
                icon.className = 'fas fa-info-circle';
            }
            
            // Auto hide after 5 seconds
            setTimeout(hideNotification, 5000);
        }

        function hideNotification() {
            const notification = document.getElementById('notification');
            notification.classList.remove('show');
        }

        // Close modal when clicking outside
        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('show');
            }
        });
    </script>
</body>
</html>
