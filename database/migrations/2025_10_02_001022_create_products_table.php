<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image_path')->nullable(); // A coluna pode ser nula

            // Chave estrangeira para a tabela de categorias
            $table->foreignId('category_id')
                  ->constrained('categories') // Liga com a tabela 'categories'
                  ->onDelete('cascade'); // Se a categoria for apagada, os produtos também serão

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
