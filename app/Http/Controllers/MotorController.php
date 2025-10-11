<?php

namespace App\Http\Controllers;

use App\Models\KepemilikanMotor;
use App\Models\JenisMotor;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;

class MotorController extends Controller
{
    /**
     * Tampilkan daftar motor pengguna.
     */
    public function index()
    {
        $motors = KepemilikanMotor::with('jenisMotor')
            ->where('user_id', auth()->id())
            ->get();

        return view('user.motor', compact('motors'));
    }

    /**
     * Halaman tambah motor.
     */
    public function create()
    {
        $jenisMotors = JenisMotor::all();
        return view('user.createmotor', compact('jenisMotors'));
    }

    /**
     * Simpan motor baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_motor_id' => 'required|exists:jenis_motors,id',
            'nomor_rangka' => 'required|unique:kepemilikan_motors',
            'nomor_mesin' => 'required|unique:kepemilikan_motors',
            'plat' => 'required|unique:kepemilikan_motors',
        ]);

        KepemilikanMotor::create([
            'user_id' => auth()->id(),
            'jenis_motor_id' => $request->jenis_motor_id,
            'nomor_rangka' => $request->nomor_rangka,
            'nomor_mesin' => $request->nomor_mesin,
            'plat' => $request->plat,
        ]);

        return redirect()->route('motor.index')->with('success', 'Motor berhasil ditambahkan.');
    }

    /**
     * Halaman edit motor.
     */
    public function edit(KepemilikanMotor $motor)
    {
        // Pastikan motor hanya bisa diakses oleh pemiliknya
        if ($motor->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $jenisMotors = JenisMotor::all();
        return view('user.editmotor', compact('motor', 'jenisMotors'));
    }

    /**
     * Update data motor.
     */
    public function update(Request $request, KepemilikanMotor $motor)
    {
        // Pastikan motor hanya bisa diakses oleh pemiliknya
        if ($motor->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'jenis_motor_id' => 'required|exists:jenis_motors,id',
            'nomor_rangka' => 'required|unique:kepemilikan_motors,nomor_rangka,' . $motor->id,
            'nomor_mesin' => 'required|unique:kepemilikan_motors,nomor_mesin,' . $motor->id,
            'plat' => 'required|unique:kepemilikan_motors,plat,' . $motor->id,
        ]);

        $motor->update([
            'jenis_motor_id' => $request->jenis_motor_id,
            'nomor_rangka' => $request->nomor_rangka,
            'nomor_mesin' => $request->nomor_mesin,
            'plat' => $request->plat,
        ]);

        return redirect()->route('motor.index')->with('success', 'Motor berhasil diperbarui.');
    }

    /**
     * Hapus motor.
     */
    public function destroy(KepemilikanMotor $motor)
    {
        if ($motor->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
    
        $motor->delete(); // Ini akan melakukan soft delete
        return redirect()->route('motor.index')->with('success', 'Motor berhasil dihapus.');
    }

    public function restore(KepemilikanMotor $motor, $id)
    {
        // Cari motor yang sudah dihapus (soft deleted)
        $motor = KepemilikanMotor::withTrashed()->find($motor->id);
    
        // Restore data 
        $motor->restore();
    
        return redirect()->route('motor.index')->with('success', 'Motor berhasil dikembalikan.');
    }
    // $motor = KepemilikanMotor::withTrashed()->find(3);

    // // Restore data
    // $motor->restore();

    // // Konfirmasi bahwa data sudah dikembalikan
    // KepemilikanMotor::find(3);
    
    
}
