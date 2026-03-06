<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function dashboard()
    {
        $student = auth()->user()->student;

        if (!$student) {
            abort(403, 'Student profile not found.');
        }

        $results = Result::where('student_id', $student->id)
            ->with('subject')
            ->get();

        $data = [
            'student' => $student,
            'results' => $results,
            'totalSubjects' => $results->count(),
            'averagePercentage' => $results->avg('percentage'),
            'passedSubjects' => $results->where('percentage', '>=', 60)->count(),
            'failedSubjects' => $results->where('percentage', '<', 60)->count(),
        ];

        return view('student.dashboard', $data);
    }

    public function results()
    {
        $student = auth()->user()->student;

        if (!$student) {
            abort(403, 'Student profile not found.');
        }

        $results = Result::where('student_id', $student->id)
            ->with('subject')
            ->latest()
            ->paginate(15);

        return view('student.results', compact('results'));
    }

    public function downloadPDF()
    {
        $student = auth()->user()->student;

        if (!$student) {
            abort(403, 'Student profile not found.');
        }

        $results = Result::where('student_id', $student->id)
            ->with('subject')
            ->get();

        $data = [
            'student' => $student,
            'results' => $results,
            'averagePercentage' => $results->avg('percentage'),
        ];

        $pdf = Pdf::loadView('student.result-pdf', $data);

        return $pdf->download('result_' . $student->roll_number . '.pdf');
    }
}