<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    use HasFactory;
    protected $table = 'tbl_rides';
    protected $fillable = ['destination_id', 'name', 'slug', 'description', 'open_time', 'close_time', 'operational_status', 'price', 'weekend_price', 'children_price', 'min_age', 'min_height'];
    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }

    public function gallery()
    {
        return $this->hasMany(GalleryRide::class, 'ride_id');
    }
}
