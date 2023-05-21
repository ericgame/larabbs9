<?php

namespace App\Http\Requests\Api;

class UserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        switch($this->method()) {
            case 'POST':
                return [
                    'name' => 'required|between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name',
                    'password' => 'required|alpha_dash|min:6',
                    'verification_key' => 'required|string',
                    'verification_code' => 'required|string',
                ];
                break;
            case 'PATCH':
                $userId = auth('api')->id();
                return [
                    'name' => 'between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name,'.$userId,
                    'email' => 'email|unique:users,email,'.$userId, // email在users資料表的email欄位必須是唯一的，除了$userId(與users資料表的id欄位值相同的值)之外
                    'introduction' => 'max:80',
                    'avatar_image_id' => 'exists:images,id,type,avatar,user_id,'.$userId, // avatar_image_id要存在於images資料表的id欄位，並且type欄位值為avatar，並且user_id欄位值為$userId
                ];
                break;
        }
    }

    public function attributes()
    {
        return [
            'verification_key' => '短信驗證碼 key',
            'verification_code' => '短信驗證碼',
        ];
    }

    public function messages()
    {
        return [
            'name.unique'=> '用戶名已被佔用，請重新填寫。',
            'name.regex'=> '用戶名只支持英文、數字、橫桿和下劃線。',
            'name.between'=> '用戶名必須介於 3 - 25 個字符之間。',
            'name.required'=> '用戶名不能為空。',
        ];
    }
}
