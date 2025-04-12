<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk entitas ArticleView (Tampilan Artikel).
 * Digunakan untuk menyimpan informasi mengenai tampilan artikel yang dilihat oleh pengguna.
 *
 * @property int $id
 * @property int $article_id ID artikel yang dilihat.
 * @property string $ip_address Alamat IP pengguna yang melihat artikel.
 * @property int $user_id ID pengguna yang melihat artikel, jika login.
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class ArticleView extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'tbl_article_views';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = ['article_id', 'ip_address', 'user_id'];

    /**
     * Mendefinisikan relasi dengan model Article.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }
}
