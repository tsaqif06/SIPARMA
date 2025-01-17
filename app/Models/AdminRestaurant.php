<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminRestaurant extends Model
{
    use HasFactory;

    protected $table = 'tbl_admin_restaurants';

    protected $fillable = [
        'user_id',
        'restaurant_id',
        'approval_status',
        'ownership_docs',
    ];

    // Relasi ke tabel tbl_users
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke tabel tbl_restaurants
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }
}
