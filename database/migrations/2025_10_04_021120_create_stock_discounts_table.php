<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_item_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('min_quantity');
            $table->decimal('discounted_price', 8, 2);
            $table->timestamps();

            $table->unique(['stock_item_id', 'min_quantity']); // evita faixas duplicadas
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_discounts');
    }
};
