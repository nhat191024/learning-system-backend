<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAssignmentRequest extends FormRequest
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
        $baseRules = [
            'type' => ['required', 'string', Rule::in(['quiz', 'lab'])],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'duration' => ['required', 'integer', 'min:1'],
            'start_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ];

        // Add quiz-specific validation rules when the type is 'quiz'
        if ($this->input('type') === 'quiz') {
            $baseRules['quiz_package_id'] = ['required', 'exists:quiz_packages,id'];
            $baseRules['question_count'] = ['required', 'integer', 'min:1'];
        }

        return $baseRules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'type.required' => 'Assignment type is required',
            'type.in' => 'Assignment type must be either quiz or lab',
            'title.required' => 'Title is required',
            'title.max' => 'Title cannot exceed 255 characters',
            'duration.required' => 'Duration is required',
            'duration.integer' => 'Duration must be a number',
            'duration.min' => 'Duration must be at least 1 minute',
            'due_date.after_or_equal' => 'Due date must be the same as or after the start date',
            'quiz_package_id.required' => 'Please select a quiz package',
            'quiz_package_id.exists' => 'The selected quiz package does not exist',
            'question_count.required' => 'Number of questions is required',
            'question_count.integer' => 'Number of questions must be a number',
            'question_count.min' => 'Number of questions must be at least 1',
        ];
    }
}
