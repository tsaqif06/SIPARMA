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
        'account_number',
        'bank_name',
        'account_name'
    ];
    public function admin()
    {
        return $this->hasMany(AdminDestination::class, 'destination_id');
    }

    public function places()
    {
        return $this->hasMany(Place::class, 'destination_id');
    }

    public function facilities()
    {
        return $this->hasMany(Facility::class, 'item_id')->where('item_type', 'destination');
    }

    public function rides()
    {
        return $this->hasMany(Ride::class, 'destination_id');
    }

    public function gallery()
    {
        return $this->hasMany(GalleryDestination::class, 'destination_id');
    }

    public function balance()
    {
        return $this->hasOne(Balance::class, 'destination_id', 'id');
    }

    public function balanceLogs()
    {
        return $this->hasMany(BalanceLog::class, 'destination_id', 'id');
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

    public function promos()
    {
        return $this->hasMany(Promo::class, 'destination_id');
    }

    public function bundles()
    {
        return $this->hasMany(Bundle::class, 'destination_id');
    }
}
