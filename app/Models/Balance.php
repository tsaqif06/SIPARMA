<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk entitas Balance (Saldo Destinasi).
 * Digunakan untuk menyimpan informasi saldo dan total keuntungan yang dimiliki oleh destinasi.
 *
 * @property int $id
 * @property int $destination_id ID destinasi yang memiliki saldo ini.
 * @property float $balance Saldo saat ini milik destinasi.
 * @property float $total_profit Total keuntungan yang diperoleh.
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Balance extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'tbl_balance';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = [
        'destination_id',
        'balance',
        'total_profit'
    ];

    /**
     * Relasi ke model Destination.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id', 'id');
    }

    /**
     * Relasi ke model Withdrawal.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class, 'balance_id', 'id');
    }
}
