<?php

namespace App\Observers;

use App\Models\SaleItem;
use App\Services\StockAlertService;
use App\Notifications\LowStockNotification;

class SaleItemObserver
{
    public function created(SaleItem $saleItem)
    {
        $product = $saleItem->product;

        if (StockAlertService::isCritical($product)) {
            foreach (adminUsers() as $admin) {
                $admin->notify(new LowStockNotification($product));
            }
        }
    }
}
