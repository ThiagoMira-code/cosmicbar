<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
{
    use Queueable;

    public function __construct(public Product $product) {}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
{
    return [
        'title' => __('ui.low_stock_alert_title'),
        'message' => __('ui.low_stock_alert_message', [
            'product' => $this->product->name,
            'qty' => $this->product->stock->quantity ?? 0,
        ]),
        'product_id' => $this->product->id,
    ];
}

}
