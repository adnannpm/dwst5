<?php

use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        // untuk custom res nya yg validator nya gitu
        $exceptions->render(function(ValidationException $e,Request $request) {
            if($request->is('api/auth/register')){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid field',
                    'errors' => $e->errors()
                ], 422);
            }else if($request->is('api/auth/login')){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Username or Password incorrect'
                ], 401);
            }else if($request->is('api/wallets')){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid field',
                    'errors' => $e->errors()
                ]);
            }else if($request->is('api/transactions')){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid field',
                    'errors' => $e->errors(),
                ]);
            }
        });

        // untuk custom res nya si bearer token
        $exceptions->render(function(AuthenticationException $e, Request $request) {
            if($request->is('api/*')){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthenticated'
                ], 401);
            }
        });
    })->create();
