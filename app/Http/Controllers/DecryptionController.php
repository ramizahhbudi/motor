<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash; // <--- JANGAN LUPA IMPORT INI
use App\Services\EncryptionService;

class DecryptionController extends Controller
{
    protected $encryptionService;

    public function __construct(EncryptionService $encryptionService) {
        $this->encryptionService = $encryptionService;
    }

    public function showForm(): View {
        return view('user.lihat-data');
    }

    public function decryptData(Request $request): RedirectResponse
    {
        $request->validate(['pin' => 'required|string|size:6']);
        $user = Auth::user();
        
        // PERBAIKAN 1: Gunakan Hash::check, bukan ===
        if (Hash::check($request->pin, $user->pin)) {
            
            // PERBAIKAN 2: Gunakan $request->pin sebagai Key untuk decrypt
            // (Karena data dienkripsi pakai PIN angka asli, bukan PIN hash)
            $pinAsli = $request->pin; 

            try {
                // 1. Dekripsi data pakai PIN Asli
                $originalName = $this->encryptionService->decrypt($user->name, $pinAsli);
                $originalPassword = $this->encryptionService->decrypt($user->password, $pinAsli);
                
                // 2. Dapatkan langkah enkripsi
                $nameSteps = $this->encryptionService->getAllEncryptionSteps($originalName, $pinAsli);
                $passwordSteps = $this->encryptionService->getAllEncryptionSteps($originalPassword, $pinAsli);

                // 3. Siapkan data view
                $viewData = [
                    'name' => [
                        'original' => $originalName,
                        'playfair' => $nameSteps['playfair'],
                        'caesar'   => $nameSteps['caesar'],
                        'vigenere' => $user->name 
                    ],
                    'password' => [
                        'original' => '********', 
                        'playfair' => $passwordSteps['playfair'], 
                        'caesar'   => $passwordSteps['caesar'],
                        'vigenere' => $user->password
                    ],
                    'email' => $user->email, 
                    'phone' => $user->phone ?? '-' 
                ];
                
                return Redirect::route('user.view_data.form')->with('view_data', $viewData);

            } catch (\Exception $e) {
                // Jaga-jaga jika proses dekripsi gagal (misal data lama tidak sinkron)
                return back()->withErrors(['pin' => 'Terjadi kesalahan saat mendekripsi data.']);
            }
        }

        // Jika PIN salah
        return back()->withErrors(['pin' => 'PIN yang Anda masukkan salah.']);
    }
}