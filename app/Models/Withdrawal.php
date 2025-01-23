<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    protected $table = 'tbl_withdrawals';

    protected $fillable = [
        'balance_id',
        'amount',
        'status',
        'admin_note',
        'transfer_proof'
    ];

    public function balance()
    {
        return $this->belongsTo(Balance::class, 'balance_id', 'id');
    }
}
