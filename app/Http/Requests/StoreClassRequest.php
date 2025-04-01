<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClassRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Thay đổi nếu cần kiểm tra quyền
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'code' => 'required|string|max:255|unique:classes,code',
            'name' => 'required|string|max:255|unique:classes,name',
            'categories' => 'required|array',
            'categories.*' => 'integer|exists:categories,id',
            'description' => 'required|string|max:500',
            'teacher_id' => 'required|integer|exists:users,id',
        ];
    }

    /**
     * Custom error messages for validation.
     */
    public function messages(): array
    {
        return [
            'code.required' => 'Vui lòng nhập mã lớp',
            'code.unique' => 'Mã lớp đã tồn tại, vui lòng chọn mã khác',
            'name.required' => 'Vui lòng nhập tên lớp!',
            'name.unique' => 'Lớp học đã tồn tại, vui lòng nhập tên khác',
            'categories.required' => 'Vui lòng chọn ít nhất một danh mục!',
            'categories.*.exists' => 'Danh mục đã chọn không tồn tại!',
            'description.string' => 'Mô tả lớp phải là chuỗi.',
            'description.required' => 'Vui lòng nhập mô tả lớp!',
            'description.max' => 'Mô tả lớp không được vượt quá 500 ký tự.',
            'teacher_id.required' => 'Vui lòng chọn giảng viên!',
            'teacher_id.exists' => 'Giảng viên không tồn tại!',
        ];
    }
}
