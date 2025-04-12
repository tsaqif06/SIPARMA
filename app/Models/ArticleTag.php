<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk entitas ArticleTag (Tag Artikel).
 * Digunakan untuk menyimpan informasi mengenai tag yang terkait dengan artikel.
 *
 * @property int $id
 * @property int $article_id ID artikel yang memiliki tag.
 * @property string $tag_name Nama tag yang terkait dengan artikel.
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class ArticleTag extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'tbl_article_tags';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = ['article_id', 'tag_name'];

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
