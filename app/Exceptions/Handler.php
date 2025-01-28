<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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
     * Render the exception to an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // Handling 404 Not Found Error
        if ($exception instanceof NotFoundHttpException) {
            $message = 'Halaman yang Anda cari tidak ditemukan.';
            return response()->view('admin.error', [
                'status_code' => 404,
                'error' => 'Page Not Found',
                'message' => $message
            ], 404);
        }

        // Handling 403 Forbidden Error
        if ($exception instanceof AccessDeniedHttpException) {
            $message = 'Anda tidak memiliki izin untuk mengakses halaman ini.';
            return response()->view('admin.error', [
                'status_code' => 403,
                'error' => 'Forbidden',
                'message' => $message
            ], 403);
        }

        return parent::render($request, $exception);
    }
}
