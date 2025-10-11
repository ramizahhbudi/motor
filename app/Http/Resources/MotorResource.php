<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\JenisMotor;
use App\Models\KepemilikanMotor;

class MotorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'jenis_motor' => $this->jenisMotor->nama,
            'nomor_rangka' => $this->nomor_rangka,
            'nomor_mesin' => $this->nomor_mesin,
            'plat' => $this->plat,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
