<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk entitas BundleItem (Item dalam Paket).
 * Digunakan untuk menyimpan informasi mengenai item yang termasuk dalam paket.
 *
 * @property int $id
 * @property int $bundle_id ID bundle yang item ini terkait.
 * @property string $item_type Jenis item (misalnya 'destination' atau 'ride').
 * @property int $item_id ID item yang terkait (bisa menjadi ID destinasi atau ride).
 * @property int $quantity Jumlah item dalam paket.
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class BundleItem extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'tbl_bundle_items';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = ['bundle_id', 'item_type', 'item_id', 'quantity'];

    /**
     * Mendefinisikan relasi dengan model Bundle.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bundle()
    {
        return $this->belongsTo(Bundle::class);
    }

    /**
     * Mendefinisikan relasi dinamis dengan model terkait berdasarkan item_type.
     *
     * Jika item_type adalah 'destination', maka akan berhubungan dengan model Destination.
     * Jika item_type adalah 'ride', maka akan berhubungan dengan model Ride.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|null
     */
    public function item()
    {
        if ($this->item_type == 'destination') {
            return $this->belongsTo(Destination::class, 'item_id');
        } elseif ($this->item_type == 'ride') {
            return $this->belongsTo(Ride::class, 'item_id');
        }

        return null;
    }
}
