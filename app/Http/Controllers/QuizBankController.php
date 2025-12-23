<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\StoreQuizBankRequest;
use App\Http\Requests\UpdateQuizBankRequest;

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
        return view('admin.quiz_bank.index', compact('quizBank', 'categories'));
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

    public function store(StoreQuizBankRequest $request)
    {
        DB::beginTransaction();
        try {
            $createQuizBank = QuizPackage::create([
                'creator_id' => Auth::id(),
                'title' => $request->title,
                'description' => $request->description,
                'quiz_id_range' => 0,
                'status' => 'published',
                'type' => $request->type,
            ]);

            foreach ($request->categories as $category) {
                QuizPackageCategory::insert([
                    'quiz_package_id' => $createQuizBank->id,
                    'category_id' => $category,
                ]);
            }
            DB::commit();
            return redirect()->back()->with('success', 'Added quiz bank successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $th->getMessage());
        }
    }

    public function edit($id)
    {
        $quizBank = QuizPackage::with('categories')->findOrFail($id);
        $categories = Category::orderBy('created_at', 'DESC')->get();
        return view('admin.quiz_bank.edit', compact('quizBank', 'categories'));
    }

    public function update(UpdateQuizBankRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $quizBank = QuizPackage::findOrFail($id);
            $quizBank->update([
                'title' => $request->title,
                'description' => $request->description,
                'type' => $request->type,
            ]);

            QuizPackageCategory::where('quiz_package_id', $id)->delete();

            foreach ($request->categories as $category) {
                QuizPackageCategory::insert([
                    'quiz_package_id' => $id,
                    'category_id' => $category,
                ]);
            }
            DB::commit();
            return redirect()->route('admin.quizBank.index')->with('success', 'Updated quiz bank successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $th->getMessage());
        }
    }
}
