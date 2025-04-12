<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk entitas GalleryPlace (Galeri Tempat).
 * Digunakan untuk menyimpan informasi gambar-gambar yang terkait dengan tempat tertentu.
 *
 * @property int $id
 * @property int $place_id ID tempat yang terkait dengan gambar.
 * @property string $image_url URL gambar yang disimpan.
 * @property string $image_type Jenis gambar (misalnya: 'jpg', 'png', dll).
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class GalleryPlace extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'tbl_gallery_places';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = ['place_id', 'image_url', 'image_type'];

    /**
     * Mendefinisikan relasi dengan model Place.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id');
    }
}
