<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\StockItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\FinanceService;
use App\Models\Expense;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // KPIs de Estoque
        $activeProducts = Product::where('status', 'active')->count();
        $itemsInStock   = StockItem::sum('quantity');

        // 🔥 FINANÇAS VINDO DO SERVICE
        $financeStats = [
            'diario'  => FinanceService::daily(),
            'semanal' => FinanceService::weekly(),
            'mensal'  => FinanceService::monthly(),
        ];

        // Valor total do estoque
        $stockValue = StockItem::selectRaw('
            SUM(quantity * COALESCE(cost_price, 0)) as total
        ')->value('total') ?? 0;

        // Produtos
        $query = Product::with(['category', 'stock']);

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

            $expensesMonth = Expense::whereMonth('expense_date', Carbon::now()->month)
        ->whereYear('expense_date', Carbon::now()->year)
        ->orderBy('expense_date', 'desc')
        ->get();


        return view('admin.dashboard', [
            'products'            => $query->latest()->get(),
            'categories'          => Category::orderBy('name')->get(),
            'activeProducts'      => $activeProducts,
            'itemsInStock'        => $itemsInStock,
            'stockValue'          => $stockValue,
            'financeStats'        => $financeStats,
            'lowStockAlerts'      => StockItem::whereColumn('quantity', '<=', 'stock_alert_level')->count(),
            'newProductsThisWeek' => Product::where('created_at', '>=', Carbon::now()->subWeek())->count(),
            'avgMargin'           => 'N/A',
            'expensesMonth' => $expensesMonth,
        ]);
    }
}
