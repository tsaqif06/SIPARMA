<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    use HasFactory;
    protected $table = 'tbl_balance';
    protected $fillable = [
        'destination_id',
        'balance',
        'total_profit'
    ];
    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id', 'id');
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class, 'balance_id', 'id');
    }
}
