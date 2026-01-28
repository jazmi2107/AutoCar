<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (auth()->user()->role == 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->role == 'user') {
            return redirect()->route('user.dashboard');
        } elseif (auth()->user()->role == 'mechanic') {
            return redirect()->route('mechanic.dashboard');
        } elseif (auth()->user()->role == 'insurance' || auth()->user()->role == 'insurance_company') {
            return redirect()->route('insurance_company.dashboard');
        }
        return view('home');
    }
}
