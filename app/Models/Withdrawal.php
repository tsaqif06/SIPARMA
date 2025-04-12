<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk entitas Withdrawal (Penarikan).
 * Digunakan untuk menyimpan informasi tentang penarikan dari saldo.
 *
 * @property int $id
 * @property int $balance_id ID saldo yang terkait dengan penarikan ini.
 * @property float $amount Jumlah uang yang ditarik.
 * @property string $status Status penarikan (misalnya, 'pending', 'completed').
 * @property string|null $admin_note Catatan dari admin terkait penarikan.
 * @property string|null $transfer_proof Bukti transfer yang diunggah oleh pengguna.
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Withdrawal extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'tbl_withdrawals';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = [
        'balance_id',
        'amount',
        'status',
        'admin_note',
        'transfer_proof'
    ];

    /**
     * Mendefinisikan relasi dengan model Balance.
     * Menunjukkan bahwa penarikan ini terkait dengan saldo tertentu.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function balance()
    {
        return $this->belongsTo(Balance::class, 'balance_id', 'id');
    }
}
