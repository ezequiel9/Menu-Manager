<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'menu_type_id',
    ];

    public function menuVariations()
    {
        return $this->hasMany(MenuVariation::class);
    }

    public function menuType()
    {
        return $this->belongsTo(MenuType::class);
    }


}
