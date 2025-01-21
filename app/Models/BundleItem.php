<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BundleItem extends Model
{
    use HasFactory;

    protected $table = 'tbl_bundle_items';

    protected $fillable = [
        'bundle_id',
        'item_type',
        'item_id',
        'quantity',
    ];

    public function bundle()
    {
        return $this->belongsTo(Bundle::class, 'bundle_id');
    }
}
