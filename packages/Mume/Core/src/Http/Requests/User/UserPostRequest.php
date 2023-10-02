<?php

namespace Mume\Core\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Mume\Core\Common\CommonConst;
use Mume\Core\Models\Role;

class UserPostRequest extends FormRequest
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
        $phoneRegex = CommonConst::VN_PHONE_NUMBER_REGEX;
        return [
            'username'              => 'required|max:100|unique:users',
            'email'                 => 'nullable|email',
            'password'              => 'required|min:6|max:100',
            'password_confirmation' => 'required|same:password|min:6|max:100',
            'name'                  => 'required',
            'phone_number'          => [
                'nullable',
                "regex:$phoneRegex",
            ],
            'gender'                => [
                'nullable',
                Rule::in([CommonConst::GENDER_FEMALE, CommonConst::GENDER_MALE, CommonConst::GENDER_OTHER]),
            ],
            'birth_date'            => 'nullable|date',
            'avatar'                => 'nullable|image|max:' . CommonConst::MAXIMUM_SIZE_AVATAR,
            'description'           => 'nullable',
            'is_active'             => [
                'nullable',
                Rule::in([CommonConst::IS_ACTIVE, CommonConst::IS_NOT_ACTIVE]),
            ],
            'role_id'               => [
                'required',
                Rule::in(Role::availableRoles()),
            ],
        ];
    }

    /**
     * @return array|string[]
     */
    public function messages(): array
    {
        return [
            'username.required'              => 'Tài khoản không được để trống',
            'username.unique'                => 'Tài khoản đã tổn tại',
            'username.max'                   => 'Tài khoản không được vượt quá 100 ký tự',
            'email.email'                    => 'Email không hợp lệ',
            'name.required'                  => 'Tên tài khoản không được để trống',
            'password.required'              => 'Mật khẩu không được để trống',
            'password.min'                   => 'Mật khẩu phải có tối thiểu 6 kí tự',
            'password.max'                   => 'Mật khẩu tối đa 100 kí tự',
            'password_confirmation.required' => 'Nhập lại mật khẩu không được để trống',
            'password_confirmation.min'      => 'Nhập lại mật khẩu phải có tối thiểu 6 kí tự',
            'password_confirmation.max'      => 'Nhập lại mật khẩu tối đa 100 kí tự',
            'password_confirmation.same'     => 'Nhập lại mật khẩu không khớp',
            'phone_number.regex'             => 'Số điện thoại không hợp lệ',
            'birth_date.date'                => 'Ngày sinh không hợp lệ',
            'role_id.required'               => 'Role của người dùng không được để trống',
            'role_id.in'                     => 'Role của người dùng không hợp lệ',
            'gender.in'                      => 'Giới tính không hợp lệ',
            'is_active.in'                   => 'Trạng thái hoạt động không hợp lệ',
            'avatar.image'                   => 'Hình ảnh không hợp lệ',
            'avatar.max'                     => __('core::validation.image_max'),
        ];
    }
}
