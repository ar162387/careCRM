<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demodulation extends Model
{
    use HasFactory;

    protected $fillable = [
        'data_collection_setup_id',
        'filter_type',
        'filter_value',
    ];

    public function dataCollectionSetup()
    {
        return $this->belongsTo(DataCollectionSetup::class);
    }
}
