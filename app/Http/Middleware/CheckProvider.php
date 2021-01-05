<?php

namespace App\Http\Middleware;

use Closure;

class CheckProvider
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $validator = validator()->make($request->all(), [
            'provider' => 'in:admins'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'invalid_provider',
                'message' => 'This provider is invalid.'
            ], 422);
        }

        if ($request->input('grant_type') === 'password' && $request->input('provider')) {
            config(['auth.guards.api.provider' => $request->input('provider')]);
        }

        return $next($request);
    }
}