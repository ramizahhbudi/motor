<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;
use App\Services\EncryptionService;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    protected $encryptionService;

    public function __construct(EncryptionService $encryptionService)
    {
        $this->encryptionService = $encryptionService;
    }

    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. Cek Blokir 10 Detik
        $request->ensureIsNotRateLimited();

        $request->validate(['pin' => ['required', 'string', 'size:6']]);

        // 2. Cari User Manual (Looping karena Email terenkripsi AES di DB)
        $users = User::all();
        $user = $users->first(function ($u) use ($request) {
            // Bandingkan email (ignore case sensitivity & spasi)
            return strtolower(trim($u->email)) === strtolower(trim($request->email));
        });

        // 3. Logika Verifikasi
        if ($user) {
            
            // A. Cek PIN (Menggunakan Hash karena di Model pin => 'hashed')
            $isPinValid = Hash::check($request->pin, $user->pin);

            // B. Cek Password (Menggunakan Dekripsi 3-Lapis Manual)
            $isPasswordValid = false;
            try {
                // Gunakan PIN inputan user untuk membuka password
                $decryptedPassword = $this->encryptionService->decrypt($user->password, $request->pin);
                
                if ($decryptedPassword === $request->password) {
                    $isPasswordValid = true;
                }
            } catch (\Exception $e) {
                $isPasswordValid = false;
            }

            // JIKA KEDUANYA BENAR
            if ($isPinValid && $isPasswordValid) {
                RateLimiter::clear($request->throttleKey());
                Auth::login($user, $request->boolean('remember'));
                $request->session()->regenerate();

                session(['auth_pin' => $request->pin]);
                
                // Redirect sesuai Role
                $role = Auth::user()->role;
                switch ($role) {
                    case 'admin': return redirect()->route('admin.dashboard');
                    case 'mekanik': return redirect()->route('mechanic.dashboard');
                    default: return redirect()->route('user_home');
                }
            }
        }

        // 4. Jika Gagal (Blokir 10 Detik)
        RateLimiter::hit($request->throttleKey(), 10);

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }


    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}