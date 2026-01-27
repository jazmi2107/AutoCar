<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\FirebaseUser;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = '/home';

    public function redirectTo()
    {
        if (auth()->check() && auth()->user()->hasRole('user')) {
            return route('user.dashboard');
        }
        return '/home';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        $insuranceCompanies = [];
        try {
            $database = app('firebase.database');
            $reference = $database->getReference('users');
            $snapshot = $reference->orderByChild('role')->equalTo('insurance_company')->getSnapshot();
            
            if ($snapshot->exists()) {
                foreach ($snapshot->getValue() as $uid => $data) {
                    if (isset($data['approval_status']) && $data['approval_status'] === 'approved') {
                        $company = (object) $data;
                        $company->id = $uid;
                        $insuranceCompanies[] = $company;
                    }
                }
            }
        } catch (\Exception $e) {
            // Log::error("Failed to fetch insurance companies: " . $e->getMessage());
        }
        
        return view('auth.register', compact('insuranceCompanies'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'role' => ['required', 'string', 'in:user,mechanic,insurance_company'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone_number' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
            
            // Driver specific
            'date_of_birth' => ['required_if:role,user,mechanic', 'nullable', 'date'],
            'vehicle_model' => ['required_if:role,user', 'nullable', 'string', 'max:255'],
            'plate_number' => ['required_if:role,user', 'nullable', 'string', 'max:20'],

            // Mechanic specific
            'license_number' => ['required_if:role,mechanic', 'nullable', 'string', 'max:50'],
            'years_of_experience' => ['required_if:role,mechanic', 'nullable', 'integer', 'min:0'],
            'insurance_company_id' => ['required_if:role,mechanic', 'nullable', 'string'],

            // Insurance Company specific
            'company_name' => ['required_if:role,insurance_company', 'nullable', 'string', 'max:255'],
            'registration_number' => ['required_if:role,insurance_company', 'nullable', 'string', 'max:50'],
            'website' => ['nullable', 'string', 'url', 'max:255'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\FirebaseUser
     */
    protected function create(array $data)
    {
        $auth = app('firebase.auth');
        $database = app('firebase.database');

        $userProperties = [
            'email' => $data['email'],
            'emailVerified' => false,
            'password' => $data['password'],
            'displayName' => $data['name'],
            'disabled' => false,
        ];

        try {
            $createdUser = $auth->createUser($userProperties);
        } catch (\Kreait\Firebase\Exception\Auth\EmailExists $e) {
             throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => ['The email has already been taken.'],
            ]);
        }

        $userData = [
            'role' => $data['role'],
            'email' => $data['email'],
            'name' => $data['name'],
            'phone_number' => $data['phone_number'],
            'address' => $data['address'],
            'created_at' => ['.sv' => 'timestamp'],
        ];

        if ($data['role'] === 'user') {
            $userData += [
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'vehicle_make' => $data['vehicle_make'] ?? null,
                'vehicle_model' => $data['vehicle_model'] ?? null,
                'plate_number' => $data['plate_number'] ?? null,
            ];
        } elseif ($data['role'] === 'mechanic') {
            $userData += [
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'license_number' => $data['license_number'] ?? null,
                'years_of_experience' => $data['years_of_experience'] ?? null,
                'insurance_company_id' => $data['insurance_company_id'] ?? null,
                'availability_status' => 'available',
                'approval_status' => 'pending',
            ];
        } elseif ($data['role'] === 'insurance_company') {
            $userData += [
                'company_name' => $data['company_name'] ?? null,
                'registration_number' => $data['registration_number'] ?? null,
                'website' => $data['website'] ?? null,
                'approval_status' => 'pending',
            ];
        }

        // Save to RTDB
        $database->getReference('users/' . $createdUser->uid)->set($userData);
        
        // Return FirebaseUser instance
        return new FirebaseUser($createdUser);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        event(new Registered($user));

        if ($request->role === 'mechanic' || $request->role === 'insurance_company') {
            return redirect($this->redirectPath())->with('status', 'Registration successful! Please wait for approval before logging in.');
        }

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return redirect($this->redirectPath());
    }
}
