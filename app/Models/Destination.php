<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model untuk entitas Destination (Destinasi).
 * Digunakan untuk menyimpan informasi terkait destinasi wisata atau tempat menarik.
 *
 * @property int $id
 * @property string $name Nama destinasi.
 * @property string $slug Slug destinasi untuk URL.
 * @property string $type Tipe destinasi (misalnya: wisata alam, wisata budaya, dll).
 * @property string $description Deskripsi destinasi.
 * @property string $location Lokasi destinasi.
 * @property string $open_time Jam buka destinasi.
 * @property string $close_time Jam tutup destinasi.
 * @property string $operational_status Status operasional destinasi (misalnya: open, closed, sementara tutup).
 * @property float $price Harga tiket untuk destinasi.
 * @property float $weekend_price Harga tiket untuk akhir pekan.
 * @property float $children_price Harga tiket untuk anak-anak.
 * @property string $account_number Nomor rekening destinasi.
 * @property string $bank_name Nama bank destinasi.
 * @property string $account_name Nama pemegang rekening destinasi.
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Destination extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'tbl_destinations';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'type',
        'description',
        'location',
        'open_time',
        'close_time',
        'operational_status',
        'price',
        'weekend_price',
        'children_price',
        'account_number',
        'bank_name',
        'account_name'
    ];

    /**
     * Mendefinisikan relasi dengan model AdminDestination.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function admin()
    {
        return $this->hasMany(AdminDestination::class, 'destination_id');
    }

    /**
     * Mendefinisikan relasi dengan model Place.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function places()
    {
        return $this->hasMany(Place::class, 'destination_id');
    }

    /**
     * Mendefinisikan relasi dengan model Facility untuk fasilitas terkait destinasi.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function facilities()
    {
        return $this->hasMany(Facility::class, 'item_id')->where('item_type', 'destination');
    }

    /**
     * Mendefinisikan relasi dengan model Ride untuk wahana yang tersedia di destinasi.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rides()
    {
        return $this->hasMany(Ride::class, 'destination_id');
    }

    /**
     * Mendefinisikan relasi dengan model GalleryDestination untuk galeri foto destinasi.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gallery()
    {
        return $this->hasMany(GalleryDestination::class, 'destination_id');
    }

    /**
     * Mendefinisikan relasi dengan model Balance untuk saldo terkait destinasi.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function balance()
    {
        return $this->hasOne(Balance::class, 'destination_id', 'id');
    }

    /**
     * Mendefinisikan relasi dengan model BalanceLog untuk riwayat saldo destinasi.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function balanceLogs()
    {
        return $this->hasMany(BalanceLog::class, 'destination_id', 'id');
    }

    /**
     * Mendefinisikan relasi dengan model Transaction untuk transaksi terkait destinasi.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'destination_id');
    }

    /**
     * Mendefinisikan relasi dengan model Review untuk ulasan terkait destinasi.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'destination_id');
    }

    /**
     * Mendefinisikan relasi dengan model Complaint untuk keluhan terkait destinasi.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'destination_id');
    }

    /**
     * Mendefinisikan relasi dengan model Promo untuk promo yang berlaku di destinasi.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function promos()
    {
        return $this->hasMany(Promo::class, 'destination_id');
    }

    /**
     * Mendefinisikan relasi dengan model Bundle untuk paket bundling yang terkait destinasi.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bundles()
    {
        return $this->hasMany(Bundle::class, 'destination_id');
    }

    /**
     * Mendapatkan deskripsi destinasi yang sudah diterjemahkan berdasarkan bahasa yang dipilih.
     *
     * @return string
     */
    public function getTranslatedDescription()
    {
        $locale = app()->getLocale();

        if ($locale === 'id') {
            return $this->description;
        }

        return Cache::remember("translated_description_destination_{$this->id}_{$locale}", now()->addDay(), function () use ($locale) {
            return GoogleTranslate::trans($this->description, $locale, 'id');
        });
    }

    /**
     * Mendapatkan nama destinasi yang sudah diterjemahkan berdasarkan bahasa yang dipilih.
     *
     * @return string
     */
    public function getTranslatedName()
    {
        $locale = app()->getLocale();

        if ($locale === 'id') {
            return ucwords($this->name); // Kalau bahasa ID, pakai aslinya
        }

        return Cache::remember("translated_name_destination_{$this->id}_{$locale}", now()->addDay(), function () use ($locale) {
            return ucwords(GoogleTranslate::trans($this->name, $locale, 'id'));
        });
    }
}
