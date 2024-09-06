<?php

namespace App\Pipelines;

use App\Exceptions\ValidateCardException;
use App\Traits\LuhnTrait;
use Closure;

class ValidateCard
{
    use LuhnTrait;

    public function handle($request, Closure $next)
    {
        if (!$this->isValidLuhn($request['cardNumber'])) {
            throw new ValidateCardException('Card validation failed.');
        }

        return $next($request);
    }
}
