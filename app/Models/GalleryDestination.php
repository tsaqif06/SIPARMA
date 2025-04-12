<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk entitas GalleryDestination (Galeri Destinasi).
 * Digunakan untuk menyimpan informasi gambar-gambar yang terkait dengan destinasi tertentu.
 *
 * @property int $id
 * @property int $destination_id ID destinasi yang terkait dengan gambar.
 * @property string $image_url URL gambar yang disimpan.
 * @property string $image_type Jenis gambar (misalnya: 'jpg', 'png', dll).
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class GalleryDestination extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'tbl_gallery_destinations';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = ['destination_id', 'image_url', 'image_type'];

    /**
     * Mendefinisikan relasi dengan model Destination.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }
}
