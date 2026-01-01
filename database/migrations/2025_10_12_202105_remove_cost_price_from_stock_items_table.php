<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Para dropar coluna em MySQL, pode ser necessário: composer require doctrine/dbal
        if (Schema::hasColumn('stock_items', 'cost_price')) {
            Schema::table('stock_items', function (Blueprint $table) {
                $table->dropColumn('cost_price');
            });
        }
    }

    public function down(): void
    {
        // Restaura a coluna caso dê rollback
        if (! Schema::hasColumn('stock_items', 'cost_price')) {
            Schema::table('stock_items', function (Blueprint $table) {
                $table->decimal('cost_price', 10, 2)->default(0);
            });
        }
    }
};
