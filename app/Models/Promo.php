<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk entitas Promo (Promosi).
 * Digunakan untuk menyimpan informasi mengenai promosi yang berlaku di suatu destinasi atau tempat.
 *
 * @property int $id
 * @property int $destination_id ID destinasi yang mendapatkan promo.
 * @property int $place_id ID tempat yang mendapatkan promo.
 * @property float $discount Diskon yang diberikan dalam promo.
 * @property string $valid_from Tanggal mulai berlaku promo.
 * @property string $valid_until Tanggal berakhirnya promo.
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Promo extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'tbl_promo';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = [
        'destination_id',
        'place_id',
        'discount',
        'valid_from',
        'valid_until'
    ];

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
     * Mendefinisikan relasi dengan model Place.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id');
    }
}
