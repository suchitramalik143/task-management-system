<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    // This is where you can define global middleware.
    protected $middleware = [
        // Global middleware here.
    ];

    // Middleware groups.
    protected $middlewareGroups = [
        'web' => [
            // Web middleware here.
        ],

        'api' => [
            // API middleware here.
        ],
    ];

    // Route-specific middleware
    protected $routeMiddleware = [
        // Other middlewares...
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
    ];
}
