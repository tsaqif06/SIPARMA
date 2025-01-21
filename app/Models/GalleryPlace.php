<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryPlace extends Model
{
    use HasFactory;
    protected $table = 'tbl_gallery_places';
    protected $fillable = ['place_id', 'image_url', 'image_type'];
    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id');
    }
}
