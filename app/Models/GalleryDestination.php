<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryDestination extends Model
{
    use HasFactory;

    protected $table = 'tbl_gallery_destinations';
    protected $fillable = [
        'destination_id',
        'image_url',
        'image_type'
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }
}
