<?php

namespace App\Exceptions;

use Exception;

class PaymentException extends Exception
{
    /**
     * PaymentException constructor.
     *
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message = 'Payment processing failed', int $code = 400)
    {
        parent::__construct($message, $code);
    }

    public function report()
    {
        \Log::error($this->getMessage());
    }

    public function render($request)
    {
        return response()->json([
            'error' => $this->getMessage()
        ], $this->getCode());
    }
}
