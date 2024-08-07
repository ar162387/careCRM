<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class General extends Model
{
    use HasFactory;

    protected $fillable = ['data_collection_setup_id', 'cut_off_frequency', 'resolution', 'transducer_type', 'sensitivity', 'unit'];

    public function dataCollectionSetup()
    {
        return $this->belongsTo(DataCollectionSetup::class);
    }
}
