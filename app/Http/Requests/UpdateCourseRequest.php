<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'sometimes|string|max:255|unique:courses,code,' . $this->route('course'),
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'categories' => 'sometimes|array',
            'categories.*' => 'exists:categories,id',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'code.required' => 'The course code is required.',
            'code.unique' => 'The course code must be unique.',
            'code.string' => 'The course code must be a string.',
            'code.max' => 'The course code may not be greater than 255 characters.',
            'name.string' => 'The course name must be a string.',
            'name.max' => 'The course name may not be greater than 255 characters.',
            'name.required' => 'The course name is required.',
            'categories.required' => 'At least one category is required.',
            'categories.*.exists' => 'The selected category is invalid.',
            'description.string' => 'The description must be a string.',
            'description.max' => 'The description may not be greater than 255 characters.',
        ];
    }
}
