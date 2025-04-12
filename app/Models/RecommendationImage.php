<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk entitas RecommendationImage (Gambar Rekomendasi).
 * Digunakan untuk menyimpan gambar terkait dengan rekomendasi yang diberikan oleh pengguna.
 *
 * @property int $id
 * @property int $recommendation_id ID rekomendasi yang terkait dengan gambar.
 * @property string $image_url URL gambar rekomendasi.
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class RecommendationImage extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'tbl_recommendation_images';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = ['recommendation_id', 'image_url'];

    /**
     * Mendefinisikan relasi dengan model Recommendation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recommendation()
    {
        return $this->belongsTo(Recommendation::class, 'recommendation_id');
    }
}
