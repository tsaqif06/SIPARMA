<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model untuk entitas Facility (Fasilitas).
 * Digunakan untuk menyimpan informasi terkait fasilitas yang tersedia di destinasi atau tempat.
 *
 * @property int $id
 * @property string $item_type Tipe item, apakah destinasi atau tempat (misalnya: 'destination', 'place').
 * @property int $item_id ID item (destinasi atau tempat) yang terkait dengan fasilitas.
 * @property string $name Nama fasilitas.
 * @property string $description Deskripsi fasilitas.
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Facility extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'tbl_facilities';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = ['item_type', 'item_id', 'name', 'description'];

    /**
     * Mendefinisikan relasi dengan model Destination untuk item tipe 'destination'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function destination()
    {
        return $this->belongsTo(Destination::class, 'item_id');
    }

    /**
     * Mendefinisikan relasi dengan model Place untuk item tipe 'place'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function place()
    {
        return $this->belongsTo(Place::class, 'item_id');
    }

    /**
     * Mendapatkan nama fasilitas yang sudah diterjemahkan berdasarkan bahasa yang dipilih.
     *
     * @return string
     */
    public function getTranslatedName()
    {
        $locale = app()->getLocale();

        if ($locale === 'id') {
            return ucwords($this->name); // Kalau bahasa ID, pakai aslinya
        }

        return Cache::remember("translated_name_facility_{$this->id}_{$locale}", now()->addDay(), function () use ($locale) {
            return ucwords(GoogleTranslate::trans($this->name, $locale, 'id'));
        });
    }
}
