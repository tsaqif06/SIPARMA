<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bundle extends Model
{
    use HasFactory;
    protected $table = 'tbl_bundles';
    protected $fillable = ['name', 'description', 'total_price', 'discount'];
    public function items()
    {
        return $this->hasMany(BundleItem::class, 'bundle_id');
    }
}
