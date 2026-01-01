<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(0);
            $table->decimal('cost_price', 10, 2)->default(0);      // opcional, se você usa no estoque
            $table->unsignedInteger('stock_alert_level')->default(0);
            $table->json('discount_tiers')->nullable();            // opcional (faixas de desconto)
            $table->timestamps();

            $table->unique('product_id'); // 1 registro de estoque por produto
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_items');
    }
};
