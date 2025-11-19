<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (HttpResponseException $e, $request) {
            return $e->getResponse();
        });

        $exceptions->render(function (ValidationException $e, $request) {
            $errors = [];

            foreach ($e->errors() as $fieldErrors) {
                foreach ($fieldErrors as $message) {
                    $errors[] = $message;
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $errors
            ], 422);
        });

            $exceptions->render(function (Throwable $e, $request) {

            $status = $e->getCode();
            if ($status < 400 || $status >= 600) {
                $status = 500;
            }

            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'Server Error',
                'errors'  => [$e->getMessage()],
            ], $status);
        });
    })->create();
