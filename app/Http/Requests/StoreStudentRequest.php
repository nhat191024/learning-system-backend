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
            'student_id' => 'required|exists:users,id',
            'class_id' => 'required|exists:classes,id',
        ];
    }

    /**
     * Custom error messages for validation.
     */
    public function messages(): array
    {
        return [
            'student_id.required' => 'Vui lòng chọn học sinh!',
            'student_id.exists' => 'Học sinh không tồn tại!',
            'class_id.required' => 'Vui lòng chọn lớp học!',
            'class_id.exists' => 'Lớp học không tồn tại!',
        ];
    }
}
