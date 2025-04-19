<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
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
            'questions' => 'required|array',
            'questions.*.question' => 'required|string|min:3|max:500',
            'questions.*.choices' => 'required|array|min:2|max:4',
            'questions.*.choices.*' => 'required|string|min:1|max:255',
            'questions.*.correct_answer' => 'required|numeric|min:0',
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
            'questions.*.question.required' => 'The question field is required.',
            'questions.*.question.string' => 'The question must be a text.',
            'questions.*.question.min' => 'The question must be at least :min characters.',

            'questions.*.choices.required' => 'At least one choice is required for each question.',
            'questions.*.choices.min' => 'Each question must have at least :min choices.',
            'questions.*.choices.max' => 'Each question cannot have more than :max choices.',

            'questions.*.choices.*.required' => 'The choice text is required.',
            'questions.*.choices.*.string' => 'The choice must be a text.',

            'questions.*.correct_answer.required' => 'Please select a correct answer for each question.',
            'questions.*.correct_answer.numeric' => 'The correct answer must be properly selected.',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        // Ensure correct_answer is within the range of available choices for each question
        $questions = $this->input('questions', []);

        foreach ($questions as $index => $question) {
            if (isset($question['correct_answer']) && isset($question['choices'])) {
                $choicesCount = count($question['choices']);
                $correctAnswer = (int) $question['correct_answer'];

                // If correct_answer is out of range, set it to the last choice
                if ($correctAnswer >= $choicesCount) {
                    $questions[$index]['correct_answer'] = $choicesCount - 1;
                }
            }
        }

        $this->merge(['questions' => $questions]);
    }
}
