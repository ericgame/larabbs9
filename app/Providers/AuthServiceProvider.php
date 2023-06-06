<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
		 \App\Models\Reply::class => \App\Policies\ReplyPolicy::class,
		 \App\Models\Topic::class => \App\Policies\TopicPolicy::class,
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // 修改策略自動發現的邏輯
        Gate::guessPolicyNamesUsing(function ($modelClass) {
            // 動態返回模型對應的策略名稱，如：// 'App\Model\User' => 'App\Policies\UserPolicy',
            return 'App\Policies\\'.class_basename($modelClass).'Policy';
        });

        // Passport
        // Passport::routes(); // Passport 的路由 (此行不用寫，否則會報錯)
        Passport::tokensExpireIn(now()->addDays(15)); // access_token 過期時間(15天)
        Passport::refreshTokensExpireIn(now()->addDays(30)); // refreshTokens 過期時間(30天)
    }
}
