<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model untuk entitas Bundle (Paket).
 * Digunakan untuk menyimpan informasi mengenai paket yang terdiri dari beberapa item.
 *
 * @property int $id
 * @property int $destination_id ID destinasi terkait dengan paket.
 * @property string $name Nama paket.
 * @property string $description Deskripsi dari paket.
 * @property float $total_price Total harga paket.
 * @property float $discount Diskon yang diberikan pada paket.
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Bundle extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'tbl_bundles';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = ['destination_id', 'name', 'description', 'total_price', 'discount'];

    /**
     * Mendefinisikan relasi dengan model BundleItem.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(BundleItem::class, 'bundle_id');
    }

    /**
     * Mendapatkan nama bundle yang telah diterjemahkan berdasarkan bahasa yang digunakan.
     * Jika bahasa yang digunakan adalah Bahasa Indonesia, maka menggunakan nama aslinya.
     *
     * @return string Nama bundle yang telah diterjemahkan atau asli.
     */
    public function getTranslatedName()
    {
        $locale = app()->getLocale();

        if ($locale === 'id') {
            return ucwords($this->name); // Kalau bahasa ID, pakai aslinya
        }

        return Cache::remember("translated_name_bundle_{$this->id}_{$locale}", now()->addDay(), function () use ($locale) {
            return ucwords(GoogleTranslate::trans($this->name, $locale, 'id'));
        });
    }
}
