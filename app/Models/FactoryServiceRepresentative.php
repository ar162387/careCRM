<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FactoryServiceRepresentative extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['factory_id', 'service_representative_id'];

    public function factory()
    {
        return $this->belongsTo(Factory::class);
    }

    public function service()
    {
        return $this->belongsTo(ServiceRepresentative::class);
    }
}
