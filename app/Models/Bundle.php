<?php

namespace App\Models;

use App\Models\BundleItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bundle extends Model
{
    use HasFactory;

    protected $table = 'tbl_bundles';

    protected $fillable = [
        'name',
        'description',
        'total_price',
        'discount',
    ];

    public function items()
    {
        return $this->hasMany(BundleItem::class, 'bundle_id');
    }
}
