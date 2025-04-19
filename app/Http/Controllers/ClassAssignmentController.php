<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\StoreAssignmentRequest;

use App\Models\ClassAssignment;
use App\Models\ClassSubmit;
use App\Models\QuizPackage;
use App\Models\AssignmentQuiz;


class ClassAssignmentController extends Controller
{
    /**
     * Display a class assignment.
     */
    public function detail($id, $classId)
    {
        try {
            $assignment = ClassAssignment::where('id', $id)->with('quizzes.choices')->first();

            $questions = $assignment->quizzes->map(function ($quiz) {
                return [
                    'id' => $quiz->id,
                    'question' => $quiz->question,
                    "choices" => $quiz->choices->map(function ($choice) {
                        return [
                            'id' => $choice->id,
                            'choice' => $choice->choice,
                            'isCorrect' => $choice->is_correct,
                        ];
                    }),
                ];
            });


            return view('admin.class.assignment_detail', compact('classId', 'questions'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Store a new class assignment.
     */
    public function store(StoreAssignmentRequest $request, $classId)
    {
        DB::beginTransaction();
        try {
            $assignment = ClassAssignment::create([
                'class_id' => $classId,
                'type' => $request->type,
                'title' => $request->title,
                'description' => $request->description,
                'duration' => $request->duration,
                'start_date' => $request->start_date . ' ' . $request->start_time . ':00',
                'due_date' => $request->due_date . ' ' . $request->due_time . ':00',
                'status' => 'published',
            ]);

            if ($request->type === 'lab') {
                DB::commit();
                return redirect()->back()->with('success', 'Assignment created successfully!');
            }

            $package = QuizPackage::find($request->quiz_package_id);
            $numberOfQuestions = $request->question_count;
            for ($i = 0; $i < $numberOfQuestions; $i++) {
                $quiz = $package->quizzes->random();
                AssignmentQuiz::create([
                    'assignment_id' => $assignment->id,
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
    public function destroy(Request $request, $id)
    {
        try {
            $assignment = ClassAssignment::find($id);
            if (!$assignment) {
                return redirect()->back()->with('error', 'Không tìm thấy bài tập.');
            }

            $assignment->status = $assignment->status === 'published' ? 'closed' : 'published';
            $assignment->save();

            return redirect()->back()->with('success', 'Change assignment status successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
