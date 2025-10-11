<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceSpecification extends Model
{
    use HasFactory;

    protected $fillable = ['service_id', 'name', 'price', 'stock'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
