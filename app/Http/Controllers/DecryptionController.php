<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Services\EncryptionService; // Import service

class DecryptionController extends Controller
{
    protected $encryptionService;

    // Inject service
    public function __construct(EncryptionService $encryptionService) {
        $this->encryptionService = $encryptionService;
    }

    // Fungsi untuk menampilkan halaman form
    public function showForm(): View {
        return view('user.lihat-data');
    }

    // Fungsi untuk memproses PIN dan dekripsi data
    public function decryptData(Request $request): RedirectResponse
    {
        $request->validate(['pin' => 'required|string|size:6']);
        $user = Auth::user();
        
        // Cek apakah PIN dari form cocok dengan PIN user
        if ($request->pin === $user->pin) {
            // 1. Dekripsi data untuk mendapatkan data asli
            $originalName = $this->encryptionService->decrypt($user->name, $user->pin);
            $originalPassword = $this->encryptionService->decrypt($user->password, $user->pin);
            
            // 2. Dapatkan semua langkah enkripsi dari data asli
            $nameSteps = $this->encryptionService->getAllEncryptionSteps($originalName, $user->pin);
            $passwordSteps = $this->encryptionService->getAllEncryptionSteps($originalPassword, $user->pin);

            // 3. Siapkan data untuk dikirim ke view
            $viewData = [
                'original' => ['name' => $originalName, 'password' => $originalPassword],
                'playfair' => ['name' => $nameSteps['playfair'], 'password' => $passwordSteps['playfair']],
                'caesar'   => ['name' => $nameSteps['caesar'], 'password' => $passwordSteps['caesar']],
                'vigenere' => ['name' => $user->name, 'password' => $user->password] // Final
            ];
            
            // 4. Redirect kembali ke halaman form dengan membawa data
            return Redirect::route('user.view_data.form')->with('view_data', $viewData);
        }

        // Jika PIN salah
        return back()->withErrors(['pin' => 'PIN yang Anda masukkan salah.']);
    }
}