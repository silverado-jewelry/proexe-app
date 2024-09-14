<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DecodePlainTextRequestBody
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('Content-Type') === 'text/plain') {
            $data = json_decode($request->getContent(), true);

            if (json_last_error() === JSON_ERROR_NONE) {
                $request->merge($data);
            }
        }

        return $next($request);
    }
}
