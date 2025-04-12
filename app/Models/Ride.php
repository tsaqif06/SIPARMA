<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model untuk entitas Ride (Wahana).
 * Digunakan untuk menyimpan informasi mengenai wahana yang tersedia di destinasi.
 *
 * @property int $id
 * @property int $destination_id ID destinasi tempat wahana berada.
 * @property string $name Nama wahana.
 * @property string $slug Slug yang digunakan dalam URL.
 * @property string $description Deskripsi wahana.
 * @property string $open_time Jam buka wahana.
 * @property string $close_time Jam tutup wahana.
 * @property string $operational_status Status operasional wahana.
 * @property float $price Harga wahana untuk pengunjung dewasa.
 * @property float $weekend_price Harga wahana untuk pengunjung di akhir pekan.
 * @property float $children_price Harga wahana untuk pengunjung anak-anak.
 * @property int $min_age Usia minimal untuk dapat menaiki wahana.
 * @property int $min_height Tinggi badan minimal untuk dapat menaiki wahana.
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Ride extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'tbl_rides';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = ['destination_id', 'name', 'slug', 'description', 'open_time', 'close_time', 'operational_status', 'price', 'weekend_price', 'children_price', 'min_age', 'min_height'];

    /**
     * Mendefinisikan relasi dengan model Destination.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }

    /**
     * Mendefinisikan relasi dengan model GalleryRide.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gallery()
    {
        return $this->hasMany(GalleryRide::class, 'ride_id');
    }

    /**
     * Mendapatkan nama wahana yang diterjemahkan berdasarkan lokal.
     *
     * @return string
     */
    public function getTranslatedName()
    {
        $locale = app()->getLocale();

        if ($locale === 'id') {
            return ucwords($this->name); // Jika bahasa ID, gunakan nama aslinya
        }

        return Cache::remember("translated_name_ride_{$this->id}_{$locale}", now()->addDay(), function () use ($locale) {
            return ucwords(GoogleTranslate::trans($this->name, $locale, 'id'));
        });
    }
}
