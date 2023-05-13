<?php

return [
    // 接口頻率限制
    'rate_limits' => [
        'access' => env('RATE_LIMITS', '60,1'), // 訪問頻率限制，次數/分鐘
        'sign' => env('SIGN_RATE_LIMITS', '10,1'), // 登錄相關，次數/分鐘
    ],
];