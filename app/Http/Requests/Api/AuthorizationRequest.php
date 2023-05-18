<?php

namespace App\Http\Requests\Api;

class AuthorizationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'username' => 'required|string',
            'password' => 'required|alpha_dash|min:6', // alpha_dash 驗證欄位值是否僅包含字母、數字、破折號（ - ）以及底線（ _ ）
        ];
    }
}
