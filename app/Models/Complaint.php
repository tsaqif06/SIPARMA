<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk entitas Complaint (Keluhan).
 * Digunakan untuk menyimpan informasi mengenai keluhan yang diajukan oleh pengguna terkait destinasi atau tempat.
 *
 * @property int $id
 * @property int $user_id ID pengguna yang mengajukan keluhan.
 * @property int $destination_id ID destinasi terkait keluhan.
 * @property int $place_id ID tempat terkait keluhan.
 * @property string $complaint_text Isi keluhan yang diajukan.
 * @property string $status Status keluhan (misalnya: pending, resolved, etc.).
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Complaint extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'tbl_complaints';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'destination_id', 'place_id', 'complaint_text', 'status'];

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
     * Mendefinisikan relasi dengan model Place.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id');
    }
}
