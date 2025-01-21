<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    use HasFactory;

    protected $table = 'tbl_recommendation';
    protected $fillable = ['user_id', 'destination_name', 'description', 'location', 'status'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function images()
    {
        return $this->hasMany(RecommendationImage::class, 'recommendation_id');
    }
}
