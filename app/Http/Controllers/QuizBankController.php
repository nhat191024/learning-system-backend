<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestionRequest;

use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\Quiz;
use App\Models\User;
use App\Models\Choice;
use App\Models\Category;
use App\Models\QuizPackage;
use App\Models\AssignmentQuiz;
use App\Models\QuizPackageCategory;

class QuizBankController extends Controller
{
    public function index()
    {
        $quizBank = QuizPackage::orderBy('created_at', 'DESC')->with('creator', 'categories')->get();
        $categories = Category::orderBy('created_at', 'DESC')->get();
        $quizzes = Quiz::orderBy('created_at', 'DESC')->get();
        $profile = User::findOrFail(Auth::id());
        return view('admin.quiz_bank.index', compact('quizBank', 'categories', 'quizzes', 'profile'));
    }

    public function questions($id)
    {
        $quizBankId = $id;
        $quizBank = QuizPackage::with('quizzes.choices')->findOrFail($id);
        $questions = $quizBank->quizzes->map(function ($quiz) {
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
        return view('admin.quiz_bank.question', compact('questions', 'quizBankId'));
    }

    public function questionsStore(StoreQuestionRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            foreach ($request->questions as $question) {
                $quiz = Quiz::create([
                    'question' => $question['question'],
                    'quiz_package_id' => $id,
                ]);

                foreach ($question['choices'] as $index => $choice) {
                    $isCorrect = $index == $question['correct_answer'] ? 1 : 0;
                    $quiz->choices()->create([
                        'choice' => $choice,
                        'is_correct' => $isCorrect,
                    ]);
                }
            }
            DB::commit();
            return redirect()->back()->with('success', 'Added questions successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $th->getMessage());
        }
    }

    public function questionDestroy($id)
    {
        DB::beginTransaction();
        try {
            $quiz = Quiz::findOrFail($id);
            if ($quiz) {
                $quiz->delete();
                $choices = Choice::where('quiz_id', $quiz->id)->get();
                if ($choices) {
                    foreach ($choices as $choice) {
                        $choice->delete();
                    }
                }
                DB::commit();
                return redirect()->back()->with('success', 'Deleted question successfully!');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete question!');
        }
    }


    public function createQuizBank(Request $request)
    {
        dd($request->all());
        try {
            $createQuizBank = QuizPackage::create([
                'creator_id' => Auth::id(),
                'title' => $request->quiz_name,
                'description' => $request->quiz_description,
                'quiz_id_range' => $request->quiz_id_range,
                'status' => 'published',
                'type' => $request->type,
            ]);

            foreach ($request->categories as $category) {
                QuizPackageCategory::insert([
                    'quiz_package_id' => $createQuizBank->id,
                    'category_id' => $category,
                ]);
            }
            return redirect()->route('quiz-bank.index')->with('success', 'Tạo kho quiz thành công!');
        } catch (\Throwable $th) {
            return redirect()->route('quiz-bank.index')->with('error', 'Tạo kho quiz thất bại!');
        }
    }

    public function updateQuizBank(Request $request)
    {
        if (Auth::check()) {
            // try {
            $updateQuizBank = QuizPackage::find($request->quiz_id);

            if (!$updateQuizBank) {
                return redirect()->route('quiz-bank.index')->with('error', 'Kho quiz không tồn tại!');
            }

            $updateQuizBank->update([
                'creator_id' => Auth::id(),
                'title' => $request->quiz_name,
                'description' => $request->quiz_description,
                'quiz_id_range' => $request->quiz_id_range,
                'status' => 'published',
                'type' => $request->type,
            ]);

            QuizPackageCategory::where('quiz_package_id', $updateQuizBank->id)->delete();

            if ($request->categories) {
                foreach ($request->categories as $category) {
                    QuizPackageCategory::create([
                        'quiz_package_id' => $updateQuizBank->id,
                        'category_id' => $category,
                    ]);
                }
            }

            return redirect()->route('quiz-bank.index')->with('success', 'Cập nhật kho quiz thành công!');
            // } catch (\Throwable $th) {
            return redirect()->route('quiz-bank.index')->with('error', 'Cập nhật kho quiz thất bại!')->with('message', $th->getMessage());
            // }
        } else {
            return redirect()->route('admin.login');
        }
    }

    public function hiddenQuizBank($id)
    {
        if (Auth::check()) {
            $hiddenQuizBank = QuizPackage::where('id', $id)->update(['type' => 'private']);

            if ($hiddenQuizBank) {
                return redirect()->route('quiz-bank.index')->with('success', 'Ẩn kho quiz thành công!');
            } else {
                return redirect()->route('quiz-bank.index')->with('error', 'Ẩn kho quiz thất bại!');
            }
        } else {
            return redirect()->route('admin.login');
        }
    }

    public function showQuizBank($id)
    {
        if (Auth::check()) {
            $showQuizBank = QuizPackage::where('id', $id)->update(['type' => 'public']);

            if ($showQuizBank) {
                return redirect()->route('quiz-bank.index')->with('success', 'Hiển thị kho quiz thành công!');
            } else {
                return redirect()->route('quiz-bank.index')->with('error', 'Hiển thị kho quiz thất bại!');
            }
        } else {
            return redirect()->route('admin.login');
        }
    }
}
