<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
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
        // Arahkan pengguna langsung ke login ketika session atau token CSRF kedaluwarsa.
        $this->renderable(function (TokenMismatchException $exception, $request) {
            return $this->expiredSessionResponse($request);
        });

        // Tangani juga exception HTTP 419 yang sudah dikonversi oleh framework atau Livewire.
        $this->renderable(function (HttpExceptionInterface $exception, $request) {
            if ($exception->getStatusCode() !== 419) {
                return null;
            }

            return $this->expiredSessionResponse($request);
        });

        $this->reportable(function (Throwable $e) {
            //
        });
    }

    private function expiredSessionResponse($request)
    {
        if ($request->expectsJson() || $request->is('livewire/*')) {
            return response()->json([
                'redirect' => route('login'),
            ], 419);
        }

        return redirect()->route('login');
    }
}
