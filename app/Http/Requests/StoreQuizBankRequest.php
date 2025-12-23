<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuizBankRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'type' => 'required|in:public,private',
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The title field is required.',
            'title.string' => 'The title must be a string.',
            'title.max' => 'The title may not be greater than :max characters.',

            'description.string' => 'The description must be a string.',
            'description.max' => 'The description may not be greater than :max characters.',

            'categories.required' => 'At least one category is required.',
            'categories.array' => 'The categories must be an array.',
            'categories.*.exists' => 'One or more selected categories do not exist.',

            'type.required' => 'The type field is required.',
            'type.in' => 'The selected type is invalid.',
        ];
    }
}
