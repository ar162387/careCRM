<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'plant_id',
        'status'
    ];

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }

    public function components()
    {
        return $this->hasMany(Component::class);
    }
}
