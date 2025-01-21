<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $table = 'tbl_facilities';

    protected $fillable = [
        'item_type',
        'item_id',
        'name',
        'description'
    ];

    /**
     * Mendapatkan model yang terkait dengan fasilitas ini (Destination atau Restaurant)
     */
    public function item()
    {
        // Relasi berdasarkan item_type dan item_id
        return $this->morphTo();
    }

    /**
     * Mendapatkan destinasi terkait dengan fasilitas ini
     */
    public function destination()
    {
        return $this->belongsTo(Destination::class, 'item_id');
    }

    /**
     * Mendapatkan restoran terkait dengan fasilitas ini
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'item_id');
    }
}
