<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model untuk entitas Place (Tempat).
 * Digunakan untuk menyimpan informasi mengenai tempat yang tersedia di dalam destinasi.
 *
 * @property int $id
 * @property string $name Nama tempat.
 * @property string $slug Slug dari tempat.
 * @property string $description Deskripsi dari tempat.
 * @property string $open_time Jam buka tempat.
 * @property string $close_time Jam tutup tempat.
 * @property string $operational_status Status operasional tempat.
 * @property float $price Harga masuk tempat.
 * @property string $location Lokasi tempat.
 * @property string $type Tipe tempat (misal: wisata, kuliner, dll).
 * @property int $destination_id ID destinasi tempat ini berada.
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Place extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'tbl_places';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'open_time',
        'close_time',
        'operational_status',
        'price',
        'location',
        'type',
        'destination_id'
    ];

    /**
     * Mendefinisikan relasi dengan model AdminPlace.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function admin()
    {
        return $this->hasMany(AdminPlace::class, 'place_id');
    }

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
     * Mendefinisikan relasi dengan model Facility.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function facilities()
    {
        return $this->hasMany(Facility::class, 'item_id')->where('item_type', 'place');
    }

    /**
     * Mendefinisikan relasi dengan model GalleryPlace.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gallery()
    {
        return $this->hasMany(GalleryPlace::class, 'place_id');
    }

    /**
     * Mendefinisikan relasi dengan model Review.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'place_id');
    }

    /**
     * Mendefinisikan relasi dengan model Complaint.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'place_id');
    }

    /**
     * Mendefinisikan relasi dengan model Promo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function promos()
    {
        return $this->hasMany(Promo::class, 'place_id');
    }

    /**
     * Mengambil deskripsi tempat yang telah diterjemahkan.
     *
     * @return string
     */
    public function getTranslatedDescription()
    {
        $locale = app()->getLocale();

        if ($locale === 'id') {
            return $this->description;
        }

        return Cache::remember("translated_description_place_{$this->id}_{$locale}", now()->addDay(), function () use ($locale) {
            return GoogleTranslate::trans($this->description, $locale, 'id');
        });
    }

    /**
     * Mengambil nama tempat yang telah diterjemahkan.
     *
     * @return string
     */
    public function getTranslatedName()
    {
        $locale = app()->getLocale();

        if ($locale === 'id') {
            return ucwords($this->name);
        }

        return Cache::remember("translated_name_place_{$this->id}_{$locale}", now()->addDay(), function () use ($locale) {
            return ucwords(GoogleTranslate::trans($this->name, $locale, 'id'));
        });
    }

    /**
     * Mengambil tipe tempat yang telah diterjemahkan.
     *
     * @return string
     */
    public function getTranslatedType()
    {
        $locale = app()->getLocale();

        if ($locale === 'id') {
            return ucwords($this->type);
        }

        return Cache::remember("translated_type_place_{$this->id}_{$locale}", now()->addDay(), function () use ($locale) {
            return ucwords(GoogleTranslate::trans($this->type, $locale, 'id'));
        });
    }
}
