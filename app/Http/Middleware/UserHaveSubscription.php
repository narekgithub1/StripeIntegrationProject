<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserHaveSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $stripe_id = (auth()->user()->stripe_id);
        try {
            if (!$stripe_id) {
                return response()->json([
                    'error' => 'You have not permission .'
                ], 204);
            }
        } catch (Exception $e) {
            return response()->json([
                'messages' => $e->getMessage()], 401);
        }
        return $next($request);
    }
}
