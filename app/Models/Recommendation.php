<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk entitas Recommendation (Rekomendasi).
 * Digunakan untuk menyimpan informasi mengenai rekomendasi destinasi yang diberikan oleh pengguna.
 *
 * @property int $id
 * @property int $user_id ID pengguna yang memberikan rekomendasi.
 * @property string $destination_name Nama destinasi yang direkomendasikan.
 * @property string $description Deskripsi mengenai destinasi yang direkomendasikan.
 * @property string $location Lokasi destinasi yang direkomendasikan.
 * @property string $status Status rekomendasi (misalnya 'approved', 'pending', dll).
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Recommendation extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'tbl_recommendations';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'destination_name',
        'description',
        'location',
        'status'
    ];

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
     * Mendefinisikan relasi dengan model RecommendationImage.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(RecommendationImage::class, 'recommendation_id');
    }
}
