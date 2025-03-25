<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Place extends Model
{
    use HasFactory;
    protected $table = 'tbl_places';
    protected $fillable = ['name', 'slug', 'description', 'open_time', 'close_time', 'operational_status', 'price', 'location', 'type', 'destination_id'];
    public function admin()
    {
        return $this->hasMany(AdminPlace::class, 'place_id');
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }

    public function facilities()
    {
        return $this->hasMany(Facility::class, 'item_id')->where('item_type', 'place');
    }

    public function gallery()
    {
        return $this->hasMany(GalleryPlace::class, 'place_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'place_id');
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'place_id');
    }

    public function promos()
    {
        return $this->hasMany(Promo::class, 'place_id');
    }

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

    public function getTranslatedType()
    {
        $locale = app()->getLocale();

        if ($locale === 'id') {
            return ucwords($this->type); // Kalau bahasa ID, pakai aslinya
        }

        return Cache::remember("translated_type_place_{$this->id}_{$locale}", now()->addDay(), function () use ($locale) {
            return ucwords(GoogleTranslate::trans($this->type, $locale, 'id'));
        });
    }
}
