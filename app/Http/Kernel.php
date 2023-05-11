<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    // 全局中間件
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class, // 修正代理服務器後的服務器參數
        \Illuminate\Http\Middleware\HandleCors::class, // 解決 cors 跨域問題
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class, // 檢測應用是否進入『維護模式』// 見：https://learnku.com/docs/laravel/9.x/configuration#maintenance-mode
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class, // 檢測表單請求的數據是否過大
        \App\Http\Middleware\TrimStrings::class, // 對所有提交的請求數據進行 PHP 函數 `trim()` 處理
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class, // 將提交請求參數中空字串轉換為 null
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    // 設定中間件組
    protected $middlewareGroups = [
        // Web 中間件組，應用於 routes/web.php 路由文件，
        // 在 RouteServiceProvider 中設定
        'web' => [
            \App\Http\Middleware\EncryptCookies::class, // Cookie 加密解密
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class, // 將 Cookie 添加到響應中
            \Illuminate\Session\Middleware\StartSession::class, // 開啟會話
            \Illuminate\View\Middleware\ShareErrorsFromSession::class, // 將系統的錯誤數據注入到視圖變量 $errors 中
            \App\Http\Middleware\VerifyCsrfToken::class, // 檢驗 CSRF ，防止跨站請求偽造的安全威脅 // 見：https://learnku.com/docs/laravel/9.x/csrf
            \Illuminate\Routing\Middleware\SubstituteBindings::class, // 處理路由綁定 // 見：https://learnku.com/docs/laravel/9.x/routing#route-model-binding
            \App\Http\Middleware\EnsureEmailIsVerified::class, // 強制用戶郵箱認證
            \App\Http\Middleware\RecordLastActivedTime::class, // 記錄用戶最後活躍時間
        ],

        // API 中間件組，應用於 routes/api.php 路由文件，
        // 在 RouteServiceProvider 中設定
        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \App\Http\Middleware\AcceptHeader::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class, // 使用別名來調用中間件 // 請見：https://learnku.com/docs/laravel/9.x/middleware#為路由分配中間件
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    // 中間件別名設置，允許你使用別名調用中間件，例如上面的 api 中間件組調用
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class, // 只有登錄用戶才能訪問，我們在控制器的構造方法中大量使用
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class, // HTTP Basic Auth 認證
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class, // 緩存標頭
        'can' => \Illuminate\Auth\Middleware\Authorize::class, // 用戶授權功能
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class, // 只有遊客才能訪問，在 register 和 login 請求中使用，只有未登錄用戶才能訪問這些頁面
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class, // 密碼確認，你可以在做一些安全級別較高的修改時使用，例如說支付前進行密碼確認
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class, // 簽名認證，在找回密碼章節裡我們講過
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class, // 訪問節流，類似於 『1 分鐘只能請求 10 次』的需求，一般在 API 中使用
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class, // Laravel 自帶的強制用戶郵箱認證的中間件，為了更加貼近我們的邏輯，已被重寫
    ];
}
