<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VerificationCodesController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\CaptchasController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::prefix('v1')->name('api.v1.')->group(function() {
Route::prefix('v1')->name('api.v1.')->group(function() {
    Route::middleware('throttle:' . config('api.rate_limits.sign'))->group(function() {
        // 圖片驗證碼
        Route::post('captchas', [CaptchasController::class, 'store'])->name('captchas.store');

        // 短信驗證碼
        Route::post('verificationCodes', [verificationCodesController::class, 'store'])->name('verificationCodes.store');

        // 用戶註冊
        Route::post('users', [UsersController::class, 'store'])->name('users.store');
    });

    Route::middleware('throttle:' . config('api.rate_limits.access'))->group(function() {
        //
    });
});

