<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\StoreStudentRequest;

class StudentController extends Controller
{
    /**
     * Store a new student in a class.
     */
    public function store(StoreStudentRequest $request)
    {
        DB::beginTransaction();
        try {
            $studentID = $request->student_id;
            $classID = $request->class_id;

            Enrollment::create([
                'student_id' => $studentID,
                'class_id' => $classID,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Thêm học sinh thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Thêm học sinh thất bại: ' . $e->getMessage());
        }
    }

    /**
     * Remove a student from a class.
     */
    public function destroy($class_id, $student_id)
    {
        DB::beginTransaction();
        try {
            $enrollment = Enrollment::where('class_id', $class_id)->where('student_id', $student_id)->first();

            if (!$enrollment) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Không tìm thấy học sinh này trong lớp.');
            }

            $enrollment->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Học sinh đã được xóa khỏi lớp.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Xóa học sinh thất bại: ' . $e->getMessage());
        }
    }
}
