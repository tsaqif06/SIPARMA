<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BundleItem extends Model
{
    use HasFactory;
    protected $table = 'tbl_bundle_items';
    protected $fillable = ['bundle_id', 'item_type', 'item_id', 'quantity'];
    public function bundle()
    {
        return $this->belongsTo(Bundle::class);
    }

    // public function destination()
    // {
    //     return $this->belongsTo(Destination::class, 'item_id');
    // }

    // public function ride()
    // {
    //     return $this->belongsTo(Ride::class, 'item_id');
    // }

    public function item()
    {
        if ($this->item_type == 'destination') {
            return $this->belongsTo(Destination::class, 'item_id');
        } elseif ($this->item_type == 'ride') {
            return $this->belongsTo(Ride::class, 'item_id');
        }

        return null;
    }
}
