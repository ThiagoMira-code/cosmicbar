<?php

namespace App\Mail;

use App\Models\Product;
use App\Models\StockItem;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LowStockAlert extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Product $product,
        public StockItem $stock
    ) {}

    public function build()
    {
        return $this->subject('Alerta: Estoque Baixo - ' . $this->product->name)
            ->view('emails.low_stock_alert');
    }
}
