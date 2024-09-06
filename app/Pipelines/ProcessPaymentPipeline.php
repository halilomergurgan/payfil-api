<?php

namespace App\Pipelines;

use Closure;

class ProcessPaymentPipeline
{
    /**
     * @throws \Exception
     */
    public function handle($request, Closure $next)
    {
        if (!$this->validateCard($request['cardDetails'])) {
            throw new \Exception('Card validation failed.');
        }

        return $next($request);
    }

    /**
     * @param $cardDetails
     * @return bool
     */
    private function validateCard($cardDetails): bool
    {
        return true;
    }
}
