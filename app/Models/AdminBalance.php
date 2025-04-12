<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk entitas AdminBalance (Saldo Admin).
 * Digunakan untuk menyimpan informasi saldo yang dimiliki oleh admin.
 *
 * @property int $id
 * @property float $balance Saldo yang dimiliki oleh admin.
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class AdminBalance extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'tbl_admin_balance';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = [
        'balance',
    ];
}
