<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryRide extends Model
{
    use HasFactory;
    protected $table = 'tbl_gallery_rides';
    protected $fillable = ['ride_id', 'image_url'];
    public function ride()
    {
        return $this->belongsTo(Ride::class, 'ride_id');
    }
}
