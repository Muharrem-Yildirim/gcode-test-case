<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Str;

class UseRequestId
{
    public function handle(Request $request, Closure $next): Response|JsonResponse
    {
        $requestId = $request->header('X-TRACE-ID')
            ?? (string) Str::ulid();

        Context::add('TRACE-ID', $requestId);

        return $next($request);
    }
}
