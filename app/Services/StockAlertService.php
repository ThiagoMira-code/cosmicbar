<?php

namespace App\Services;

use App\Models\Product;

class StockAlertService
{
    public static function isCritical(Product $product): bool
    {
        return $product->quantity <= $product->alert_level;
    }

    public static function criticalItems()
    {
        return Product::whereColumn('quantity', '<=', 'alert_level')->get();
    }
}
