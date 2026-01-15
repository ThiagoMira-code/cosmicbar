<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class FinanceService
{
    /**
     * Despesas no período
     */
    private static function expensesBetween($start, $end)
    {
        return abs(
            DB::table('expenses')
                ->whereBetween('expense_date', [$start, $end])
                ->where('amount', '<', 0)
                ->sum('amount')
        );
    }

    /**
     * Método base
     */
   private static function calculateBetween($start, $end)
{
    // VENDAS
    $salesData = DB::table('sale_items')
        ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
        ->leftJoin('stock_items', 'stock_items.product_id', '=', 'sale_items.product_id')
        ->whereBetween('sales.created_at', [$start, $end])
        ->selectRaw('
            SUM(sale_items.price * sale_items.quantity) as vendas,
            SUM(
                (sale_items.price - COALESCE(stock_items.cost_price, 0))
                * sale_items.quantity
            ) as lucro
        ')
        ->first();

    $vendas = $salesData->vendas ?? 0;
    $lucro  = $salesData->lucro ?? 0;
    $cmv    = $vendas - $lucro;

    // ENTRADAS (income)
    $income = DB::table('expenses')
        ->whereDate('expense_date', '>=', $start->toDateString())
        ->whereDate('expense_date', '<=', $end->toDateString())
        ->where('amount', '>', 0)
        ->sum('amount');

    // DESPESAS
    $expenses = abs(
        DB::table('expenses')
            ->whereDate('expense_date', '>=', $start->toDateString())
            ->whereDate('expense_date', '<=', $end->toDateString())
            ->where('amount', '<', 0)
            ->sum('amount')
    );

    return (object)[
        'vendas'    => $vendas,
        'income'    => $income,
        'cmv'       => $cmv,
        'despesas'  => $expenses,
        'resultado' => ($vendas + $income) - $expenses,
    ];
}


    public static function daily()
    {
        return self::calculateBetween(
            Carbon::today(),
            Carbon::today()
        );
    }

    public static function weekly()
    {
        return self::calculateBetween(
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        );
    }

    public static function monthly()
    {
        return self::calculateBetween(
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        );
    }
}
