<?php

namespace App\Http\Controllers\Api\V1;

use Symfony\Component\HttpFoundation\Response;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Models\Choice;
use App\Models\CourseSubmit;
use App\Models\CourseAnswer;
use App\Models\CourseAssignment;

class CourseAssignmentController extends Controller
{
    public function getById($courseId)
    {
        $courseAssignment = CourseAssignment::where('course_id', $courseId)->get();

        $courseAssignment = $courseAssignment->map(function ($assignment) {
            $answers = CourseSubmit::where('course_assignment_id', $assignment->id)->where('student_id', Auth::user()->id)->get();
            return [
                'id' => $assignment->id,
                'video_url' => $assignment->video_url,
                'title' => $assignment->title,
                'description' => $assignment->description,
                'duration' => $assignment->duration ? $assignment->duration : "Không có",
                'isSubmitted' => $answers->count() >= 1 ? true : false,
            ];
        });

        return response()->json($courseAssignment, 200);
    }

    public function getStudentAssignmentPoint($courseId)
    {
        $courseAssignment = CourseAssignment::where('course_id', $courseId)->get();

        $courseAssignment = $courseAssignment->map(function ($assignment) {
            $answers = CourseSubmit::where('course_assignment_id', $assignment->id)->where('student_id', Auth::user()->id)->first();

            return [
                'id' => $assignment->id,
                'video_url' => $assignment->video_url,
                'title' => $assignment->title,
                'description' => $assignment->description,
                'duration' => $assignment->duration ? $assignment->duration : "Không có",
                'score' => $answers ? $answers->score : 0,
                'total_score' => $assignment->courseQuizzes->count(),
                'handed_date' => $answers ? Carbon::parse($answers->created_at) : null,
                'isSubmitted' => $answers ? true : false,
            ];
        });

        return response()->json($courseAssignment, 200);
    }

    public function getDetailAssignment($assignmentId)
    {
        $courseAssignment = CourseAssignment::find($assignmentId);

        if (!$courseAssignment) {
            return response()->json(['message' => 'Assignment not found'], 404);
        }

        $courseAssignment->load('courseQuizzes.quiz.choices');

        $response = [
            'id' => $courseAssignment->id,
            'video_url' => $courseAssignment->video_url,
            'title' => $courseAssignment->title,
            'description' => $courseAssignment->description,
            'duration' => $courseAssignment->duration ? $courseAssignment->duration : "0",
            'isSubmitted' => false,
            'questions' => $courseAssignment->courseQuizzes->map(function ($quiz) {
                return [
                    'id' => $quiz->id,
                    'question' => $quiz->quiz->question,
                    'choices' => $quiz->quiz->choices->map(function ($choice) {
                        return [
                            'id' => $choice->id,
                            'choice' => $choice->choice,
                            'is_correct' => $choice->is_correct,
                        ];
                    }),
                ];
            }),
        ];

        return response()->json($response, 200);
    }

    public function SubmitAssignment(Request $request)
    {
        $user = Auth::user();
        $answers = json_decode($request->answers, true);
        $totalScore = 0;

        $submit = CourseSubmit::create([
            'course_assignment_id' => $request->assignment_id,
            'student_id' => $user->id,
        ]);

        foreach ($answers as $answer) {
            CourseAnswer::create([
                'course_submit_id' => $submit->id,
                'quiz_id' => $answer['question_id'],
                'choice_id' => $answer['choice_id'],
            ]);

            $choice = Choice::find($answer['choice_id']);

            if ($choice && $choice->is_correct == 1) {
                $totalScore += 1;
            }


            $submit->score = $totalScore;
            $submit->save();

            return response()->json([
                'message' => 'Nộp bài thành công',
                'score' => $totalScore
            ], Response::HTTP_OK);
        }
    }
}
