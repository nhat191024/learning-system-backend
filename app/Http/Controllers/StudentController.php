<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Classes;
use App\Models\Enrollment;
use App\Models\Certificate;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use Spatie\LaravelPdf\Facades\Pdf;
use App\Http\Requests\StoreStudentRequest;

class StudentController extends Controller
{
    /**
     * Store a new student in a class.
     */
    public function store(StoreStudentRequest $request, $id)
    {
        DB::transaction(function () use ($request, $id) {
            foreach ($request->students as $student) {
                Enrollment::create([
                    'student_id' => $student,
                    'class_id' => $id,
                ]);
            }
        });

        return redirect()->back()->with('success', 'Student added successfully.');
    }

    /**
     * Remove a student from a class.
     */
    public function destroy($classId, $studentId)
    {
        DB::transaction(function () use ($classId, $studentId) {
            $enrollment = Enrollment::where('class_id', $classId)->where('student_id', $studentId)->firstOrFail();
            $enrollment->delete();
        });

        return redirect()->back()->with('success', 'Student removed from the class.');
    }

    /**
     * Generate a certificate for a student in a class.
     */
    public function certificate($classId, $studentId)
    {
        DB::beginTransaction();
        try {
            $existingCertificate = Certificate::where('student_id', $studentId)
                ->where('class_id', $classId)
                ->first();

            if ($existingCertificate) {
                throw new \Exception('Certificate already exists for this student in this class.');
            }

            $student = User::findOrFail($studentId);
            $class = Classes::findOrFail($classId);

            if (!$student || !$class) {
                throw new \Exception('Invalid student or class.');
            }

            $certificate = Certificate::create([
                'student_id' => $studentId,
                'class_id' => $classId,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Certificate generated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Destroy a certificate for a student in a class.
     */
    public function destroyCertificate($classId, $studentId)
    {
        DB::transaction(function () use ($classId, $studentId) {
            $certificate = Certificate::where('class_id', $classId)->where('student_id', $studentId)->firstOrFail();
            $certificate->delete();
        });

        return redirect()->back()->with('success', 'Certificate deleted successfully.');
    }
}
