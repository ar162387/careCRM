<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'data_collection_setup_id',
        'average_type',
        'number_of_averages',
        'average_overlap_percentage',
        'window_type',
    ];

    public function dataCollectionSetup()
    {
        return $this->belongsTo(DataCollectionSetup::class);
    }
}
