<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleView extends Model
{
    use HasFactory;

    protected $fillable = ['article_id', 'ip_address', 'user_id'];

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }
}
