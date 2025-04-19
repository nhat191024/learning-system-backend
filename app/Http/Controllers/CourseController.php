<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Models\Course;
use App\Models\QuizPackage;
use App\Models\CourseAssignment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\CourseEnrollment;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;

class CourseController extends Controller
{
    /**
     * Display a listing of courses and students.
     */
    public function index(Request $request)
    {
        $courses = Course::with('categories')->get();
        $categories = cache()->remember('categories', now()->addMinutes(10), function () {
            return Category::all();
        });

        return view('admin.course.index', compact('courses', 'categories'));
    }

    /**
     * Store a new course.
     */
    public function store(StoreCourseRequest $request)
    {
        DB::beginTransaction();
        try {
            $course = Course::create([
                'code' => $request->code,
                'name' => $request->name,
                'description' => $request->description,
                'status' => 'published',
            ]);

            foreach ($request->categories as $categoryId) {
                $course->categories()->attach($categoryId);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Added course successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Display course details.
     */
    public function detail($id, $assignment_id = null)
    {
        $course = Course::with(['categories', 'students', 'assignments'])->findOrFail($id);

        $assignments = CourseAssignment::where('course_id', $id)
            ->with('courseSubmits.student')
            ->get();

        $enrollments = CourseEnrollment::where('course_id', $id)
            ->with('user')
            ->get();

        $assignmentNames = $assignments->pluck('title')->values();

        $studentNames = $enrollments->pluck('user.name');

        $assignmentPoints = $studentNames->map(function ($studentName) use ($assignments) {
            $points = $assignments->map(function ($assignment) use ($studentName) {
                $submit = $assignment->courseSubmits->first(function ($submit) use ($studentName) {
                    return $submit->student->name === $studentName;
                });

                return [
                    'score' => $submit ? $submit->score : 0,
                ];
            })->values();

            $totalScores = $assignments->map(function ($assignment) {

                return [
                    'totalScore' => $assignment->courseQuizzes->count()
                ];
            })->values();

            return [
                'name' => $studentName,
                'points' => $points,
                'totalScore' => $totalScores,
            ];
        })->values();

        $categories = cache()->remember('categories', now()->addMinutes(10), function () {
            return Category::all();
        });

        $students = User::where('role_id', 3)
            ->whereDoesntHave('courseEnrollments', function ($query) use ($id) {
                $query->where('course_id', $id);
            })
            ->get();

        $quizPackages = QuizPackage::with(['quizzes' => function ($query) {
            $query->select('id', 'quiz_package_id');
        }])
            ->get()
            ->map(function ($package) {
                $package->quiz_count = $package->quizzes->count();
                return $package;
            });

        $selectedAssignment = null;
        if ($assignment_id) {
            $selectedAssignment = $assignments->find($assignment_id);
        }

        return view('admin.course.detail', compact(
            'course',
            'categories',
            'students',
            'assignmentPoints',
            'assignmentNames',
            'quizPackages',
            'selectedAssignment'
        ));
    }

    /**
     * Update course details.
     */
    public function update(UpdateCourseRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $course = Course::findOrFail($id);

            if (!$course) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Khóa học không tồn tại.');
            }

            $course->code = $request->code;
            $course->name = $request->name;
            $course->description = $request->description;
            $course->status = 'published';
            $course->save();

            if ($request->has('categories')) {
                $course->categories()->sync($request->categories);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Update course successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Change course status.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $course = Course::findOrFail($id);

            if (!$course) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Course not found.');
            }

            $course->status = $course->status === 'published' ? 'archived' : 'published';
            $course->save();

            DB::commit();
            return redirect()->back()->with('success', 'Updated course status successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Export course details to Excel.
     */
    public function export($id)
    {
        $course = Course::find($id)->load(['categories', 'students']);

        $categories = $course->categories->pluck('name')->implode(', ');
        $students = $course->students;

        $data = [
            ['Thông tin lớp học'],
            ['Tên lớp', $course->name],
            ['Mã lớp', $course->code],
            ['Trạng thái', $course->status === 'published' ? 'Mở khoá' : 'Khoá'],
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

        $fileName = 'course-' . $course->id . '-details.xlsx';

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
