<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuVariation extends Model
{
    use HasFactory;

    protected $fillable = [
        'details',
        'menu_id'
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
