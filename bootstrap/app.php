<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        // api: __DIR__.'/../routes/api.php',
        // commands: __DIR__.'/../routes/console.php',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // REGISTRE OS ALIASES AQUI
        $middleware->alias([
            // troquei o nome do alias para 'locale' para evitar confusão com a função PHP setlocale()
            'locale'   => \App\Http\Middleware\SetLocale::class,
            'is_admin' => \App\Http\Middleware\IsAdmin::class,
        ]);

        // se quiser aplicar SEMPRE em todas as rotas (opcional):
        // $middleware->append(\App\Http\Middleware\SetLocale::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
