<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Student;
use App\Models\Result;
use App\Models\Classes;
use App\Services\GradeCalculator;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $teacher = auth()->user()->teacher;

        if (!$teacher) {
            return view('teacher.dashboard', [
                'assignedSubjects' => collect(),
                'totalStudents' => 0,
                'totalResults' => 0,
            ]);
        }

        $data = [
            'assignedSubjects' => $teacher->subjects()->with('class')->get(),
            'totalStudents' => Student::whereHas('class.subjects', function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            })->count(),
            'totalResults' => Result::whereHas('subject', function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            })->count(),
        ];

        return view('teacher.dashboard', $data);
    }

    public function subjects()
    {
        $teacher = auth()->user()->teacher;

        if (!$teacher) {
            return view('teacher.subjects', ['subjects' => collect()]);
        }

        $subjects = $teacher->subjects()->with('class')->get();

        return view('teacher.subjects', compact('subjects'));
    }

    public function manageResults(Subject $subject)
    {
        // Check if teacher owns this subject
        $teacher = auth()->user()->teacher;

        if (!$teacher || $subject->teacher_id !== $teacher->id) {
            abort(403, 'You are not authorized to manage results for this subject.');
        }

        $students = Student::where('class_id', $subject->class_id)
            ->with([
                'user',
                'results' => function ($q) use ($subject) {
                    $q->where('subject_id', $subject->id);
                }
            ])
            ->get();

        return view('teacher.manage-results', compact('subject', 'students'));
    }

    public function storeResult(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'total_marks' => 'required|numeric|min:1',
            'marks' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value > $request->input('total_marks')) {
                        $fail('Marks cannot exceed total marks (' . $request->input('total_marks') . ').');
                    }
                }
            ],
            'semester' => 'nullable|string',
            'academic_year' => 'required|integer|min:1901|max:2155',
            'remarks' => 'nullable|string',
        ]);

        // Calculate percentage and grade
        $calculation = GradeCalculator::calculate($validated['marks'], $validated['total_marks']);

        $result = Result::updateOrCreate(
            [
                'student_id' => $validated['student_id'],
                'subject_id' => $validated['subject_id'],
                'semester' => $validated['semester'] ?? 'Fall',
                'academic_year' => $validated['academic_year'],
            ],
            [
                'marks' => $validated['marks'],
                'total_marks' => $validated['total_marks'],
                'percentage' => $calculation['percentage'],
                'grade' => $calculation['grade'],
                'remarks' => $validated['remarks'] ?? null,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Result saved successfully!',
            'result' => $result,
        ]);
    }

    public function updateResult(Request $request, Result $result)
    {
        // Verify the teacher owns the subject this result belongs to
        $teacher = auth()->user()->teacher;
        if (!$teacher || $result->subject->teacher_id !== $teacher->id) {
            abort(403, 'You are not authorized to update this result.');
        }

        $validated = $request->validate([
            'total_marks' => 'required|numeric|min:1',
            'marks' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value > $request->input('total_marks')) {
                        $fail('Marks cannot exceed total marks (' . $request->input('total_marks') . ').');
                    }
                }
            ],
            'remarks' => 'nullable|string',
        ]);

        // Calculate percentage and grade
        $calculation = GradeCalculator::calculate($validated['marks'], $validated['total_marks']);

        $result->update([
            'marks' => $validated['marks'],
            'total_marks' => $validated['total_marks'],
            'percentage' => $calculation['percentage'],
            'grade' => $calculation['grade'],
            'remarks' => $validated['remarks'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Result updated successfully!',
            'result' => $result,
        ]);
    }

    public function deleteResult(Result $result)
    {
        // Verify the teacher owns the subject this result belongs to
        $teacher = auth()->user()->teacher;
        if (!$teacher || $result->subject->teacher_id !== $teacher->id) {
            abort(403, 'You are not authorized to delete this result.');
        }

        $result->delete();

        return response()->json([
            'success' => true,
            'message' => 'Result deleted successfully!',
        ]);
    }
}