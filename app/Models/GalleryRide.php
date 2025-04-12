<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk entitas GalleryRide (Galeri Ride).
 * Digunakan untuk menyimpan informasi gambar-gambar yang terkait dengan ride tertentu.
 *
 * @property int $id
 * @property int $ride_id ID ride yang terkait dengan gambar.
 * @property string $image_url URL gambar yang disimpan.
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class GalleryRide extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'tbl_gallery_rides';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = ['ride_id', 'image_url'];

    /**
     * Mendefinisikan relasi dengan model Ride.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ride()
    {
        return $this->belongsTo(Ride::class, 'ride_id');
    }
}
