<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Driver;
use App\Models\Mechanic;
use App\Models\InsuranceCompany;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
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
        if (auth()->check() && auth()->user()->role == 'user') {
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
        $insuranceCompanies = InsuranceCompany::where('approval_status', 'approved')->get();
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
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
            'insurance_company_id' => ['required_if:role,mechanic', 'nullable', 'exists:insurance_companies,id'],

            // Insurance Company specific
            'company_name' => ['required_if:role,insurance_company', 'nullable', 'string', 'max:255'],
            'registration_number' => ['required_if:role,insurance_company', 'nullable', 'string', 'max:50', 'unique:insurance_companies'],
            'website' => ['nullable', 'string', 'url', 'max:255'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'], // For insurance, this might be company name or contact person. Let's use the input name.
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        if ($data['role'] === 'user') {
            Driver::create([
                'user_id' => $user->id,
                'phone_number' => $data['phone_number'],
                'address' => $data['address'],
                'date_of_birth' => $data['date_of_birth'],
                'vehicle_make' => $data['vehicle_make'],
                'vehicle_model' => $data['vehicle_model'],
                'plate_number' => $data['plate_number'],
            ]);
        } elseif ($data['role'] === 'mechanic') {
            Mechanic::create([
                'user_id' => $user->id,
                'phone_number' => $data['phone_number'],
                'address' => $data['address'],
                'date_of_birth' => $data['date_of_birth'],
                'license_number' => $data['license_number'],
                'years_of_experience' => $data['years_of_experience'],
                'insurance_company_id' => $data['insurance_company_id'],
                'availability_status' => 'available',
                'approval_status' => 'pending',
            ]);
        } elseif ($data['role'] === 'insurance_company') {
            InsuranceCompany::create([
                'user_id' => $user->id,
                'company_name' => $data['company_name'],
                'registration_number' => $data['registration_number'],
                'phone_number' => $data['phone_number'],
                'address' => $data['address'],
                'website' => $data['website'] ?? null,
                'approval_status' => 'pending',
            ]);
        }

        return $user;
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

        event(new Registered($user = $this->create($request->all())));

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
