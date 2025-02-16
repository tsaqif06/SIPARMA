<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminBalanceLog extends Model
{
    use HasFactory;
    protected $table = 'tbl_admin_balance_logs';
    protected $fillable = [
        'profit',
        'period_year',
        'period_month'
    ];
}
