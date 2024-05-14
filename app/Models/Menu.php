<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $fillable = ['title'];

    public function subMenus()
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }

    public function parentMenu()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function parentsOnly()
    {
        return self::where('parent_id', null)->with('subMenus')->get();
    }
}
