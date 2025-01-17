<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $table = 'tbl_promos';

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'discount_percentage',
        'destination_id',
        'restaurant_id',
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }
}
