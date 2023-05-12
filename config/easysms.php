<?php

return [
    // HTTP 請求的超時時間（秒）
    'timeout' => 10.0,

    // 默認發送配置
    'default' => [
        // 網關調用策略，默認：順序調用
        'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

        // 默認可用的發送網關
        'gateways' => [
            'aliyun',
        ],
    ],
    // 可用的網關配置
    'gateways' => [
        'errorlog' => [
            'file' => '/tmp/easy-sms.log',
        ],
        'aliyun' => [
            'access_key_id' => env('SMS_ALIYUN_ACCESS_KEY_ID'),
            'access_key_secret' => env('SMS_ALIYUN_ACCESS_KEY_SECRET'),
            'sign_name' => 'Larabbs',
            'templates' => [
                'register' => env('SMS_ALIYUN_TEMPLATE_REGISTER'),
            ]
        ],
    ],
];