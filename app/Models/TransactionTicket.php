<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionTicket extends Model
{
    use HasFactory;
    protected $table = 'tbl_transaction_tickets';
    protected $fillable = ['transaction_id', 'item_type', 'item_id', 'adult_count', 'children_count', 'subtotal', 'visit_date'];
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function item()
    {
        if ($this->item_type == 'destination') {
            return $this->belongsTo(Destination::class, 'item_id');
        } elseif ($this->item_type == 'ride') {
            return $this->belongsTo(Ride::class, 'item_id');
        } elseif ($this->item_type == 'bundle') {
            return $this->belongsTo(Bundle::class, 'item_id');
        }

        return null;
    }
}
