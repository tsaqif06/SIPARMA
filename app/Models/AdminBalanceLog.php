<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk entitas AdminBalanceLog (Log Saldo Admin).
 * Digunakan untuk menyimpan riwayat perubahan saldo admin, termasuk periode tahun dan bulan pencatatan.
 *
 * @property int $id
 * @property float $profit Jumlah profit yang dicatat.
 * @property int $period_year Tahun pencatatan profit.
 * @property int $period_month Bulan pencatatan profit (1-12).
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class AdminBalanceLog extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'tbl_admin_balance_logs';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = [
        'profit',
        'period_year',
        'period_month',
    ];
}
