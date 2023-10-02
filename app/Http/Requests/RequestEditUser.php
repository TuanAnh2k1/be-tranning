<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestEditUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "name" => [
                "required", "min:5"
            ],
            "username" => [
                "required", "min:5"
            ],
            "password" => [
                "required", "min:5"
            ],
            "confirm_password" => [
                "required", "min:5", "same:password"
            ],
        ];
    }

    public function messages()
    {
        $required = 'Yêu cầu không bỏ trống!';
        $min = 'Ít nhất 5 ký tự!';
        $regex = 'Yêu cầu nhập đúng định dạng!';
        return [
            'name.required' =>  $required,
            'name.min' =>  $min,
            'username.required' => $required,
            'username.min' => $min,
            'confirm_password.required' =>  $required,
            'password.required' =>  $required,
            'confirm_password.min' =>  $min,
            'confirm_password.same' =>  "Nhập lại mật khẩu không trùng khớp",
            'password.min' =>  $min,
        ];
    }
}
