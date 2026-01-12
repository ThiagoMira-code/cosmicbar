<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class FinanceService
{
    /**
     * Soma despesas a partir de uma data
     */
    private static function calculateExpensesFrom($startDate = null)
    {
        $query = DB::table('expenses');

        if ($startDate) {
$query->whereDate('created_at', '>=', $startDate->format('Y-m-d'));
        }

        return (float) $query->sum('amount');
    }

    /**
     * Método base reutilizável
     */
   private static function calculateFrom($startDate = null)
{
    $raw = DB::table('sale_items')
        ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
        ->leftJoin('stock_items', 'stock_items.product_id', '=', 'sale_items.product_id');

    if ($startDate) {
        $raw->whereDate('sales.created_at', '>=', $startDate->format('Y-m-d'));
    }

    $data = $raw->selectRaw('
        SUM(CAST(sale_items.price AS DECIMAL(10,2)) * sale_items.quantity) as total_vendas,
        SUM(
            (CAST(sale_items.price AS DECIMAL(10,2)) - COALESCE(stock_items.cost_price, 0))
            * sale_items.quantity
        ) as lucro_liquido
    ')->first();

    $vendas    = $data->total_vendas ?? 0;
    $lucro     = $data->lucro_liquido ?? 0;
    $cmv       = $vendas - $lucro;
    $despesas  = self::calculateExpensesFrom($startDate);

    return (object)[
        'vendas'        => $vendas,
        'lucro'         => $lucro,
        'cmv'           => $cmv,              // continua exibindo
        'despesas'      => $despesas,
        'lucro_real'    => $vendas - $despesas, // agora lucro_real ignora CMV
        'resultado'     => $vendas - $despesas, // resultado final sem CMV
    ];
}


    public static function daily()
    {
        return self::calculateFrom(Carbon::today());
    }

    public static function weekly()
    {
        return self::calculateFrom(Carbon::now()->startOfWeek());
    }

    public static function monthly()
    {
        return self::calculateFrom(Carbon::now()->startOfMonth());
    }
}
