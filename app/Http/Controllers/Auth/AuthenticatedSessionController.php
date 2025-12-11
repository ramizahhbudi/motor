<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User; // 1. Import Model User
use App\Services\EncryptionService; // 2. Import Service Enkripsi

class AuthenticatedSessionController extends Controller
{
    protected $encryptionService;

    // 3. Inject service melalui constructor
    public function __construct(EncryptionService $encryptionService)
    {
        $this->encryptionService = $encryptionService;
    }

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
    public function store(LoginRequest $request): RedirectResponse
    {
        // 4. Tambahkan validasi untuk PIN
        $request->validate([
            'pin' => ['required', 'string', 'size:6'],
        ]);

        // 5. Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // 6. Lakukan proses dekripsi
        if ($user) {
            // Dekripsi password dari DB menggunakan PIN dari form login
            $decryptedPassword = $this->encryptionService->decrypt($user->password, $request->pin);

            // 7. Bandingkan password
            if ($decryptedPassword === $request->password) {
                // Jika cocok, login-kan user
                Auth::login($user, $request->boolean('remember'));
                $request->session()->regenerate();

                // Logika redirect berdasarkan role Anda
                $role = Auth::user()->role;
                switch ($role) {
                    case 'admin':
                        return redirect()->route('admin.dashboard');
                    case 'mekanik':
                        return redirect()->route('mechanic.dashboard');
                    default:
                        return redirect()->route('user_home');
                }
            }
        }

        // Jika user tidak ada atau password/PIN salah
        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
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