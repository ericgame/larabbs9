<?php

namespace App\Providers;

use Jpush\Client;
use Illuminate\Support\ServiceProvider;

class JpushServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        /*
          每次呼叫 Client 進行注入的時候，
          先檢查系統是否已經存在 Client 的實例，
          如果已經存在就回傳該實例，反之，建立、回傳一個新的實例。
        */
        $this->app->singleton(Client::class, function ($app) {
            return new Client(config('jpush.key'), config('jpush.secret'));
        });

        $this->app->alias(Client::class, 'jpush');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
