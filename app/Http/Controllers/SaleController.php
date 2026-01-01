<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockItem; // Importe o model de estoque
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'mesa'  => 'required|integer',
            'itens' => 'required|array|min:1',
            'total' => 'required|numeric'
        ]);

        DB::beginTransaction();

        try {
            $sale = Sale::create([
                'table_number' => $request->mesa,
                'total'        => $request->total,
                'paid'         => true
            ]);

            foreach ($request->itens as $item) {
                // 1. Cria o item da venda
                SaleItem::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $item['id'],
                    'price'      => $item['price'],
                    'quantity'   => $item['qty']
                ]);

                // 2. BAIXA NO ESTOQUE:
                // Busca o item de estoque vinculado ao ID do produto
                $stock = StockItem::where('product_id', $item['id'])->first();

                if ($stock) {
                    // Subtrai a quantidade vendida da quantidade atual
                    $stock->decrement('quantity', $item['qty']);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'sale_id' => $sale->id
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
                'line'    => $e->getLine()
            ], 500);
        }
    }
}