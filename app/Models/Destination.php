<?php

namespace App\Models;

use App\Models\Ride;
use App\Models\Promo;
use App\Models\Review;
use App\Models\Midtrans;
use App\Models\Complaint;
use App\Models\Restaurant;
use App\Models\Transaction;
use App\Models\AdminDestination;
use App\Models\GalleryDestination;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Destination extends Model
{
    use HasFactory;

    protected $table = 'tbl_destinations';

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

    public function ride()
    {
        return $this->hasMany(Ride::class, 'destination_id');
    }

    public function facilities()
    {
        return $this->hasMany(Facility::class, 'item_id');
    }
}
