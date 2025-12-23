<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseAssignmentRequest extends FormRequest
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
            'description' => 'nullable|string',
            'video_url' => 'required|url|max:255',
            'duration' => 'required|integer|min:1',
            'quiz_package_id' => 'required|exists:quiz_packages,id',
            'question_count' => 'required|integer|min:1',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The assignment title is required.',
            'title.string' => 'The assignment title must be a string.',
            'title.max' => 'The assignment title must not exceed 255 characters.',
            'video_url.required' => 'The video URL is required.',
            'video_url.url' => 'The video URL must be a valid URL.',
            'video_url.max' => 'The video URL must not exceed 255 characters.',
            'duration.required' => 'The duration is required.',
            'duration.integer' => 'The duration must be an integer.',
            'duration.min' => 'The duration must be at least 1 minute.',
            'quiz_package_id.required' => 'Please select a quiz package.',
            'quiz_package_id.exists' => 'The selected quiz package is invalid.',
            'question_count.required' => 'The number of questions is required.',
            'question_count.integer' => 'The number of questions must be an integer.',
            'question_count.min' => 'The number of questions must be at least 1.',
        ];
    }
}
