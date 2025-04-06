<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Facility extends Model
{
    use HasFactory;
    protected $table = 'tbl_facilities';
    protected $fillable = ['item_type', 'item_id', 'name', 'description'];

    public function destination()
    {
        return $this->belongsTo(Destination::class, 'item_id');
    }

    public function place()
    {
        return $this->belongsTo(Place::class, 'item_id');
    }

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
