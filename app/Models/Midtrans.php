<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Midtrans extends Model
{
    use HasFactory;

    protected $table = 'tbl_midtrans';

    protected $fillable = [
        'destination_id',
        'client_key',
        'server_key',
        'is_active',
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }
}
