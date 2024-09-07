<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIpAddress
{
    protected $blacklistedIps = ['123.456.789.000', '987.654.321.000'];

    /**
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (in_array($request->ip(), $this->blacklistedIps)) {
            return response()->json(['error' => 'Access denied from this IP address.'], 403);
        }

        return $next($request);
    }
}
