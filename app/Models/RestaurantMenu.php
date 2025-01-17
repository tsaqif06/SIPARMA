<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantMenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'menu_name',
        'menu_description',
        'menu_price'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }
}
