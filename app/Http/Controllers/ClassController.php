<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Classes;
use App\Models\Category;

use App\Http\Requests\StoreClassRequest;
use App\Http\Requests\UpdateClassRequest;
use App\Models\ClassAssignment;
use App\Models\Enrollment;
use App\Models\QuizPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;

class ClassController extends Controller
{
    /**
     * Display a listing of class and student.
     */
    public function index(Request $request)
    {
        try {
            $classes = Classes::with('categories', 'teacher')->get();
            $teachers = cache()->remember('teachers', now()->addMinutes(10), function () {
                return User::where('role_id', 2)->get();
            });
            $categories = cache()->remember('categories', now()->addMinutes(10), function () {
                return Category::all();
            });
            return view('admin.class.index', compact('classes', 'teachers', 'categories'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Store new class.
     */
    public function store(StoreClassRequest $request)
    {
        DB::beginTransaction();
        try {
            $class = Classes::create([
                'code' => $request->code,
                'name' => $request->name,
                'description' => $request->description,
                'teacher_id' => $request->teacher_id,
            ]);

            foreach ($request->categories as $categoryId) {
                $class->categories()->attach($categoryId);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Class added successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to add class: ' . $e->getMessage());
        }
    }

    /**
     * Show detail of the class
     */
    public function detail($id, $assignment_id = null)
    {
        $class = Classes::with(['categories', 'teacher', 'students.certificates', 'assignments'])->findOrFail($id);

        $assignments = ClassAssignment::where('class_id', $id)->with('submits.user')->get();
        $enrollment = Enrollment::where('class_id', $id)->with('student')->get();

        $assignmentName = $assignments->map(function ($assignment) {
            return $assignment->title;
        })->values();

        $students = $enrollment->map(function ($student) {
            return $student->student->name;
        });

        $assignmentPoint = $students->map(function ($studentName) use ($assignments) {
            $points = $assignments->map(function ($assignment) use ($studentName) {
                $submit = $assignment->submits->firstWhere('user.name', $studentName);
                if ($assignment->type == 'quiz') {
                    return [
                        'score' => $submit ? $submit->score : 0,
                    ];
                } else {
                    return [
                        'score' => $submit ? "Đã nộp bài" : "Chưa nộp bài",
                    ];
                }
            })->values();

            $totalScore = $assignments->map(function ($assignment) {
                if ($assignment->type == 'quiz') {
                    return [
                        'totalScore' => $assignment->quizzes->count()
                    ];
                } else {
                    return [
                        'totalScore' => "Không có tông điểm",
                    ];
                }
            })->values();

            return [
                'name' => $studentName,
                'points' => $points,
                'totalScore' => $totalScore,
            ];
        })->values();

        $teachers = cache()->remember('teachers', now()->addMinutes(10), function () {
            return User::where('role_id', 2)->get();
        });
        $categories = cache()->remember('categories', now()->addMinutes(10), function () {
            return Category::all();
        });
        $students = User::where('role_id', 3)
            ->whereDoesntHave('enrollments', function ($query) use ($class) {
                $query->where('class_id', $class->id);
            })
            ->get();

        // Eager load quizzes relationship with count
        $quizPackages = QuizPackage::with(['quizzes' => function ($query) {
            $query->select('id', 'quiz_package_id'); // Select only needed fields for efficiency
        }])->get();

        // Transform the packages to include quiz count explicitly
        $quizPackages = $quizPackages->map(function ($package) {
            $package->quiz_count = $package->quizzes->count();
            return $package;
        });

        return view('admin.class.detail', compact('class', 'teachers', 'categories', 'students', 'assignmentPoint', 'assignmentName', 'quizPackages'));
    }

    /**
     * Show the form for editing the specified class.
     */
    public function edit($id)
    {
        $class = Classes::findOrFail($id);
        $teachers = User::where('role_id', 2)->get();
        $categories = Category::all();
        return view('class.edit', compact('class', 'teachers', 'categories'));
    }

    /**
     * Update the specified class in storage.
     */
    public function update(UpdateClassRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $class = Classes::findOrFail($id);
            $class->update([
                'code' => $request->code,
                'name' => $request->name,
                'description' => $request->description,
                'teacher_id' => $request->teacher_id,
            ]);

            if ($request->has('categories')) {
                $class->categories()->sync($request->categories);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Update class successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Update class failed: ' . $e->getMessage());
        }
    }

    /**
     * Destroy the specified class.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $class = Classes::find($id);

            if (!$class) {
                return redirect()->back()->with('error', 'Class does not exist');
            }

            $newStatus = $class->status === 'published' ? 'closed' : 'published';
            $class->update(['status' => $newStatus]);

            DB::commit();

            $message = $newStatus === 'closed' ? 'Class has been locked!' : 'Class has been displayed!';
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Export class details to Excel.
     */
    public function export($id)
    {
        $class = Classes::find($id)->load(['categories', 'teacher', 'students']);

        $categories = $class->categories->pluck('name')->implode(', ');
        $students = $class->students;

        $data = [
            ['Thông tin lớp học'],
            ['Tên lớp', $class->name],
            ['Mã lớp', $class->code],
            ['Trạng thái', $class->status === 'published' ? 'Mở khoá' : 'Khoá'],
            ['Giảng viên', $class->teacher->name],
            ['Danh mục', $categories],

            [],
            ['Danh sách học sinh'],
            ['STT', 'Tên học sinh', 'Email'],
        ];

        foreach ($students as $key => $student) {
            $data[] = [
                $key + 1,
                $student->name,
                $student->email,
            ];
        }

        $fileName = 'class-' . $class->id . '-details.xlsx';

        return Excel::download(new class($data) implements FromArray {
            protected $data;

            public function __construct($data)
            {
                $this->data = $data;
            }

            public function array(): array
            {
                return $this->data;
            }
        }, $fileName);
    }
}
