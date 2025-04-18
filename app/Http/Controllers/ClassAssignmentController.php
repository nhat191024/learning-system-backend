<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use Illuminate\Http\Request;

use App\Models\ClassAssignment;
use App\Models\ClassSubmit;

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
