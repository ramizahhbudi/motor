<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;

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
     * Handle an incoming authentication request.
     */
    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     $request->authenticate();

    //     $request->session()->regenerate();

    //     // Check user role and redirect accordingly
    //     $role = Auth::user()->role;

    //     switch ($role) {
    //         case 'admin':
    //             return redirect()->route('admin.dashboard');
    //         case 'mekanik':
    //             return redirect()->route('mechanic.dashboard');
    //         case 'user':
    //         default:
    //             return redirect()->route('user_home');
    //     }
    // }

    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. Find the user by their email
        $user = User::where('email', $request->email)->first();

        // 2. Check if user exists and if the plain text password matches
        if ($user && $user->password === $request->password) {
            
            // 3. Manually log the user in
            Auth::login($user);
            
            // 4. Regenerate the session ID for security
            $request->session()->regenerate();

            // 5. Check user role and redirect accordingly
            $role = $user->role; // Get role from the user object

            switch ($role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'mekanik':
                    return redirect()->route('mechanic.dashboard');
                case 'user':
                default:
                    return redirect()->route('user_home');
            }
        }

        // If the check fails, return with an error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
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
