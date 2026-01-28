<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $request->id }} - AutoCar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
        body {
            background-color: #000;
            color: #fff;
        }
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 0 20px rgba(248, 195, 0, 0.2);
            color: #333;
        }
        .btn-primary {
            background-color: #f8c300;
            border-color: #f8c300;
            color: #000;
            font-weight: bold;
        }
        .btn-primary:hover {
            background-color: #e0b000;
            border-color: #e0b000;
            color: #000;
        }
        .btn-secondary {
            background-color: #333;
            border-color: #333;
            color: #fff;
        }
        .btn-secondary:hover {
            background-color: #555;
            border-color: #555;
        }
        .receipt-header {
            border-bottom: 3px solid #667eea;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-logo {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }
        .receipt-title {
            color: #667eea;
            font-weight: bold;
        }
        .info-section {
            margin-bottom: 30px;
        }
        .info-label {
            font-weight: 600;
            color: #6c757d;
        }
        .total-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
        }
        .signature-section {
            margin-top: 50px;
            border-top: 2px solid #dee2e6;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="receipt-container">
            <!-- Header -->
            <div class="receipt-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="receipt-title mb-2">ASSISTANCE REQUEST RECEIPT</h1>
                        <p class="text-muted mb-0">Receipt #{{ str_pad($request->id, 6, '0', STR_PAD_LEFT) }}</p>
                        <p class="text-muted">Date: {{ now()->format('F d, Y') }}</p>
                    </div>
                    <div class="col-md-4 text-end">
                        @if($insurance->profile_picture)
                            <img src="{{ asset('storage/' . $insurance->profile_picture) }}" alt="{{ $insurance->company_name }}" class="company-logo">
                        @endif
                    </div>
                </div>
            </div>

            <!-- Insurance Company Information -->
            <div class="info-section">
                <h5 class="mb-3">Insurance Company</h5>
                <p class="mb-1"><span class="info-label">Company:</span> {{ $insurance->company_name }}</p>
                <p class="mb-1"><span class="info-label">Registration No:</span> {{ $insurance->registration_number }}</p>
                @if($insurance->phone_number)
                    <p class="mb-1"><span class="info-label">Phone:</span> {{ $insurance->phone_number }}</p>
                @endif
                @if($insurance->address)
                    <p class="mb-1"><span class="info-label">Address:</span> {{ $insurance->address }}</p>
                @endif
                @if($insurance->website)
                    <p class="mb-1"><span class="info-label">Website:</span> {{ $insurance->website }}</p>
                @endif
            </div>

            <!-- Customer Information -->
            <div class="info-section">
                <h5 class="mb-3">Customer Information</h5>
                <p class="mb-1"><span class="info-label">Name:</span> {{ $request->user->name }}</p>
                <p class="mb-1"><span class="info-label">Email:</span> {{ $request->user->email }}</p>
                <p class="mb-1"><span class="info-label">Phone:</span> {{ $request->phone_number }}</p>
            </div>

            <!-- Service Details -->
            <div class="info-section">
                <h5 class="mb-3">Service Details</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Description</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="info-label">Request ID</span></td>
                                <td>#{{ $request->id }}</td>
                            </tr>
                            <tr>
                                <td><span class="info-label">Service Date</span></td>
                                <td>{{ $request->created_at->format('F d, Y H:i A') }}</td>
                            </tr>
                            <tr>
                                <td><span class="info-label">Completion Date</span></td>
                                <td>{{ $request->updated_at->format('F d, Y H:i A') }}</td>
                            </tr>
                            <tr>
                                <td><span class="info-label">Vehicle Brand</span></td>
                                <td>{{ $request->vehicle_make }}</td>
                            </tr>
                            <tr>
                                <td><span class="info-label">Vehicle Model</span></td>
                                <td>{{ $request->vehicle_model }}</td>
                            </tr>
                            <tr>
                                <td><span class="info-label">Plate Number</span></td>
                                <td>{{ $request->plate_number }}</td>
                            </tr>
                            <tr>
                                <td><span class="info-label">Breakdown Type</span></td>
                                <td>{{ ucfirst(str_replace('_', ' ', $request->breakdown_type)) }}</td>
                            </tr>
                           
                            @if($request->mechanic)
                                <tr>
                                    <td><span class="info-label">Mechanic</span></td>
                                    <td>{{ $request->mechanic->user->name }}</td>
                                </tr>
                                <tr>
                                    <td><span class="info-label">Mechanic Phone</span></td>
                                    <td>{{ $request->mechanic->phone_number }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td><span class="info-label">Status</span></td>
                                <td><span class="badge bg-success">Completed</span></td>
                            </tr>
                            @if($request->rating)
                                <tr>
                                    <td><span class="info-label">Service Rating</span></td>
                                    <td>
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $request->rating)
                                                ⭐
                                            @else
                                                ☆
                                            @endif
                                        @endfor
                                        ({{ $request->rating }}/5)
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Total Section -->
            <div class="total-section">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-0"><strong>Service Type:</strong></p>
                        <p>Emergency Roadside Assistance</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <p class="mb-0"><strong>Coverage Status:</strong></p>
                        <p class="text-success mb-0"><strong>✓ Covered by Insurance</strong></p>
                    </div>
                </div>
            </div>

            <!-- Signature Section -->
            <div class="signature-section">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-5">This is generate e-invoice no signature required</p>
                        <p class="mb-0 text-center"><strong>Customer Signature</strong></p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-5">This is generate e-invoice no signature required</p>
                        <p class="mb-0 text-center"><strong>Insurance Representative</strong></p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-5">
                <p class="text-muted mb-0">This is an official receipt from {{ $insurance->company_name }}</p>
                <p class="text-muted">Generated on {{ now()->format('F d, Y H:i A') }}</p>
            </div>

            <!-- Print Button -->
            <div class="text-center mt-4 no-print">
                <button onclick="window.print()" class="btn btn-primary btn-lg">
                    <i class="fas fa-print me-2"></i>Print Receipt
                </button>
                <a href="{{ route('insurance_company.request.show', $request->id) }}" class="btn btn-secondary btn-lg">
                    <i class="fas fa-arrow-left me-2"></i>Back to Request
                </a>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</body>
</html>
