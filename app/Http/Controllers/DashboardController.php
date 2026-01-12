<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\StockItem;
use App\Models\Expense;
use App\Services\FinanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. KPIs GLOBAIS (Não costumam mudar com o filtro da tabela)
        $activeProducts = Product::where('status', 'active')->count();
        $itemsInStock   = StockItem::sum('quantity');
        $stockValue     = StockItem::selectRaw('SUM(quantity * COALESCE(cost_price, 0)) as total')->value('total') ?? 0;
        
        $financeStats = [
            'diario'  => FinanceService::daily(),
            'semanal' => FinanceService::weekly(),
            'mensal'  => FinanceService::monthly(),
        ];

        // 2. QUERY DE PRODUTOS (Com Filtros Aplicados)
        $query = Product::with(['category', 'stock']);

        // Filtro por Nome ou "SKU" (ID)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // 🔥 NOVO: Filtro por Categoria
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 3. OUTROS DADOS
        $expensesMonth = Expense::whereMonth('expense_date', Carbon::now()->month)
            ->whereYear('expense_date', Carbon::now()->year)
            ->orderBy('expense_date', 'desc')
            ->get();

        return view('admin.dashboard', [
            // Mantendo os produtos filtrados
            'products'            => $query->latest()->get(),
            'categories'          => Category::orderBy('name')->get(),
            'activeProducts'      => $activeProducts,
            'itemsInStock'        => $itemsInStock,
            'stockValue'          => $stockValue,
            'financeStats'        => $financeStats,
            'lowStockAlerts'      => StockItem::whereColumn('quantity', '<=', 'stock_alert_level')->count(),
            'newProductsThisWeek' => Product::where('created_at', '>=', Carbon::now()->subWeek())->count(),
            'avgMargin'           => 'N/A', // Você pode calcular isso depois
            'expensesMonth'       => $expensesMonth,
        ]);
    }
}