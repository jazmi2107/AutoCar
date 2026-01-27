<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->role == 'mechanic') {
            $mechanic = $user->mechanic;
            if ($mechanic && $mechanic->approval_status !== 'approved') {
                $this->guard()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('error', 'Your account is pending approval from the administrator.');
            }
        } elseif ($user->role == 'insurance_company') {
            $company = $user->insuranceCompany;
            if ($company && $company->approval_status !== 'approved') {
                $this->guard()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('error', 'Your account is pending approval from the administrator.');
            }
        }
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/home';

    protected function redirectTo()
    {
        if (auth()->user()->role == 'admin') {
            return route('admin.dashboard');
        } elseif (auth()->user()->role == 'user') {
            return route('user.dashboard');
        } elseif (auth()->user()->role == 'mechanic') {
            return route('mechanic.dashboard');
        } elseif (auth()->user()->role == 'insurance_company') {
            return route('insurance_company.dashboard');
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
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
