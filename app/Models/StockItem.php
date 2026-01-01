<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
    protected $fillable = [
        'product_id',
        'quantity',
        'price',
        'cost_price',
        'stock_alert_level',
    ];

    protected $casts = [
        'price'      => 'decimal:2',
        'cost_price' => 'decimal:2',
    ];

    // Corrigindo o Mutator para NÃO apagar o ponto se ele for decimal
    public function setCostPriceAttribute($value)
    {
        if ($value === null || $value === '') {
            $this->attributes['cost_price'] = null;
            return;
        }
        // Troca vírgula por ponto, mas MANTÉM o ponto original
        $norm = str_replace(',', '.', (string) $value); 
        $this->attributes['cost_price'] = (float) $norm;
    }

    public function setPriceAttribute($value)
    {
        if ($value === null || $value === '') {
            $this->attributes['price'] = null;
            return;
        }
        $norm = str_replace(',', '.', (string) $value);
        $this->attributes['price'] = (float) $norm;
    }

    public function product() { return $this->belongsTo(Product::class); }
    public function discounts() { return $this->hasMany(StockDiscount::class, 'stock_item_id'); }
}