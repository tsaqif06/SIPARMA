<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk entitas Article (Artikel).
 * Digunakan untuk mengelola artikel yang diterbitkan oleh pengguna, termasuk informasi terkait kategori, tag, komentar, like, dan views.
 *
 * @property int $id
 * @property int $user_id ID pengguna (penulis) yang membuat artikel.
 * @property int $category_id ID kategori artikel.
 * @property string $title Judul artikel.
 * @property string $slug Slug untuk URL artikel.
 * @property string $content Konten artikel.
 * @property string|null $thumbnail URL gambar thumbnail artikel.
 * @property string $status Status artikel (misalnya: published, draft, pending).
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Article extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'tbl_articles';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'content',
        'thumbnail',
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
     * Mendefinisikan relasi dengan model ArticleCategory.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id');
    }

    /**
     * Mendefinisikan relasi dengan model ArticleTag.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tags()
    {
        return $this->hasMany(ArticleTag::class, 'article_id');
    }

    /**
     * Mendefinisikan relasi dengan model ArticleComment.
     * Mengurutkan komentar berdasarkan yang terbaru.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(ArticleComment::class, 'article_id')->latest();
    }

    /**
     * Mendefinisikan relasi dengan model ArticleLike.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function likes()
    {
        return $this->hasMany(ArticleLike::class, 'article_id');
    }

    /**
     * Mendefinisikan relasi dengan model ArticleView.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function views()
    {
        return $this->hasMany(ArticleView::class, 'article_id');
    }
}
