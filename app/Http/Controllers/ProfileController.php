<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use App\Services\EncryptionService;

class ProfileController extends Controller
{
    protected $encryptionService;

    public function __construct(EncryptionService $encryptionService)
    {
        $this->encryptionService = $encryptionService;
    }

    public function edit(Request $request): View
    {
        $user = $request->user();
        $pin = session('auth_pin');
        $displayName = $user->name;

        if ($pin) {
            try {
                $displayName = $this->encryptionService->decrypt($user->name, $pin);
            } catch (\Exception $e) {}
        }

        return view('profile.edit', [
            'user' => $user,
            'decryptedName' => $displayName,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'], 
            'phone' => ['nullable', 'string', 'max:15'],
            'pin'   => ['required', 'string', 'size:6'],
        ]);

        $user = $request->user();

        // 1. Cek PIN
        if (!Hash::check($request->pin, $user->pin)) {
            return back()->withErrors(['pin' => 'PIN salah! Perubahan ditolak.']);
        }

        // 2. Enkripsi Nama Baru
        $encryptedName = $this->encryptionService->encrypt($request->name, $request->pin);

        // 3. Simpan
        $user->name = $encryptedName;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // [UBAH DISINI] Tambahkan 'with status'
        return Redirect::route('profile.edit')->with('status', 'Profil berhasil diperbarui!'); 
    }

    public function editPassword(Request $request): View
    {
        return view('profile.change-password', [
            'user' => $request->user(),
        ]);
    }
    public function updatePassword(Request $request): RedirectResponse
    {
        // Validasi
        $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
            'pin' => ['required', 'string', 'size:6'],
        ]);

        $user = $request->user();

        // 1. Verifikasi PIN Database (Hash Check)
        if (!Hash::check($request->pin, $user->pin)) {
            return back()->withErrors(['pin' => 'PIN salah! Gagal mengganti password.']);
        }

        // 2. Enkripsi Password Baru (3-Lapis)
        $encryptedPassword = $this->encryptionService->encrypt($request->password, $request->pin);

        // 3. Update Data
        $user->update([
            'password' => $encryptedPassword,
        ]);

        // [UBAH DISINI] Redirect kembali ke halaman password (bukan profile)
        return Redirect::route('password.edit')->with('status', 'Password berhasil diubah dan diamankan.');
    }
    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // 1. Validasi Input PIN (Bukan Password, karena sistem Anda berbasis PIN)
        $request->validate([
            'pin' => ['required', 'string', 'size:6'],
        ]);

        $user = $request->user();

        // 2. Verifikasi PIN Database (Hash)
        if (!Hash::check($request->pin, $user->pin)) {
            return back()->withErrors(['pin_deletion' => 'PIN salah! Gagal menghapus akun.']);
        }

        // 3. Proses Logout & Hapus
        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}