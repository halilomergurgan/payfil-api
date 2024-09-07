<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof AuthorizationException) {
            return response()->json([
                'error' => 'You are not authorized to perform this action.',
            ], 403);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'error' => 'The requested resource was not found.',
            ], 404);
        }

        if ($exception instanceof ThrottleRequestsException) {
            return response()->json([
                'error' => 'Too many attempts.',
            ], 429);
        }

        if ($exception instanceof UnauthorizedException) {
            return response()->json([
                'error' => 'User does not have the right roles.',
            ], 403);
        }

        return parent::render($request, $exception);
    }
}
