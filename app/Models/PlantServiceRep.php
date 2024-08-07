<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlantServiceRep extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'plant_id',
        'service_rep_id',
    ];

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }

    public function service_representative()
    {
        return $this->belongsTo(ServiceRepresentative::class);
    }
}
