<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk entitas ArticleComment (Komentar Artikel).
 * Digunakan untuk menyimpan komentar-komentar yang ditinggalkan oleh pengguna pada artikel.
 *
 * @property int $id
 * @property int $article_id ID artikel yang dikomentari.
 * @property int $user_id ID pengguna yang memberikan komentar.
 * @property string $comment Isi dari komentar.
 * @property int|null $parent_id ID komentar induk (untuk thread/reply).
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class ArticleComment extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'tbl_article_comments';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = ['article_id', 'user_id', 'comment', 'parent_id'];

    /**
     * Mendefinisikan relasi dengan model Article.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }

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
     * Mendefinisikan relasi komentar balasan (reply) dengan diri sendiri.
     * Menggunakan self-referencing relationship untuk mendapatkan komentar balasan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(ArticleComment::class, 'parent_id')
            ->with(['replies' => function ($query) {
                $query->orderBy('created_at', 'asc');
            }])
            ->orderBy('created_at', 'asc');
    }
}
