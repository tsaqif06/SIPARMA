<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;
    protected $table = 'tbl_facilities';
    protected $fillable = ['item_type', 'item_id', 'name', 'description'];

    public function destination()
    {
        return $this->belongsTo(Destination::class, 'item_id');
    }

    public function place()
    {
        return $this->belongsTo(Place::class, 'item_id');
    }
}
