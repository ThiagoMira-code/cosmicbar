<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_items', function (Blueprint $table) {
            // adiciona a coluna 'price'
            $table->decimal('price', 10, 2)->nullable()->after('quantity');

            // opcional: se no seu controller você passa null para cost_price e stock_alert_level,
            // deixe as colunas aceitarem null para evitar problemas em modo estrito do MySQL.
            // Para usar ->change() pode ser necessário "composer require doctrine/dbal"
            // Descomente se precisar:
            // $table->decimal('cost_price', 10, 2)->nullable()->change();
            // $table->unsignedInteger('stock_alert_level')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('stock_items', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
};
