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
        if ($user->hasRole('mechanic')) {
            if ($user->approval_status !== 'approved') {
                $this->guard()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('error', 'Your account is pending approval from the administrator.');
            }
        } elseif ($user->hasRole('insurance_company')) {
            if ($user->approval_status !== 'approved') {
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
        if (auth()->user()->hasRole('admin')) {
            return route('admin.dashboard');
        } elseif (auth()->user()->hasRole('user')) {
            return route('user.dashboard');
        } elseif (auth()->user()->hasRole('mechanic')) {
            return route('mechanic.dashboard');
        } elseif (auth()->user()->hasRole('insurance_company')) {
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
