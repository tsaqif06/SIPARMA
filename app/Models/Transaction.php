<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk entitas Transaction (Transaksi).
 * Digunakan untuk menyimpan informasi mengenai transaksi yang dilakukan oleh pengguna.
 *
 * @property int $id
 * @property int $user_id ID pengguna yang melakukan transaksi.
 * @property int $destination_id ID destinasi yang terkait dengan transaksi.
 * @property float $amount Jumlah uang yang dibayarkan dalam transaksi.
 * @property string $status Status transaksi (misalnya, berhasil, gagal).
 * @property string $transaction_code Kode unik yang mengidentifikasi transaksi.
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Transaction extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'tbl_transactions';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'destination_id', 'amount', 'status', 'transaction_code'];

    /**
     * Mendefinisikan relasi dengan model User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Mendefinisikan relasi dengan model Destination.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }

    /**
     * Mendefinisikan relasi dengan model TransactionTicket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany(TransactionTicket::class, 'transaction_id');
    }
}
