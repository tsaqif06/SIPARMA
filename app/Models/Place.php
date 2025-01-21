<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;
    protected $table = 'tbl_places';
    protected $fillable = ['name', 'slug', 'description', 'open_time', 'close_time', 'operational_status', 'price', 'location', 'type', 'destination_id'];
    public function admin()
    {
        return $this->hasMany(AdminPlace::class, 'place_id');
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }

    public function facilities()
    {
        return $this->hasMany(Facility::class, 'item_id')->where('item_type', 'restaurant');
    }

    public function gallery()
    {
        return $this->hasMany(GalleryPlace::class, 'place_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'place_id');
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'place_id');
    }

    public function promos()
    {
        return $this->hasMany(Promo::class, 'place_id');
    }
}
