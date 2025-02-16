<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminBalance extends Model
{
    use HasFactory;
    protected $table = 'tbl_admin_balance';
    protected $fillable = [
        'balance',
    ];
}
