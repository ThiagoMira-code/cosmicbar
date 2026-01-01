<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockDiscount extends Model
{
    protected $fillable = [
        'stock_item_id',
        'min_quantity',
        'discounted_price',
    ];

    public function stockItem()
    {
        return $this->belongsTo(StockItem::class, 'stock_item_id');
    }
}