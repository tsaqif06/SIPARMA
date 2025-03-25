<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ride extends Model
{
    use HasFactory;
    protected $table = 'tbl_rides';
    protected $fillable = ['destination_id', 'name', 'slug', 'description', 'open_time', 'close_time', 'operational_status', 'price', 'weekend_price', 'children_price', 'min_age', 'min_height'];
    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }

    public function gallery()
    {
        return $this->hasMany(GalleryRide::class, 'ride_id');
    }

    public function getTranslatedName()
    {
        $locale = app()->getLocale();

        if ($locale === 'id') {
            return ucwords($this->name); // Kalau bahasa ID, pakai aslinya
        }

        return Cache::remember("translated_name_ride_{$this->id}_{$locale}", now()->addDay(), function () use ($locale) {
            return ucwords(GoogleTranslate::trans($this->name, $locale, 'id'));
        });
    }
}
