<?php

namespace App\Http\Controllers;

use App\Models\CourseAssignment;
use App\Models\CourseQuiz;
use App\Models\QuizPackage;

use App\Http\Requests\StoreCourseAssignmentRequest;
use App\Models\Course;
use Illuminate\Support\Facades\DB;

class CourseAssignmentController extends Controller
{
    /**
     * Display a course assignment.
     */
    public function detail($id, $courseId)
    {
        try {
            $assignment = CourseAssignment::where('id', $id)->with('courseQuizzes.quiz.choices')->first();

            $questions = $assignment->courseQuizzes->map(function ($quiz) {
                return [
                    'id' => $quiz->id,
                    'question' => $quiz->quiz->question,
                    'choices' => $quiz->quiz->choices->map(function ($choice) {
                        return [
                            'id' => $choice->id,
                            'choice' => $choice->choice,
                            'isCorrect' => $choice->is_correct,
                        ];
                    }),
                ];
            });


            return view('admin.course.assignment_detail', compact('courseId', 'questions'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Store a new class assignment.
     */
    public function store(StoreCourseAssignmentRequest $request, $courseId)
    {
        DB::beginTransaction();
        try {
            $assignment = CourseAssignment::create([
                'course_id' => $courseId,
                'video_url' => $request->video_url,
                'title' => $request->title,
                'description' => $request->description,
                'duration' => $request->duration,
            ]);

            $package = QuizPackage::find($request->quiz_package_id);
            $numberOfQuestions = $request->question_count;

            if ($numberOfQuestions > $package->quizzes->count()) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Number of questions exceeds available quizzes in the package (' . $package->quizzes->count() . ')');
            }

            for ($i = 0; $i < $numberOfQuestions; $i++) {
                $quiz = $package->quizzes->random();
                CourseQuiz::create([
                    'course_assignment_id' => $assignment->id,
                    'quiz_id' => $quiz->id,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Assignment created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * change assignment status
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $assignment = CourseAssignment::find($id);
            if (!$assignment) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Không tìm thấy bài tập.');
            }

            $assignment->status = $assignment->status === 'published' ? 'closed' : 'published';
            $assignment->save();

            DB::commit();
            return redirect()->back()->with('success', 'Change assignment status successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
