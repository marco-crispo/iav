<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\UserPositionController;
use App\Http\Controllers\Api\AvailabilityController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Api\MessageController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Broadcast;
use Ably\AblyRest;
use App\Http\Controllers\Api\OnlineUserController;
use App\Traits\Utils;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/profile', [ProfileController::class, 'store']);

Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

// Re-invio email
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return Utils::successResponse([], 'Verification email sent again', Response::HTTP_OK);
})->middleware(['auth:sanctum', 'throttle:6,1']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::get('/availability', [AvailabilityController::class, 'index']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::get('/is-email-verified', [AuthController::class, 'isEmailVerified']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/profile', [ProfileController::class, 'index']);
    Route::get('/profile/{profile}', [ProfileController::class, 'show']);
    Route::post('/profile/{profile}', [ProfileController::class, 'update']);
    Route::delete('/profile/{profile}', [ProfileController::class, 'destroy']);

    Route::get('/position', [UserPositionController::class, 'index']);
    Route::post('/position', [UserPositionController::class, 'store']);
    Route::get('/position/nearby', [UserPositionController::class, 'nearby']);
    Route::get('/position/{userPosition}', [UserPositionController::class, 'show']);
    Route::post('/position/{userPosition}', [UserPositionController::class, 'update']);
    Route::delete('/position/{userPosition}', [UserPositionController::class, 'destroy']);

    Route::post('/messages', [MessageController::class, 'store']);
    Route::get('/my-messages', [MessageController::class, 'myMessages']);

    Route::post('/email/resend', function (Request $request) {
        if ($request->user()->hasVerifiedEmail()) {
            return Utils::successResponse([], 'The email was already verified', Response::HTTP_OK);
        }
        $request->user()->sendEmailVerificationNotification();
        return Utils::successResponse([], 'An email was sent again to your address', Response::HTTP_OK);
    });

    // MANAGING ABLY
    Route::get('/ably-token', function (Request $request) {
        $apiKey = env('ABLY_KEY');

        if (empty($apiKey)) {
            Utils::errorResponse(['Server configuration error'], 'Error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $ably = new AblyRest($apiKey);
        $clientId = $request->user()?->id ? (string)$request->user()->id : 'guest';

        $tokenRequest = $ably->auth->createTokenRequest([
            'clientId' => $clientId,
        ]);

        // return Utils::successResponse($tokenRequest, 'Ably token generated successfully', Response::HTTP_OK);
        return response()->json($tokenRequest, Response::HTTP_OK);
    });
    Route::post('/online/enter', [OnlineUserController::class, 'enter']);
    Route::post('/online/leave', [OnlineUserController::class, 'leave']);

});

Broadcast::routes();
