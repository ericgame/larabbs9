<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VerificationCodesController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\CaptchasController;
use App\Http\Controllers\Api\AuthorizationsController;
use App\Http\Controllers\Api\ImagesController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\TopicsController;
use App\Http\Controllers\Api\RepliesController;

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
        // 遊客可以訪問的接口 ---------------------------------------------------------------

        // 某個用戶的詳情
        Route::get('users/{user}', [UsersController::class, 'show'])->name('users.show');

        /*分類列表
            GET|HEAD api/v1/categories ... api.v1.categories.index › Api\CategoriesController@index
        */
        Route::apiResource('categories', CategoriesController::class)->only('index');

        /*話題列表、詳情
            GET|HEAD api/v1/topics ... api.v1.topics.index › Api\TopicsController@index
            GET|HEAD api/v1/topics/{topic} ... api.v1.topics.show › Api\TopicsController@show
        */
        Route::apiResource('topics', TopicsController::class)->only(['index', 'show']);

        // 某個用戶發布的話題
        Route::get('users/{user}/topics', [TopicsController::class, 'userIndex'])->name('users.topics.index');


        // 登錄後可以訪問的接口 ---------------------------------------------------------------
        Route::middleware('auth:api')->group(function() {
            // 當前登錄用戶信息
            Route::get('user', [UsersController::class, 'me'])->name('user.show');

            // 編輯登錄用戶信息
            Route::patch('user', [UsersController::class, 'update'])->name('user.update');
            
            // 上傳圖片
            Route::post('images', [ImagesController::class, 'store'])->name('images.store');

            /*發布、修改、刪除話題
                POST api/v1/topics ... api.v1.topics.store › Api\TopicsController@store
                PUT|PATCH api/v1/topics/{topic} ... api.v1.topics.update › Api\TopicsController@update
                DELETE api/v1/topics/{topic} ... api.v1.topics.destroy › Api\TopicsController@destroy
            */
            Route::apiResource('topics', TopicsController::class)->only(['store', 'update', 'destroy']);

            /*發布、刪除回覆
                POST api/v1/topics/{topic}/replies ... api.v1.topics.replies.store › Api\RepliesController@store
                DELETE api/v1/topics/{topic}/replies/{reply} ... api.v1.topics.replies.destroy › Api\RepliesController@destroy
            */
            Route::apiResource('topics.replies', RepliesController::class)->only(['store', 'destroy']);
        });
    });
});

