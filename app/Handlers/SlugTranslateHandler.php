<?php

namespace App\Handlers;

use GuzzleHttp\Client;
use Overtrue\Pinyin\Pinyin;
use Illuminate\Support\Str;

class SlugTranslateHandler
{
    public function translate($text)
    {
        // 實例化 HTTP 客戶端
        $http = new Client;

        // 初始化配置信息
        $api = 'http://api.fanyi.baidu.com/api/trans/vip/translate?';
        $appid = config('services.baidu_translate.appid');
        $key = config('services.baidu_translate.key');
        $salt = time();

        // 如果沒有配置百度翻譯，自動使用兼容的拼音方案
        if (empty($appid) || empty($key)) {
            return $this->pinyin($text);
        }


        // 根據文檔，生成 sign
        // http://api.fanyi.baidu.com/api/trans/product/apidoc
        // appid+q+salt+密鑰 的MD5值
        $sign = md5($appid . $text . $salt . $key);

        // 構建請求參數
        $query = http_build_query([
            "q" => $text,
            "from" => "zh",
            "to" => "en",
            "appid" => $appid,
            "salt" => $salt,
            "sign" => $sign,
        ]);


        // 發送 HTTP Get 請求
        $response = $http->get($api.$query);

        $result = json_decode($response->getBody(), true);


        /*
        獲取結果，如果請求成功，dd($result) 結果如下：

        array:3 [▼
            "from" => "zh"
            "to" => "en"
            "trans_result" => array:1 [▼
                0 => array:2 [▼
                    "src" => "XSS 安全漏洞"
                    "dst" => "XSS security vulnerability"
                ]
            ]
        ]
        */


        // 嘗試獲取獲取翻譯結果
        if (isset($result['trans_result'][0]['dst'])) {
            return Str::slug($result['trans_result'][0]['dst']);
        } else {
            // 如果百度翻譯沒有結果，使用拼音作為後備計劃。
            return $this->pinyin($text);
        }
    }

    public function pinyin($text)
    {
        return Str::slug(app(Pinyin::class)->permalink($text));
    }
}