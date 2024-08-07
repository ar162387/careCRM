<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_name',
        'address',
        'city',
        'state',
        'zip',
        'country',
        'contact_name',
        'contact_title',
        'phone_number',
        'alt_phone_number',
        'fax_number',
        'email',
    ];
}
