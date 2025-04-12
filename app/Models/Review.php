<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk entitas Review (Ulasan).
 * Digunakan untuk menyimpan ulasan yang diberikan oleh pengguna untuk destinasi atau tempat tertentu.
 *
 * @property int $id
 * @property int $user_id ID pengguna yang memberikan ulasan.
 * @property int $destination_id ID destinasi yang diulas.
 * @property int $place_id ID tempat yang diulas (jika ada).
 * @property float $rating Penilaian (rating) dari pengguna.
 * @property string $comment Komentar dari pengguna mengenai destinasi atau tempat.
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Review extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'tbl_reviews';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'destination_id', 'place_id', 'rating', 'comment'];

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
