<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bundle extends Model
{
    use HasFactory;
    protected $table = 'tbl_bundles';
    protected $fillable = ['destination_id', 'name', 'description', 'total_price', 'discount'];
    public function items()
    {
        return $this->hasMany(BundleItem::class, 'bundle_id');
    }

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
