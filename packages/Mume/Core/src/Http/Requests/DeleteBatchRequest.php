<?php

namespace Mume\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteBatchRequest extends FormRequest
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
            'ids' => 'required',
        ];
    }

    /**
     * @return array|string[]
     */
    public function messages(): array
    {
        return [
            'ids.required' => 'ID của đối tượng cần xóa không được để trống',
        ];
    }
}
