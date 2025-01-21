<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;
    protected $table = 'tbl_promo';
    protected $fillable = ['destination_id', 'place_id', 'discount', 'valid_from', 'valid_until'];
    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }

    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id');
    }
}
