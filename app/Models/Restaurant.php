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
        'menu_images',
        'promo_description',
        'status',
    ];

    // Relasi ke tabel tbl_reviews
    public function reviews()
    {
        return $this->hasMany(Review::class, 'restaurant_id');
    }

    // Relasi ke tabel tbl_complaints
    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'restaurant_id');
    }

    // Relasi ke tabel tbl_admin_restaurants
    public function admin()
    {
        return $this->hasOne(AdminRestaurant::class, 'restaurant_id');
    }

    // Relasi ke tabel tbl_destinations
    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }
}
