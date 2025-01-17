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
        'description',
        'location',
        'price',
        'open_time',
        'close_time',
        'operational_status',
        'weekday_price',
        'weekend_price',
    ];

    // Relasi ke tabel tbl_restaurants
    public function restaurants()
    {
        return $this->hasMany(Restaurant::class, 'destination_id');
    }

    // Relasi ke tabel tbl_transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'destination_id');
    }

    // Relasi ke tabel tbl_reviews
    public function reviews()
    {
        return $this->hasMany(Review::class, 'destination_id');
    }

    // Relasi ke tabel tbl_complaints
    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'destination_id');
    }

    // Relasi ke tabel tbl_admin_destinations
    public function admin()
    {
        return $this->hasOne(AdminDestination::class, 'destination_id');
    }

    // Relasi ke tabel tbl_midtrans
    public function midtrans()
    {
        return $this->hasOne(Midtrans::class, 'destination_id');
    }
}
