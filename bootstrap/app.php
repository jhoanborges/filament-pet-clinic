<?php

use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group('universal', [
            InitializeTenancyByDomain::class,
            InitializeTenancyBySubdomain::class,
        ]);

        /*
        $middleware->alias([
            'subdomain' => \App\Http\Middleware\SubdomainMiddleware::class,
        ]);
        // You can also add the middleware to the web group if needed
        $middleware->appendToGroup('web', [
            \App\Http\Middleware\SubdomainMiddleware::class,
        ]);
        */
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
