<?php

namespace App\Pipelines;

use Closure;

class ProcessPayment
{
    public function handle($request, Closure $next)
    {
        $provider = $request['provider'];

        if (!$provider->processPayment($request['amount'], $request['currency'])) {
            throw new \Exception('Payment failed.');
        }

        return $next($request);
    }
}
