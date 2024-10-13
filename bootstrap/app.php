<?php

use App\Exceptions\TCMBException;
use App\Http\Middleware\UseRequestId;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->prepend(UseRequestId::class);

        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);


        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (TCMBException $e, Request $request) {
            if ($request->is('api/*')) {
                Log::channel('tcmb')->error($e);

                return response()->json([
                    'message' => sprintf('TCMB servisine ulaşırken hata oluştu. [%s]', Context::get('TRACE-ID')),
                    'trace_id' => Context::get('TRACE-ID'),
                ], status: 500);
            }
        });

        $exceptions->render(function (Exception $e, Request $request) {
            if ($request->is('api/*')) {
                Log::channel('daily')->error($e);

                return response()->json([
                    'message' => sprintf('Bilinmeyen bir hata oluştu. [%s]', Context::get('TRACE-ID')),
                    'trace_id' => Context::get('TRACE-ID'),
                ], status: 500);
            }
        });
    })->create();
