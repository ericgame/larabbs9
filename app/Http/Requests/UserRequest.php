<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; //所有權限都可通過
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name,' . Auth::id(),
            'email' => 'required|email',
            'introduction' => 'max:80',
            'avatar' => 'mimes:png,jpg,gif,jpeg|dimensions:min_width=208,min_height=208',
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => '用戶名已被佔用，請重新填寫。',
            'name.regex' => '用戶名只支持英文、數字、橫槓和下劃線。',
            'name.between' => '用戶名必須介於 3 - 25 個字之間。',
            'name.required' => '用戶名不能為空。',
            'email.required' => '郵箱不能為空。',
            'email.email' => '郵箱必須是有效的 E-mail。',
            'introduction.max' => '個人簡介最多80個字。',
            'avatar.mimes' => '頭像必須是 png, jpg, gif, jpeg 格式的圖片。',
            'avatar.dimensions' => '圖片的清晰度不夠，寬和高需要 208px 以上。',
        ];
    }
}
