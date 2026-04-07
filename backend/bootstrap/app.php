<?php

use App\Http\Middleware\AdminMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(\Illuminate\Http\Middleware\HandleCors::class);
        $middleware->alias(['role' => AdminMiddleware::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Priority 5: Professional Global Error Handling & Logging
        
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json(['message' => 'Sesi berakhir, silakan login kembali.'], 401);
            }
        });

        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Data yang dikirim tidak valid.',
                    'errors'  => $e->errors(),
                ], 422);
            }
        });

        $exceptions->render(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json(['message' => 'Data tidak ditemukan.'], 404);
            }
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json(['message' => 'Anda tidak memiliki akses ke data ini.'], 403);
            }
        });

        // Catch-all for fatal production errors (Hide PHP Traces)
        $exceptions->render(function (\Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                // Priority 5: Enhanced Logging
                Log::error("API Error: " . $e->getMessage(), [
                    'url'    => $request->fullUrl(),
                    'input'  => $request->except(['password']),
                    'trace'  => $e->getTraceAsString(),
                ]);

                return response()->json([
                    'message' => 'Terjadi kesalahan sistem. Silakan coba beberapa saat lagi.',
                    'debug'   => config('app.debug') ? $e->getMessage() : null,
                ], 500);
            }
        });
    })->create();
