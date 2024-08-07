<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataCollectionSetup extends Model
{
    use HasFactory;

    protected $fillable = ['setup_name'];

    public function general()
    {
        return $this->hasOne(General::class);
    }

    public function measurement()
    {
        return $this->hasOne(Measurement::class);
    }

    public function demodulation()
    {
        return $this->hasOne(Demodulation::class);
    }
}
