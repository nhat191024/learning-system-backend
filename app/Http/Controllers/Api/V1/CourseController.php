<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Course;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CourseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $courses = Course::with('categories', 'enrollments')->where('status', "published")->get();

        $courses = $courses->map(function ($course) {
            return [
                'id' => $course->id,
                'code' => $course->code,
                'name' => $course->name,
                'description' => $course->description,
                'categories' => $course->categories->map(function ($category) {
                    return $category->name;
                }),
                'joined' => $course->enrollments->contains('student_id', Auth::id()) ? true : false,
                'createdAt' => $course->created_at,
            ];
        });

        return response()->json([
            'courses' => $courses,
        ], Response::HTTP_OK);
    }

    public function getCourseById($id)
    {
        $course = Course::with('categories')->find($id);

        return response()->json([
            'id' => $course->id,
            'code' => $course->code,
            'name' => $course->name,
            'description' => $course->description,
            'categories' => $course->categories->map(function ($category) {
                return $category->name;
            }),
            'createdAt' => $course->created_at,
        ], Response::HTTP_OK);
    }

    public function joinCourse($courseId)
    {
        $user = Auth::user();
        $course = Course::find($courseId);

        if (!$course) {
            return response()->json([
                'message' => 'Khoá học không tồn tại',
            ], Response::HTTP_NOT_FOUND);
        }

        if ($course->enrollments->contains('student_id', $user->id)) {
            return response()->json([
                'message' => 'Bạn đã tham gia khóa học này',
            ], Response::HTTP_BAD_REQUEST);
        }

        $course->enrollments()->create([
            'course_id' => $courseId,
            'student_id' => $user->id,
        ]);

        return response()->json([
            'message' => 'Tham gia khoá học thành công',
        ], Response::HTTP_OK);
    }
}
