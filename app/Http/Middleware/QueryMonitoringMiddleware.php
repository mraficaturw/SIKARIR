<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * ============================================================================
 * QUERY MONITORING MIDDLEWARE
 * ============================================================================
 * Middleware untuk monitoring jumlah database queries per request.
 * 
 * Fitur:
 * - Log warning jika query count melebihi threshold
 * - Hanya aktif di development environment
 * - Membantu detect N+1 query problems
 * 
 * Threshold: 20 queries (adjustable)
 * ============================================================================
 */
class QueryMonitoringMiddleware
{
    /**
     * Query count threshold untuk warning
     * Jika jumlah query melebihi ini, akan di-log
     */
    const QUERY_THRESHOLD = 20;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Hanya aktif di development/local environment
        // Return early untuk production untuk avoid overhead
        if (!config('app.debug')) {
            return $next($request);
        }

        // Counter untuk total queries
        $queryCount = 0;
        $queries = [];

        // Listen semua database queries
        DB::listen(function ($query) use (&$queryCount, &$queries) {
            $queryCount++;

            // Store query info untuk debugging
            $queries[] = [
                'sql' => $query->sql,
                'bindings' => $query->bindings,
                'time' => $query->time,
            ];
        });

        // Process request
        $response = $next($request);

        // Check jika query count melebihi threshold
        if ($queryCount > self::QUERY_THRESHOLD) {
            Log::warning('High query count detected', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'query_count' => $queryCount,
                'threshold' => self::QUERY_THRESHOLD,
                'queries' => array_slice($queries, 0, 5), // Log first 5 queries saja
            ]);

            // Untuk development, bisa uncomment ini untuk see real-time warning
            // error_log("⚠️  High query count: {$queryCount} queries on {$request->path()}");
        }

        return $response;
    }
}
