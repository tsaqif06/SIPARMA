<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    use HasFactory;

    protected $table = 'tbl_rides';

    protected $fillable = [
        'destination_id',
        'name',
        'description',
        'price',
        'weekend_price',
        'children_price',
        'min_age',
        'min_height',
        'status',
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }
}
