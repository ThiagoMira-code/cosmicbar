<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CriticalStockExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Product::query()
            ->select('name', 'quantity', 'alert_level')
            ->whereColumn('quantity', '<=', 'alert_level')
            ->orderBy('quantity')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Product',
            'Current Quantity',
            'Alert Level',
        ];
    }
}
