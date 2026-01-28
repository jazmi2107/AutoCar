<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Mechanic\MechanicController;
use App\Http\Controllers\InsuranceCompany\InsuranceCompanyController;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Debug Routes
Route::get('/debug-auth', function() {
    return [
        'auth_config' => config('auth'),
        'firebase_config' => [
            'default' => config('firebase.default'),
            'project' => config('firebase.projects.' . config('firebase.default')),
        ],
        'env' => [
            'FIREBASE_PROJECT_ID' => env('FIREBASE_PROJECT_ID'),
            'GOOGLE_CLOUD_PROJECT' => env('GOOGLE_CLOUD_PROJECT'),
            'FIREBASE_PROJECT' => env('FIREBASE_PROJECT'),
            'HAS_CREDENTIALS' => !empty(env('FIREBASE_CREDENTIALS')),
        ]
    ];
});

Route::get('/clear-cache', function() {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return "Cache cleared";
});

// User Dashboard Routes
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/request-assistance', [UserDashboardController::class, 'requestAssistance'])->name('request.assistance');
    Route::post('/request-assistance', [UserDashboardController::class, 'storeAssistanceRequest'])->name('request.assistance.store');
    Route::get('/mechanics/{insurance_company_id}', [UserDashboardController::class, 'getMechanics'])->name('get.mechanics');
    Route::get('/my-requests', [UserDashboardController::class, 'myRequests'])->name('my.requests');
    Route::get('/track/{id}', [UserDashboardController::class, 'trackRequest'])->name('track.request');
    Route::post('/request/{id}/cancel', [UserDashboardController::class, 'cancelRequest'])->name('cancel.request');
    Route::post('/request/{id}/rate', [UserDashboardController::class, 'rateRequest'])->name('rate.request');
    Route::get('/request-history', [UserDashboardController::class, 'requestHistory'])->name('request.history');
    Route::get('/request-details/{id}', [UserDashboardController::class, 'viewCompletedRequest'])->name('request.details');
    Route::get('/receipt/{id}', [UserDashboardController::class, 'receipt'])->name('receipt');
    Route::get('/profile', [UserDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [UserDashboardController::class, 'updatePassword'])->name('password.update');
    Route::get('/reverse-geocode', [UserDashboardController::class, 'reverseGeocode'])->name('reverse.geocode');
});

// User Management Routes (CRUD)
Route::middleware(['auth'])->prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::get('/{id}', [UserController::class, 'show'])->name('show');
    Route::get('/{id}/view', [UserController::class, 'view'])->name('view');
    Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
    Route::put('/{id}', [UserController::class, 'update'])->name('update');
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
});

// Mechanic Dashboard Routes
Route::middleware(['auth'])->prefix('mechanic')->name('mechanic.')->group(function () {
    Route::get('/dashboard', [MechanicController::class, 'index'])->name('dashboard');
    Route::get('/assigned-jobs', [MechanicController::class, 'assignedJobs'])->name('assigned_jobs');
    Route::post('/jobs/{id}/approve', [MechanicController::class, 'approveJob'])->name('job.approve');
    Route::post('/jobs/{id}/reject', [MechanicController::class, 'rejectJob'])->name('job.reject');
    Route::get('/job-history', [MechanicController::class, 'jobHistory'])->name('job_history');
    Route::get('/profile', [MechanicController::class, 'profile'])->name('profile');
    Route::put('/profile', [MechanicController::class, 'updateProfile'])->name('profile.update');
    Route::put('/location', [MechanicController::class, 'updateLocation'])->name('location.update');
    Route::put('/password', [MechanicController::class, 'updatePassword'])->name('password.update');
    Route::get('/requests/{id}', [MechanicController::class, 'showRequest'])->name('request.show');
    Route::put('/requests/{id}/status', [MechanicController::class, 'updateRequestStatus'])->name('request.status');
});

