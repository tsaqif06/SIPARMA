<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $table = 'tbl_restaurants';

    protected $fillable = [
        'name',
        'description',
        'location',
        'destination_id',
        'status'
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }

    public function menus()
    {
        return $this->hasMany(RestaurantMenu::class, 'restaurant_id');
    }

    public function gallery()
    {
        return $this->hasMany(GalleryRestaurant::class, 'restaurant_id');
    }

    public function adminRestaurants()
    {
        return $this->hasMany(AdminRestaurant::class, 'restaurant_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'restaurant_id');
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'restaurant_id');
    }

    public function promo()
    {
        return $this->hasMany(Promo::class, 'restaurant_id');
    }

    public function facilities()
    {
        return $this->hasMany(Facility::class, 'item_id');
    }
}
