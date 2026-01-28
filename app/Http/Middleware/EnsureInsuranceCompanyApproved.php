<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\InsuranceCompany;
use Symfony\Component\HttpFoundation\Response;

class EnsureInsuranceCompanyApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Check if user is an insurance company
        if ($user && $user->role === 'insurance_company') {
            $insuranceCompany = InsuranceCompany::where('user_id', $user->id)->first();

            if (!$insuranceCompany) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->withErrors(['email' => 'Insurance company profile not found.']);
            }

            // Check approval status
            if ($insuranceCompany->approval_status === 'pending') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->withErrors(['email' => 'Your account is pending approval. Please wait for admin approval.']);
            }

            if ($insuranceCompany->approval_status === 'rejected') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->withErrors(['email' => 'Your account has been rejected. Reason: ' . $insuranceCompany->rejection_reason]);
            }
        }

        return $next($request);
    }
}
