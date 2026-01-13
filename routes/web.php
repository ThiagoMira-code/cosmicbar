<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ExpenseController;
use App\Exports\CriticalStockExport;
use Maatwebsite\Excel\Facades\Excel;

// Troca de idioma (EN/IT)
Route::get('/locale/{lang}', function (string $lang) {
    $lang = in_array($lang, ['en', 'it']) ? $lang : 'it';
    session(['locale' => $lang]);
    App::setLocale($lang);
    return back();
})->name('locale.set');

// Middleware de locale
Route::middleware('locale')->group(function () {

    Route::get('/', function () {
        if (auth()->check()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('admin.login');
    })->name('home');

    // Autenticação
    Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');

    // Painel Admin - ACESSO GERAL (Admin e Staff)
    // Removido 'is_admin' daqui para evitar o erro 403 no login do Staff
    Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

        // Dashboard e Logout acessíveis por todos os logados
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

        // Checkout de Vendas acessível por todos (PDV)
        Route::post('/sales/checkout', [SaleController::class, 'store'])->name('sales.checkout');

        // FUNÇÕES RESTRITAS - APENAS ADMINISTRADORES
        Route::middleware(['is_admin'])->group(function () {
            
            // Gerenciamento de Produtos
            Route::post('/products', [ProductController::class, 'store'])->name('products.store');
            Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
            Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

            // Gerenciamento de Estoque e Preços
            Route::post('/stock/{product}', [StockController::class, 'update'])->name('stock.update');
            Route::post('/stock/{product}/price', [StockController::class, 'updatePrice'])->name('stock.price.update');

                Route::post('/expenses', [ExpenseController::class, 'store'])
        ->name('expenses.store');
        
       Route::post('/products/{product}/add-stock', [StockController::class, 'addStock'])
    ->name('stock.add');

        });

    

    });

    Route::fallback(fn () => redirect()->route('admin.login'));
});