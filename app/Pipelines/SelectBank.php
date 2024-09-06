<?php

namespace App\Pipelines;

use App\Services\Payment\Providers\Bank1PaymentProvider;
use App\Services\Payment\Providers\Bank2PaymentProvider;
use App\Services\Payment\Providers\Bank3PaymentProvider;
use Closure;

class SelectBank
{
    public function handle($request, Closure $next)
    {
        $bin = substr($request['cardNumber'], 0, 6);

        if ($this->isBank1($bin)) {
            $provider = new Bank1PaymentProvider();
        } elseif ($this->isBank2($bin)) {
            $provider = new Bank2PaymentProvider();
        } else {
            $provider = new Bank3PaymentProvider();
        }

        $request['provider'] = $provider;

        return $next($request);
    }

    private function isBank1($bin)
    {
        return in_array($bin, ['411111', '411112']);
    }

    private function isBank2($bin)
    {
        return in_array($bin, ['511111', '511112']);
    }
}

