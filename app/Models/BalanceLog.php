<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk entitas BalanceLog (Log Saldo).
 * Digunakan untuk menyimpan informasi log saldo yang terkait dengan destinasi.
 *
 * @property int $id
 * @property int $destination_id ID destinasi yang terkait dengan log saldo ini.
 * @property float $profit Keuntungan yang dicatat pada log saldo.
 * @property int $period_year Tahun periode keuntungan dicatat.
 * @property int $period_month Bulan periode keuntungan dicatat.
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class BalanceLog extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'tbl_balance_logs';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = [
        'destination_id',
        'profit',
        'period_year',
        'period_month'
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
}
