<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = ['type_id', 'item_code', 'item_name', 'quantity', 'price','low_stock_alert', 'manage_by'];

    public function type()
    {
        return $this->belongsTo(InventoryType::class);
    }

}
