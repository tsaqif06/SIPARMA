<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk entitas AdminDestination (Admin Destinasi).
 * Digunakan untuk mengelola hubungan antara admin dan destinasi yang dikelola.
 *
 * @property int $id
 * @property int $user_id ID pengguna (admin) yang mengelola destinasi.
 * @property int $destination_id ID destinasi yang dikelola oleh admin.
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class AdminDestination extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'tbl_admin_destinations';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'destination_id'];

    /**
     * Mendefinisikan relasi dengan model User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendefinisikan relasi dengan model Destination.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
