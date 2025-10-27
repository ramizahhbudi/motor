<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Services\EncryptionService; // 1. Import Service Enkripsi

class RegisteredUserController extends Controller
{
    protected $encryptionService;

    // 2. Inject service melalui constructor
    public function __construct(EncryptionService $encryptionService)
    {
        $this->encryptionService = $encryptionService;
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 3. Sesuaikan validasi PIN
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', 'string', 'max:15', 'unique:users'], // Field Anda dipertahankan
            
            // PIN diubah menjadi 6 karakter, alfanumerik (huruf+angka), dan dikonfirmasi
            'pin' => ['required', 'string', 'alpha_num', 'size:6', 'confirmed'] 
        ]);

        $pin = $request->pin;

        // 4. Lakukan enkripsi sebelum menyimpan
        $encryptedName = $this->encryptionService->encrypt($request->name, $pin);
        $encryptedPassword = $this->encryptionService->encrypt($request->password, $pin);

        // 5. Buat user baru dengan data yang sudah dienkripsi
        $user = User::create([
            'name' => $encryptedName, // Simpan nama terenkripsi
            'email' => $request->email,
            'password' => $encryptedPassword, // Simpan password terenkripsi
            'phone' => $request->phone, // Field Anda dipertahankan
            'pin' => $pin, // Simpan PIN (tidak dienkripsi)
        ]);

        event(new Registered($user));

        // Redirect ke login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Registration successful! Please login to continue.');
    }
}