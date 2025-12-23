<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $userId = $this->route('id');

        return [
            'email' => 'sometimes|email|unique:users,email,' . $userId,
            'avatar' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'sometimes|string|max:255',
            'gender' => 'sometimes|in:male,female,other',
            'role_id' => 'sometimes|in:1,2,3,4',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email này đã được sử dụng.',
            'avatar.image' => 'Avatar phải là tệp hình ảnh.',
            'avatar.mimes' => 'Avatar chỉ chấp nhận các định dạng jpeg, png, jpg, gif.',
            'avatar.max' => 'Avatar không được vượt quá 2MB.',
            'name.required' => 'Tên người dùng là bắt buộc.',
            'gender.required' => 'Giới tính là bắt buộc.',
            'gender.in' => 'Giới tính không hợp lệ.',
            'role_id.required' => 'Vui lòng chọn vai trò.',
            'role_id.in' => 'Vai trò không hợp lệ.',
        ];
    }
}
