<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk entitas ArticleCategory (Kategori Artikel).
 * Digunakan untuk mengelola kategori artikel yang dapat dimiliki oleh artikel.
 *
 * @property int $id
 * @property string $name Nama kategori artikel.
 * @property string $slug Slug untuk kategori artikel (biasanya untuk URL).
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class ArticleCategory extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'tbl_article_categories';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug'];

    /**
     * Mendefinisikan relasi dengan model Article.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles()
    {
        return $this->hasMany(Article::class, 'category_id');
    }
}
