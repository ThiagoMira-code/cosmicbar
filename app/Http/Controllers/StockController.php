<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    // Método auxiliar para limpar a formatação de preço (converte 1,60 para 1.60)
    private function sanitizePrice($value) {
        if (!$value) return null;
        return (float) str_replace(',', '.', str_replace('.', '', str_replace('€', '', $value)));
    }

public function update(Request $request, Product $product)
{
    // 1. Limpeza forçada: independente de vir ponto ou vírgula, transformamos em formato decimal de banco (1.60)
    if ($request->has('price')) {
        $request->merge(['price' => str_replace(',', '.', $request->price)]);
    }
    if ($request->has('cost_price')) {
        $request->merge(['cost_price' => str_replace(',', '.', $request->cost_price)]);
    }

    // 2. Validação: Adicionamos o cost_price aqui para o Laravel aceitar o dado
    $validated = $request->validate([
        'quantity'          => ['required', 'integer', 'min:0'],
        'price'             => ['required', 'numeric', 'min:0'],
        'cost_price'        => ['nullable', 'numeric', 'min:0'], // <-- ADICIONADO
        'stock_alert_level' => ['required', 'integer', 'min:0'],
        'min_quantity'      => ['array'],
        'min_quantity.*'    => ['nullable', 'integer', 'min:1'],
        'discount_price'    => ['array'],
        'discount_price.*'  => ['nullable', 'numeric', 'min:0'],
    ]);

    $stock = StockItem::firstOrCreate(
        ['product_id' => $product->id],
        ['quantity' => 0]
    );

    DB::transaction(function () use ($validated, $stock, $product) {
        // 3. Salvamento: Incluímos explicitamente o cost_price para sair de NULL no banco
        $stock->quantity          = $validated['quantity'];
        $stock->price             = $validated['price'];
        $stock->cost_price        = $validated['cost_price']; // <-- AGORA VAI SALVAR
        $stock->stock_alert_level = $validated['stock_alert_level'];
        $stock->save();

        if (($stock->quantity ?? 0) > 0 && $product->status !== 'active') {
            $product->status = 'active';
            $product->save();
        }

        // Lógica de descontos (se houver)
        if (method_exists($stock, 'discounts')) {
            $stock->discounts()->delete();
            $mins   = $validated['min_quantity'] ?? [];
            $prices = $validated['discount_price'] ?? [];
            foreach ($mins as $i => $min) {
                $p = isset($prices[$i]) ? str_replace(',', '.', $prices[$i]) : null;
                if ($min && $p !== null) {
                    $stock->discounts()->create([
                        'min_quantity'     => $min,
                        'discounted_price' => (float)$p,
                    ]);
                }
            }
        }
    });

    return back()->with('success', __('ui.save_stock'));
}

    public function updatePrice(Request $request, Product $product)
    {
        $request->merge(['price' => str_replace(',', '.', $request->price)]);

        $validated = $request->validate([
            'price' => ['required','numeric','min:0'],
        ]);

        $stock = StockItem::firstOrCreate(
            ['product_id' => $product->id],
            ['quantity' => 0]
        );

        $stock->price = $validated['price'];
        $stock->save();

        return back()->with('success', __('ui.price_updated_success'));
    }
}