<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminPlace extends Model
{
    use HasFactory;
    protected $table = 'tbl_admin_places';

    protected $fillable = ['user_id', 'place_id', 'approval_status', 'ownership_docs'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function place()
    {
        return $this->belongsTo(Place::class);
    }
}
