<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
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
            'students' => 'required|array',
            'students*' => 'required|exists:users,id',
        ];
    }

    /**
     * Custom error messages for validation.
     */
    public function messages(): array
    {
        return [
            'students.required' => 'Vui lòng chọn ít nhất một học sinh.',
            'students.array' => 'Dữ liệu không hợp lệ.',
            'students*.required' => 'Học sinh không được để trống.',
            'students*.exists' => 'Học sinh không tồn tại trong hệ thống.',
        ];
    }
}
