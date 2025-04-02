<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Classes;
use App\Models\Category;
use App\Models\Enrollment;
use App\Models\Certificate;
use App\Models\ClassAssignment;

use App\Http\Requests\StoreClassRequest;
use App\Http\Requests\UpdateClassRequest;

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
            $teachers = User::where('role_id', 2)->get();
            $categories = Category::all();
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
        $class = Classes::find($id)->load(['categories', 'teacher', 'students', 'assignments']);
        $teachers = User::where('role_id', 2)->get();
        $categories = Category::all();
        return view('admin.class.detail', compact('class', 'teachers', 'categories'));
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

    public function assignmentDetailsJson($assignment_id)
    {
        $assignment = ClassAssignment::with(['quizzes.choices', 'submits.student'])->findOrFail($assignment_id);

        $questionsHtml = view('partials.assignment_questions', compact('assignment'))->render();
        $scoresHtml = view('partials.assignment_scores', compact('assignment'))->render();
        $resultsHtml = '';

        if ($assignment->type === 'lab') {
            $resultsHtml = view('partials.assignment_results', compact('assignment'))->render();
        }

        return response()->json([
            'assignment' => $assignment,
            'questionsHtml' => $questionsHtml,
            'scoresHtml' => $scoresHtml,
            'resultsHtml' => $resultsHtml,
        ]);
    }

    public function toggleClassStatus(Request $request, $id)
    {
        $class = Classes::findOrFail($id);

        $newStatus = $request->input('status');
        if (!in_array($newStatus, ['closed', 'published'])) {
            return redirect()->back()->with('error', 'Trạng thái không hợp lệ!');
        }

        $class->update(['status' => $newStatus]);

        $message = $newStatus === 'closed' ? 'Lớp đã được khóa.' : 'Lớp đã được mở khóa.';
        return redirect()->back()->with('success', $message);
    }

    public function export($id)
    {
        $class = Classes::with(['assignments'])->findOrFail($id);
        $students = $class->students()->where('role_id', 3)->get();

        $data = [
            ['Thông tin lớp học'],
            ['Tên lớp', $class->name],
            ['Mã lớp', $class->code],
            ['Trạng thái', $class->status === 'published' ? 'Mở khóa' : 'Khóa'],
            ['Giảng viên', $class->teacher->name],
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

    public function importConfirm(Request $request, $class_id)
    {
        $students = $request->input('students');
        $successMessages = [];
        $errorMessages = [];

        foreach ($students as $studentData) {
            $student = User::where('email', $studentData['email'])->first();

            if ($student && $student->role_id == 3) {
                // Kiểm tra nếu học sinh đã tồn tại trong lớp học
                $enrollmentExists = Enrollment::where('class_id', $class_id)
                    ->where('student_id', $student->id)
                    ->exists();

                if (!$enrollmentExists) {
                    Enrollment::firstOrCreate(
                        ['class_id' => $class_id, 'student_id' => $student->id]
                    );
                    $successMessages[] = "Học sinh {$student->name} ({$student->email}) đã được thêm thành công.";
                } else {
                    $errorMessages[] = "Học sinh {$student->name} ({$student->email}) đã tồn tại trong lớp.";
                }
            } else {
                $errorMessages[] = "Học sinh với email {$studentData['email']} không tồn tại hoặc không phải là học sinh.";
            }
        }

        return response()->json([
            'success' => true,
            'successMessages' => $successMessages,
            'errorMessages' => $errorMessages,
        ]);
    }

    public function template()
    {
        $headers = ['Tên học sinh', 'Email'];
        $fileName = 'student_import_template.xlsx';

        return Excel::download(new class([$headers]) implements FromArray {
            protected $data;

            public function __construct($data)
            {
                $this->data = $data;
            }

            public function array(): array
            {
                return [$this->data];
            }
        }, $fileName);
    }
}
