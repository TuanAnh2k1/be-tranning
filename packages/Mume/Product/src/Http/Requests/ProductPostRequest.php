<?php

    namespace Mume\Product\Http\Requests;

    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Validation\Rule;
    use Mume\Core\Common\CommonConst;
    use Mume\Product\Models\Product;

    class ProductPostRequest extends FormRequest
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
                'name'         => 'required|max:100|unique:products',
                'sku'          => 'required|max:45|unique:products',
                'price'        => 'nullable',
                'images'       => 'nullable',
                'status'       => [
                    'nullable',
                    Rule::in([Product::PRODUCT_STATUS_OUT_STOCK, Product::PRODUCT_STATUS_IN_STOCK]),
                ],
                'description'  => 'nullable',
                'category_ids' => 'nullable',
                'is_active'    => [
                    'nullable',
                    Rule::in([CommonConst::IS_ACTIVE, CommonConst::IS_NOT_ACTIVE]),
                ],
            ];
        }

        /**
         * @return array|string[]
         */
        public function messages(): array
        {
            return [
                'name.required' => 'Tên sản phẩm không được để trống',
                'name.max'      => 'Tên sản phẩm không được vượt quá 100 ký tự',
                'name.unique'   => 'Sản phẩm đã tồn tại',
                'sku.required'  => 'SKU sản phẩm không được để trống',
                'sku.max'       => 'SKU sản phẩm không được vượt quá 100 ký tự',
                'sku.unique'    => 'SKU đã tồn tại',
                'is_active.in'  => 'Trạng thái hoạt động không hợp lệ',
                'status.in'     => 'Trạng thái sản phẩm không hợp lệ',
            ];
        }
    }
