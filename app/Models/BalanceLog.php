<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceLog extends Model
{
    use HasFactory;

    protected $table = 'tbl_balance_logs';

    protected $fillable = [
        'destination_id',
        'profit',
        'period_year',
        'period_month'
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id', 'id');
    }
}
