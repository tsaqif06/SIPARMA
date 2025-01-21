<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $table = 'tbl_reviews';
    protected $fillable = ['user_id', 'destination_id', 'place_id', 'rating', 'comment'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }

    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id');
    }
}
