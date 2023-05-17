<?php

namespace App\Http\Requests\Api;

class SocialAuthorizationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'code' => 'required_without:access_token|string', // 如果 access_token 沒填寫，code 必填
            'access_token' => 'required_without:code|string', // 如果 code 沒填寫，access_token 必填
        ];

        // 如果第三方API為 wechat 而且 沒填寫 code 則 openid 必填
        if ($this->social_type == 'wechat' && !$this->code) {
            $rules['openid'] = 'required|string';
        }

        return $rules;
    }
}
