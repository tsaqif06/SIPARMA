<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    protected $table = 'tbl_destinations';

    protected $fillable = [
        'name',
        'type',
        'description',
        'location',
        'price',
        'open_time',
        'close_time',
        'operational_status',
        'weekday_price',
        'weekend_price'
    ];

    public function restaurants()
    {
        return $this->hasMany(Restaurant::class, 'destination_id');
    }

    public function adminDestinations()
    {
        return $this->hasMany(AdminDestination::class, 'destination_id');
    }

    public function gallery()
    {
        return $this->hasMany(GalleryDestination::class, 'destination_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'destination_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'destination_id');
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'destination_id');
    }

    public function promo()
    {
        return $this->hasMany(Promo::class, 'destination_id');
    }

    public function midtrans()
    {
        return $this->hasOne(Midtrans::class, 'destination_id');
    }
}
