<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk entitas AdminPlace (Admin Tempat).
 * Digunakan untuk mengelola hubungan antara admin dan tempat yang dikelola, serta status dan dokumen kepemilikan tempat tersebut.
 *
 * @property int $id
 * @property int $user_id ID pengguna (admin) yang mengelola tempat.
 * @property int $place_id ID tempat yang dikelola oleh admin.
 * @property string $approval_status Status persetujuan tempat (misalnya: approved, pending, rejected).
 * @property string $ownership_docs Dokumen kepemilikan tempat.
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class AdminPlace extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'tbl_admin_places';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'place_id', 'approval_status', 'ownership_docs'];

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
     * Mendefinisikan relasi dengan model Place.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function place()
    {
        return $this->belongsTo(Place::class);
    }
}
