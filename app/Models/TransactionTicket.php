<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk entitas TransactionTicket (Tiket Transaksi).
 * Digunakan untuk menyimpan informasi mengenai tiket yang terkait dengan transaksi.
 *
 * @property int $id
 * @property int $transaction_id ID transaksi yang terkait dengan tiket ini.
 * @property string $item_type Tipe item yang terkait dengan tiket (destination, ride, bundle).
 * @property int $item_id ID item yang terkait dengan tiket (bisa destinasi, wahana, atau paket).
 * @property int $adult_count Jumlah tiket dewasa yang dibeli.
 * @property int $children_count Jumlah tiket anak-anak yang dibeli.
 * @property float $subtotal Total harga untuk tiket ini (jumlah tiket x harga per tiket).
 * @property \Illuminate\Support\Carbon $visit_date Tanggal kunjungan yang dijadwalkan untuk tiket ini.
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class TransactionTicket extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'tbl_transaction_tickets';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = ['transaction_id', 'item_type', 'item_id', 'adult_count', 'children_count', 'subtotal', 'visit_date'];

    /**
     * Mendefinisikan relasi dengan model Transaction.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    /**
     * Mendefinisikan relasi dinamis dengan model item terkait berdasarkan tipe item (destination, ride, bundle).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|null
     */
    public function item()
    {
        if ($this->item_type == 'destination') {
            return $this->belongsTo(Destination::class, 'item_id');
        } elseif ($this->item_type == 'ride') {
            return $this->belongsTo(Ride::class, 'item_id');
        } elseif ($this->item_type == 'bundle') {
            return $this->belongsTo(Bundle::class, 'item_id');
        }

        return null;
    }
}
