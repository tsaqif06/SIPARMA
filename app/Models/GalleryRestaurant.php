<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryRestaurant extends Model
{
    use HasFactory;

    protected $table = 'tbl_gallery_restaurants';
    protected $fillable = [
        'restaurant_id',
        'image_url',
        'image_type'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }
}
