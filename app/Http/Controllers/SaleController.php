<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockItem;
use App\Models\User;
use App\Notifications\LowStockNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class SaleController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'mesa'  => 'required|integer',
        'itens' => 'required|array|min:1',
        'total' => 'required|numeric'
    ]);

    $warnings = []; // Array para capturar alertas de estoque
    DB::beginTransaction();

    try {
        // 1. Cria a venda
        $sale = Sale::create([
            'table_number' => $request->mesa,
            'total'        => $request->total,
            'paid'         => true
        ]);

        foreach ($request->itens as $item) {
            // 2. Cria o item da venda
            SaleItem::create([
                'sale_id'    => $sale->id,
                'product_id' => $item['id'],
                'price'      => $item['price'],
                'quantity'   => $item['qty']
            ]);

            // 3. Atualiza o estoque
            $stock = StockItem::with('product')->where('product_id', $item['id'])->first();

            if ($stock && $stock->product) {
                $newQuantity = max($stock->quantity - $item['qty'], 0);
                $stock->update(['quantity' => $newQuantity]);

                // 4. Verifica se atingiu nível crítico para o alerta na tela
                if ($newQuantity <= ($stock->stock_alert_level ?? 0)) {
                    $warnings[] = "Estoque crítico: {$stock->product->name} (Restam apenas {$newQuantity})";
                }

                // 5. Tenta enviar notificação interna (opcional, silenciado se falhar)
                try {
                    if ($newQuantity <= ($stock->stock_alert_level ?? 0)) {
                        $admins = User::where('is_admin', 1)->get();
                        Notification::send($admins, new LowStockNotification($stock->product));
                    }
                } catch (\Exception $e) {
                    // Ignora erro de notificação para não cancelar a venda se a tabela não existir
                }
            }
        }

        DB::commit();

        return response()->json([
            'success'  => true,
            'sale_id'  => $sale->id,
            'warnings' => $warnings // Retorna os avisos para o frontend
        ]);

    } catch (\Throwable $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'error'   => $e->getMessage()
        ], 500);
    }
}
}
