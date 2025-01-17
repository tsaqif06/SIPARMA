<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminDestination extends Model
{
    use HasFactory;

    protected $table = 'tbl_admin_destinations';

    protected $fillable = [
        'user_id',
        'destination_id',
    ];

    // Relasi ke tabel tbl_users
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke tabel tbl_destinations
    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }
}
