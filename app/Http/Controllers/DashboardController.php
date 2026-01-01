<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\StockItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // KPIs Básicos de Estoque
        $activeProducts = Product::where('status', 'active')->count();
        $itemsInStock = StockItem::sum('quantity');

        // 1. DEFINIÇÃO DA LÓGICA DE CÁLCULO
        $calculate = function ($startDate = null) {
            $query = DB::table('sale_items')
                ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
                ->leftJoin('stock_items', 'stock_items.product_id', '=', 'sale_items.product_id');

            if ($startDate) {
                // Filtramos apenas pela DATA (Y-m-d) para ignorar conflitos de horas (22:35)
                $query->whereDate('sales.created_at', '>=', $startDate->format('Y-m-d'));
            }

            return $query->select(DB::raw('
                SUM(CAST(sale_items.price AS DECIMAL(10,2)) * sale_items.quantity) as total_vendas,
                SUM((CAST(sale_items.price AS DECIMAL(10,2)) - COALESCE(stock_items.cost_price, 0)) * sale_items.quantity) as lucro_liquido
            '))->first();
        };


        // 2. EXECUÇÃO (Atribuindo os valores reais às variáveis)
        // Usamos Carbon::today() para garantir que pegue as vendas de 01/01/2026
        $financeStats = [
            'diario'  => $calculate(Carbon::today()),
            'semanal' => $calculate(Carbon::now()->startOfWeek()),
            'mensal'  => $calculate(Carbon::now()->startOfMonth()),
        ];


        // Valor do inventário parado (Custo * Qtd)
        $stockValue = StockItem::select(DB::raw('SUM(quantity * COALESCE(cost_price, 0)) as total'))->value('total') ?? 0;

        // Listagem de produtos para a tabela
        $query = Product::with(['category', 'stock']);
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        return view('admin.dashboard', [
            'products' => $query->latest()->get(),
            'categories' => Category::orderBy('name')->get(),
            'activeProducts' => $activeProducts,
            'itemsInStock' => $itemsInStock,
            'stockValue' => $stockValue,
            'financeStats' => $financeStats, // Agora os dados vão preenchidos para a View
            'lowStockAlerts' => StockItem::whereColumn('quantity', '<=', 'stock_alert_level')->count(),
            'newProductsThisWeek' => Product::where('created_at', '>=', Carbon::now()->subWeek())->count(),
            'avgMargin' => 'N/A',
        ]);
    }
}