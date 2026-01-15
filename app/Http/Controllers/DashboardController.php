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
        /*
        |--------------------------------------------------------------------------
        | 1. KPIs GLOBAIS
        |--------------------------------------------------------------------------
        */
        $activeProducts = Product::where('status', 'active')->count();
        $itemsInStock   = StockItem::sum('quantity');
        $stockValue     = StockItem::selectRaw(
            'SUM(quantity * COALESCE(cost_price, 0)) as total'
        )->value('total') ?? 0;

        /*
        |--------------------------------------------------------------------------
        | 2. FINANCE STATS (Service)
        |--------------------------------------------------------------------------
        */
        $financeStats = [
            'diario'  => FinanceService::daily(),
            'semanal' => FinanceService::weekly(),
            'mensal'  => FinanceService::monthly(),
        ];

        /*
        |--------------------------------------------------------------------------
        | 3. PRODUTOS (Com Filtros)
        |--------------------------------------------------------------------------
        */
        $query = Product::with(['category', 'stock']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        /*
        |--------------------------------------------------------------------------
        | 4. FINANCEIRO DO MÊS
        |--------------------------------------------------------------------------
        */
        $expensesMonth = Expense::whereMonth('expense_date', now()->month)
            ->whereYear('expense_date', now()->year)
            ->where('amount', '<', 0) // 🔴 despesas
            ->orderBy('expense_date', 'desc')
            ->get();

        $incomesMonth = Expense::whereMonth('expense_date', now()->month)
            ->whereYear('expense_date', now()->year)
            ->where('amount', '>', 0) // 🟢 entradas
            ->orderBy('expense_date', 'desc')
            ->get();

        // Totais
        $totalExpensesMonth = abs($expensesMonth->sum('amount'));
        $totalIncomeMonth   = $incomesMonth->sum('amount');
        $resultMonth        = $totalIncomeMonth - $totalExpensesMonth;

        /*
        |--------------------------------------------------------------------------
        | 5. RETORNO PARA A VIEW
        |--------------------------------------------------------------------------
        */
        return view('admin.dashboard', [
            'products'            => $query->latest()->get(),
            'categories'          => Category::orderBy('name')->get(),
            'activeProducts'      => $activeProducts,
            'itemsInStock'        => $itemsInStock,
            'stockValue'          => $stockValue,

            'financeStats'        => $financeStats,

            'expensesMonth'       => $expensesMonth,
            'incomesMonth'        => $incomesMonth,
            'totalExpensesMonth'  => $totalExpensesMonth,
            'totalIncomeMonth'    => $totalIncomeMonth,
            'resultMonth'         => $resultMonth,

            'lowStockAlerts'      => StockItem::whereColumn('quantity', '<=', 'stock_alert_level')->count(),
            'newProductsThisWeek' => Product::where('created_at', '>=', Carbon::now()->subWeek())->count(),
            'avgMargin'           => 'N/A',
        ]);
    }
}
