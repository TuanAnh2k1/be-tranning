<?php

namespace Mume\Core\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserPasswordUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'old_password'              => 'required|min:6|max:100',
            'new_password'              => 'required|min:6|max:100|different:old_password',
            'new_password_confirmation' => 'required|same:new_password|min:6|max:100',
        ];
    }

    /**
     * @return array|string[]
     */
    public function messages(): array
    {
        return [
            'old_password.required'              => 'Mật khẩu cũ không được để trống',
            'old_password.min'                   => 'Mật khẩu cũ phải có tối thiểu 6 kí tự',
            'old_password.max'                   => 'Mật khẩu cũ tối đa 100 kí tự',
            'new_password.required'              => 'Mật khẩu mới không được để trống',
            'new_password.different'             => 'Mật khẩu mới phải khác mật khẩu cũ',
            'new_password.min'                   => 'Mật khẩu mới phải có tối thiểu 6 kí tự',
            'new_password.max'                   => 'Mật khẩu mới tối đa 100 kí tự',
            'new_password_confirmation.required' => 'Nhập lại mật khẩu không được để trống',
            'new_password_confirmation.min'      => 'Nhập lại mật khẩu phải có tối thiểu 6 kí tự',
            'new_password_confirmation.max'      => 'Nhập lại mật khẩu tối đa 100 kí tự',
            'new_password_confirmation.same'     => 'Nhập lại mật khẩu không khớp',
        ];
    }
}
