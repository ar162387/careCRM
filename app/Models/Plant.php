<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'company_id',
        'status',
    ];

    public function note()
    {
        return $this->belongsTo(Note::class);
    }
}
