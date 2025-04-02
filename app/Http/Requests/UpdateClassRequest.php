<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClassRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Change this if you need to implement authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $id = $this->route('id'); // Get the class ID from the route

        return [
            'code' => 'sometimes|string|max:255|unique:classes,code,' . $id,
            'name' => 'sometimes|string|max:255|unique:classes,name,' . $id,
            'categories' => 'sometimes|array',
            'categories.*' => 'integer|exists:categories,id',
            'description' => 'sometimes|string|max:500',
            'teacher_id' => 'sometimes|integer|exists:users,id',
        ];
    }

    /**
     * Custom error messages for validation.
     */
    public function messages(): array
    {
        return [
            'code.unique' => 'Mã lớp đã tồn tại, vui lòng chọn mã khác',
            'name.unique' => 'Lớp học đã tồn tại, vui lòng nhập tên khác',
            'categories.required' => 'Vui lòng chọn ít nhất một danh mục!',
            'categories.*.exists' => 'Danh mục đã chọn không tồn tại!',
            'description.string' => 'Mô tả lớp phải là chuỗi.',
            'description.max' => 'Mô tả lớp không được vượt quá 500 ký tự.',
            'teacher_id.exists' => 'Giảng viên không tồn tại!',
        ];
    }
}