// Admin Dashboard Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
    Route::get('/users/create', [AdminDashboardController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminDashboardController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{id}', [AdminDashboardController::class, 'showUser'])->name('users.show');
    Route::get('/users/{id}/edit', [AdminDashboardController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{id}', [AdminDashboardController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [AdminDashboardController::class, 'deleteUser'])->name('users.delete');
    
    // Mechanic Management
    Route::get('/approvals', [AdminDashboardController::class, 'approvals'])->name('approvals');
    Route::get('/mechanics/approvals', [AdminDashboardController::class, 'mechanicApprovals'])->name('mechanics.approvals');
    Route::get('/mechanics', [AdminDashboardController::class, 'mechanics'])->name('mechanics');
    Route::get('/mechanics/create', [AdminDashboardController::class, 'createMechanic'])->name('mechanics.create');
    Route::post('/mechanics', [AdminDashboardController::class, 'storeMechanic'])->name('mechanics.store');
    Route::get('/mechanics/{id}', [AdminDashboardController::class, 'showMechanic'])->name('mechanics.show');
    Route::get('/mechanics/{id}/edit', [AdminDashboardController::class, 'editMechanic'])->name('mechanics.edit');
    Route::put('/mechanics/{id}', [AdminDashboardController::class, 'updateMechanic'])->name('mechanics.update');
    Route::post('/mechanics/{id}/approve', [AdminDashboardController::class, 'approveMechanic'])->name('mechanics.approve');
    Route::post('/mechanics/{id}/reject', [AdminDashboardController::class, 'rejectMechanic'])->name('mechanics.reject');
    Route::delete('/mechanics/{id}', [AdminDashboardController::class, 'deleteMechanic'])->name('mechanics.delete');
    
    // Insurance Company Management
    Route::get('/insurance-companies', [AdminDashboardController::class, 'insuranceCompanies'])->name('insurance_companies');
    Route::get('/insurance-companies/create', [AdminDashboardController::class, 'createInsuranceCompany'])->name('insurance_companies.create');
    Route::post('/insurance-companies', [AdminDashboardController::class, 'storeInsuranceCompany'])->name('insurance_companies.store');
    Route::get('/insurance-companies/{id}', [AdminDashboardController::class, 'showInsuranceCompany'])->name('insurance_companies.show');
    Route::get('/insurance-companies/{id}/edit', [AdminDashboardController::class, 'editInsuranceCompany'])->name('insurance_companies.edit');
    Route::put('/insurance-companies/{id}', [AdminDashboardController::class, 'updateInsuranceCompany'])->name('insurance_companies.update');
    Route::delete('/insurance-companies/{id}', [AdminDashboardController::class, 'deleteInsuranceCompany'])->name('insurance_companies.delete');
    
    // Reports
    Route::get('/reports', [AdminDashboardController::class, 'reports'])->name('reports');
    Route::get('/reports/export', [AdminDashboardController::class, 'exportReports'])->name('reports.export');
});

// Insurance Company Dashboard Routes
Route::middleware(['auth'])->prefix('insurance')->name('insurance_company.')->group(function () {
    Route::get('/dashboard', [InsuranceCompanyController::class, 'index'])->name('dashboard');
    Route::get('/mechanics', [InsuranceCompanyController::class, 'mechanics'])->name('mechanics');
    Route::get('/mechanics/create', [InsuranceCompanyController::class, 'createMechanic'])->name('mechanics.create');
    Route::post('/mechanics', [InsuranceCompanyController::class, 'storeMechanic'])->name('mechanics.store');
    Route::get('/mechanics/{id}', [InsuranceCompanyController::class, 'showMechanic'])->name('mechanics.show');
    Route::get('/mechanics/{id}/edit', [InsuranceCompanyController::class, 'editMechanic'])->name('mechanics.edit');
    Route::put('/mechanics/{id}', [InsuranceCompanyController::class, 'updateMechanic'])->name('mechanics.update');
    Route::delete('/mechanics/{id}', [InsuranceCompanyController::class, 'deleteMechanic'])->name('mechanics.delete');
    Route::get('/requests', [InsuranceCompanyController::class, 'requests'])->name('requests');
    Route::get('/requests/{id}', [InsuranceCompanyController::class, 'showRequest'])->name('request.show');
    Route::put('/requests/{id}/approve', [InsuranceCompanyController::class, 'approveRequest'])->name('request.approve');
    Route::put('/requests/{id}/reject', [InsuranceCompanyController::class, 'rejectRequest'])->name('request.reject');
    Route::get('/profile', [InsuranceCompanyController::class, 'profile'])->name('profile');
    Route::put('/profile', [InsuranceCompanyController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [InsuranceCompanyController::class, 'updatePassword'])->name('password.update');
});
