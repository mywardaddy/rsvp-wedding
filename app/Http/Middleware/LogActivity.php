<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log specific HTTP methods
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $routeName = $request->route()?->getName() ?? $request->path();

            ActivityLog::log(
                action: $request->method() . ' ' . $routeName,
                description: $request->method() . ' request to ' . $request->path(),
            );
        }

        return $response;
    }
}
