<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecommendationImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'recommendation_id',
        'image_url'
    ];

    public function recommendation()
    {
        return $this->belongsTo(Recommendation::class, 'recommendation_id');
    }
}
