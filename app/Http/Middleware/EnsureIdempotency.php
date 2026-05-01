<?php

namespace App\Http\Middleware;

use App\Models\Transaction;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIdempotency
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = $request->header('Idempotency-Key');
        if (!$key) {
            return response()->json(['message' => 'Idempotency-Key header required'], 400);
        }

        $existing = Transaction::where('idempotency_key', $key)->first();
        if ($existing) {
            // Previously processed – return success (idempotent response)
            return response()->json([
                'message' => 'Already processed',
                'transaction_id' => $existing->id,
                'reference' => $existing->reference,
            ], 200);
        }

        return $next($request);
    }
}
