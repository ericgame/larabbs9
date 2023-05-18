<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VerificationCodesController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\CaptchasController;
use App\Http\Controllers\Api\AuthorizationsController;

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

        // 第三方登錄
        Route::post('socials/{social_type}/authorizations', [AuthorizationsController::class, 'socialStore'])->where('social_type', 'wechat')->name('socials.authorizations.store');
        
        // 登錄
        Route::post('authorizations', [AuthorizationsController::class, 'store'])->name('authorizations.store');

        // 刷新token
        Route::put('authorizations/current', [AuthorizationsController::class, 'update'])->name('authorizations.update');

        // 刪除token
        Route::delete('authorizations/current', [AuthorizationsController::class, 'destroy'])->name('authorizations.destroy');
    });

    Route::middleware('throttle:' . config('api.rate_limits.access'))->group(function() {
        //
    });
});

