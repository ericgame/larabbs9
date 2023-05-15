<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Overtrue\EasySms\EasySms;
use App\Http\Requests\Api\VerificationCodeRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;

class VerificationCodesController extends Controller
{
    public function store(VerificationCodeRequest $request, EasySms $easySms)
    {
        $captchaCacheKey = 'captcha_'.$request->captcha_key;
        $captchaData = \Cache::get($captchaCacheKey);

        if (!$captchaData) {
            abort(403, '圖片驗證碼已失效');
        }

        if (!hash_equals($captchaData['code'], $request->captcha_code)) {
            // 驗證錯誤就清除緩存
            \Cache::forget($captchaCacheKey);
            throw new AuthenticationException('驗證碼錯誤');
        }

        $phone = $captchaData['phone'];

        if (!app()->environment('production')) {
            $code = '1234';
        } else {
            // 生成4位隨機數，左側補0
            $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);

            try {
                $result = $easySms->send($phone, [
                    'template' => config('easysms.gateways.aliyun.templates.register'),
                    'data' => [
                        'code' => $code
                    ],
                ]);
            } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
                $message = $exception->getException('aliyun')->getMessage();
                abort(500, $message ?: '短信發送異常');
            }
        }

        $smsKey = 'verificationCode_'.Str::random(15);
        $smsCacheKey = 'verificationCode_'.$smsKey;
        $expiredAt = now()->addMinutes(5);

        // 緩存驗證碼 5分鐘 過期
        \Cache::put($smsCacheKey, ['phone' => $phone, 'code' => $code], $expiredAt);

        // 清除圖片驗證碼緩存
        \Cache::forget($captchaCacheKey);

        return response()->json([
            'key' => $smsKey,
            'expired_at' => $expiredAt->toDateTimeString(),
        ])->setStatusCode(201);
    }
}
