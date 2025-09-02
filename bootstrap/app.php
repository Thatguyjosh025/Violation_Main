<?php

use App\Http\Middleware\CheckRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->alias([
            'permission' => CheckRole::class
        ]);

        //bypass csrf for import csv
        $middleware->validateCsrfTokens(except: [
            'import_users_csv',
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
