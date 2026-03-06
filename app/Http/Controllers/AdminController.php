<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Result;
use App\Models\Classes;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $data = [
            'totalStudents' => Student::count(),
            'totalTeachers' => Teacher::count(),
            'totalSubjects' => Subject::count(),
            'totalResults' => Result::count(),
            'totalClasses' => Classes::count(),
            'recentResults' => Result::with(['student.user', 'subject'])
                ->latest()
                ->take(10)
                ->get(),
            'performanceData' => $this->getPerformanceData(),
        ];

        return view('admin.dashboard', $data);
    }

    private function getPerformanceData()
    {
        $grades = DB::table('results')
            ->select('grade', DB::raw('count(*) as count'))
            ->groupBy('grade')
            ->get();

        return [
            'labels' => $grades->pluck('grade'),
            'data' => $grades->pluck('count'),
        ];
    }

    // Students Management
    public function students()
    {
        $students = Student::with(['user', 'class'])->paginate(15);
        $classes = Classes::all();
        return view('admin.students.index', compact('students', 'classes'));
    }

    public function storeStudent(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'class_id' => 'required|exists:classes,id',
            'roll_number' => 'required|unique:students,roll_number',
            'phone' => 'nullable|string',
            'guardian_name' => 'nullable|string',
            'guardian_phone' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $role = Role::where('name', 'student')->first();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => $role->id,
                'phone' => $validated['phone'] ?? null,
            ]);

            Student::create([
                'user_id' => $user->id,
                'class_id' => $validated['class_id'],
                'roll_number' => $validated['roll_number'],
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'guardian_name' => $validated['guardian_name'] ?? null,
                'guardian_phone' => $validated['guardian_phone'] ?? null,
                'address' => $validated['address'] ?? null,
            ]);
        });

        return redirect()->route('admin.students')->with('success', 'Student added successfully!');
    }

    public function updateStudent(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->user_id,
            'class_id' => 'required|exists:classes,id',
            'roll_number' => 'required|unique:students,roll_number,' . $student->id,
            'phone' => 'nullable|string',
            'guardian_name' => 'nullable|string',
            'guardian_phone' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string',
            'status' => 'nullable|in:active,inactive,suspended',
        ]);

        DB::transaction(function () use ($validated, $student) {
            $student->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
            ]);

            $student->update([
                'class_id' => $validated['class_id'],
                'roll_number' => $validated['roll_number'],
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'guardian_name' => $validated['guardian_name'] ?? null,
                'guardian_phone' => $validated['guardian_phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'status' => $validated['status'] ?? 'active',
            ]);
        });

        return redirect()->route('admin.students')->with('success', 'Student updated successfully!');
    }

    public function updateStudentStatus(Request $request, Student $student)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $student->update(['status' => $validated['status']]);

        return redirect()->route('admin.students')->with('success', "Student status updated to " . ucfirst($validated['status']) . " successfully!");
    }

    public function deleteStudent(Student $student)
    {
        if ($student->user) {
            $student->user->delete();
        }
        $student->delete();
        return redirect()->route('admin.students')->with('success', 'Student deleted successfully!');
    }

    // Teachers Management
    public function teachers()
    {
        // Only fetch teachers that have an active associated user record
        $teachers = Teacher::whereHas('user')->with('user')->paginate(15);
        return view('admin.teachers.index', compact('teachers'));
    }

    public function storeTeacher(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'employee_id' => 'required|unique:teachers,employee_id',
            'phone' => 'nullable|string',
            'qualification' => 'nullable|string',
            'specialization' => 'nullable|string',
            'joining_date' => 'nullable|date',
        ]);

        DB::transaction(function () use ($validated) {
            $role = Role::where('name', 'teacher')->first();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => $role->id,
                'phone' => $validated['phone'] ?? null,
            ]);

            Teacher::create([
                'user_id' => $user->id,
                'employee_id' => $validated['employee_id'],
                'qualification' => $validated['qualification'] ?? null,
                'specialization' => $validated['specialization'] ?? null,
                'joining_date' => $validated['joining_date'] ?? now(),
            ]);
        });

        return redirect()->route('admin.teachers')->with('success', 'Teacher added successfully!');
    }

    public function updateTeacher(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->user_id,
            'employee_id' => 'required|unique:teachers,employee_id,' . $teacher->id,
            'phone' => 'nullable|string',
            'qualification' => 'nullable|string',
            'specialization' => 'nullable|string',
            'joining_date' => 'nullable|date',
            'password' => 'nullable|string|min:8|confirmed',
            'password_confirmation' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $request, $teacher) {
            $userFields = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
            ];

            // Only hash & update the password if a new one was provided
            if (!empty($validated['password'])) {
                $userFields['password'] = Hash::make($validated['password']);
            }

            $teacher->user->update($userFields);

            $teacher->update([
                'employee_id' => $validated['employee_id'],
                'qualification' => $validated['qualification'] ?? null,
                'specialization' => $validated['specialization'] ?? null,
                'joining_date' => $validated['joining_date'] ?? null,
            ]);
        });

        return redirect()->route('admin.teachers')->with('success', 'Teacher updated successfully!');
    }

    public function deleteTeacher(Teacher $teacher)
    {
        if ($teacher->user) {
            $teacher->user->delete();
        }
        $teacher->delete();
        return redirect()->route('admin.teachers')->with('success', 'Teacher deleted successfully!');
    }

    // Classes Management
    public function classes()
    {
        $classes = Classes::withCount(['students', 'subjects'])->with(['students.user'])->paginate(15);
        $allStudents = Student::with('user')->orderBy('class_id')->get();

        // Pre-build plain arrays so Blade never sees fn() inside @json()
        $studentsForJs = $allStudents->map(function ($s) {
            return [
                'id' => $s->id,
                'name' => $s->user?->name ?? 'Deleted User',
                'roll' => $s->roll_number,
                'class_id' => $s->class_id,
            ];
        })->values()->all();

        $classesForJs = Classes::pluck('name', 'id')->all();

        return view('admin.classes.index', compact('classes', 'allStudents', 'studentsForJs', 'classesForJs'));
    }

    public function storeClass(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:classes,name',
            'description' => 'nullable|string',
        ]);

        Classes::create($validated);

        return redirect()->route('admin.classes')->with('success', 'Class added successfully!');
    }

    public function updateClass(Request $request, Classes $class)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:classes,name,' . $class->id,
            'description' => 'nullable|string',
        ]);

        $class->update($validated);

        return redirect()->route('admin.classes')->with('success', 'Class updated successfully!');
    }

    public function deleteClass(Classes $class)
    {
        $class->delete();
        return redirect()->route('admin.classes')->with('success', 'Class deleted successfully!');
    }

    public function addStudentToClass(Request $request, Classes $class, Student $student)
    {
        // Move the student into this class
        $student->update(['class_id' => $class->id]);

        $name = $student->user?->name ?? 'Student';
        return redirect()->route('admin.classes')->with('success', "Student \"{$name}\" has been added to {$class->name}.");
    }

    public function removeStudentFromClass(Request $request, Classes $class, Student $student)
    {
        // Verify the student actually belongs to this class
        if ($student->class_id !== $class->id) {
            return redirect()->route('admin.classes')->with('error', 'Student does not belong to this class.');
        }

        // Move them to the first available *other* class
        $otherClass = Classes::where('id', '!=', $class->id)->first();
        if ($otherClass) {
            $student->update(['class_id' => $otherClass->id]);
            $name = $student->user?->name ?? 'Student';
            return redirect()->route('admin.classes')->with('success', "Student \"{$name}\" has been removed from {$class->name} and moved to {$otherClass->name}.");
        } else {
            return redirect()->route('admin.classes')->with('error', 'Cannot remove student, no other classes exist to move them into.');
        }
    }

    // Subjects Management
    const MAX_SUBJECTS_PER_TEACHER = 2;

    public function subjects()
    {
        $subjects = Subject::with(['class', 'teacher.user'])->paginate(15);
        $classes = Classes::all();
        $teachers = Teacher::with('user')->withCount('subjects')->get();
        return view('admin.subjects.index', compact('subjects', 'classes', 'teachers'));
    }

    public function storeSubject(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:subjects,code',
            'class_id' => 'required|exists:classes,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'total_marks' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        // Enforce max subjects per teacher
        if (!empty($validated['teacher_id'])) {
            $currentCount = Subject::where('teacher_id', $validated['teacher_id'])->count();
            if ($currentCount >= self::MAX_SUBJECTS_PER_TEACHER) {
                return redirect()->route('admin.subjects')
                    ->withInput()
                    ->with('error', 'This teacher already has ' . $currentCount . ' subject(s) assigned. Maximum allowed is ' . self::MAX_SUBJECTS_PER_TEACHER . '. Please choose a different teacher.');
            }
        }

        Subject::create($validated);

        return redirect()->route('admin.subjects')->with('success', 'Subject added successfully!');
    }

    public function updateSubject(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:subjects,code,' . $subject->id,
            'class_id' => 'required|exists:classes,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'total_marks' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        // Enforce max subjects per teacher (only when teacher is being changed or newly assigned)
        if (!empty($validated['teacher_id'])) {
            $newTeacherId = (int) $validated['teacher_id'];
            $isChangingTeacher = $subject->teacher_id !== $newTeacherId;

            if ($isChangingTeacher) {
                $currentCount = Subject::where('teacher_id', $newTeacherId)->count();
                if ($currentCount >= self::MAX_SUBJECTS_PER_TEACHER) {
                    return redirect()->route('admin.subjects')
                        ->withInput()
                        ->with('error', 'The selected teacher already has ' . $currentCount . ' subject(s) assigned. Maximum allowed is ' . self::MAX_SUBJECTS_PER_TEACHER . '. Please choose a different teacher.');
                }
            }
        }

        $subject->update($validated);

        return redirect()->route('admin.subjects')->with('success', 'Subject updated successfully!');
    }

    public function deleteSubject(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('admin.subjects')->with('success', 'Subject deleted successfully!');
    }

    // Results Management
    public function results()
    {
        $results = Result::with(['student.user', 'subject.class'])->latest()->paginate(20);
        return view('admin.results.index', compact('results'));
    }
}