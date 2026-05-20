<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Company;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming Admin authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Handle an incoming Client authentication request.
     */
    public function clientSignIn(LoginRequest $request): RedirectResponse
    {
        $user = User::where('email',$request->email)->first();
        if($user != null && $user->status == '1'){

            $company = Company::where('user_id',$user->id)->first();
            if($company != null && $company->status == '1') {
                $request->authenticate();
                $request->session()->regenerate();
                return redirect()->intended(RouteServiceProvider::HOMIE);
            }else{
                return back()->withErrors(['error' => 'Not Authorised!']);
            }

        } else {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
            // return redirect()->back()->with('error','Unauthorized Access');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
