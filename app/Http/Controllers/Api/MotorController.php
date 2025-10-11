<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MotorResource;
use App\Models\KepemilikanMotor;
use App\Models\JenisMotor;
use Illuminate\Http\Request;
use App\Http\Requests\MotorRequest;


class MotorController extends Controller
{
    /**
     * Tampilkan daftar motor pengguna.
     */
    public function index()
    {
        // Mengambil daftar motor yang dimiliki oleh pengguna yang sedang login
        $Data = KepemilikanMotor::with('jenisMotor')->paginate(5);
        return response()->json(["status" => "anjay gaming berhasil banget cuy", "data" => $Data]);
        
    }

    public function show(string $id)
    {
        $Data = KepemilikanMotor::with('jenisMotor')->find($id);
        return response()->json(["status"=>"pagiku cerahku", "data" => $Data]);
    }

    /**
     * Simpan motor baru.
     */
    public function store(MotorRequest $request)
    {
        $motor = KepemilikanMotor::create([
            'user_id' => 5, // Manually set to 5
            ...$request->validated(),
        ]);
    
        return response()->json([
            'status' => 'success',
            'message' => 'motor jelas tidak jelas',
            'data' => $motor
        ], 201);
    }
    

    /**
     * Update data motor.
     */
    public function update(Request $request, KepemilikanMotor $motor, $id)
    {
        $Data = KepemilikanMotor::with('jenisMotor')->find($id);
        $Data->update($request->all());
        return response()->json(["status"=>"akhirnya bisa pulang", "data" => $Data]);
    }

    /**
     * Hapus motor.
     */
    public function destroy(KepemilikanMotor $motor, $id)
    {
        // Authorize the delete action (if using policies)
        $motor = KepemilikanMotor::with('jenisMotor')->find($id);
    
        // Delete the motor instance
        $motor->delete();
    
        // Return a JSON response indicating success
        return response()->json([
            'status'  => 'success',
            'message' => 'menghilanglah kau wahai motor',
            'data' => $motor
        ]);
    }    
    

    /**
     * Restore motor yang dihapus (soft delete).
     */
    public function restore($id)
    {
        $motor = KepemilikanMotor::withTrashed()->findOrFail($id);

        if ($motor->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $motor->restore();
        return new MotorResource($motor);
    }
}
