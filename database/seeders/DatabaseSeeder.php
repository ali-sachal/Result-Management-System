<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\User;
use App\Models\Classes;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Result;
use App\Services\GradeCalculator;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Roles
        $adminRole = Role::create(['name' => 'admin']);
        $teacherRole = Role::create(['name' => 'teacher']);
        $studentRole = Role::create(['name' => 'student']);

        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin@gmail.com'),
            'role_id' => $adminRole->id,
            'phone' => '1234567890',
        ]);

        // Create Classes
        $classes = [
            // ['name' => 'Computer Science - Year 1', 'description' => 'First year computer science'],
            // ['name' => 'Computer Science - Year 2', 'description' => 'Second year computer science'],
            // ['name' => 'Engineering - Year 1', 'description' => 'First year engineering'],
            // ['name' => 'Business Administration', 'description' => 'Business administration program'],
        ];

        foreach ($classes as $class) {
            Classes::create($class);
        }

        // Create Teachers
        $teacherData = [
            // ['name' => 'Dr. John Smith', 'email' => 'john@example.com', 'employee_id' => 'T001', 'specialization' => 'Computer Science'],
            // ['name' => 'Prof. Sarah Johnson', 'email' => 'sarah@example.com', 'employee_id' => 'T002', 'specialization' => 'Mathematics'],
            // ['name' => 'Dr. Michael Brown', 'email' => 'michael@example.com', 'employee_id' => 'T003', 'specialization' => 'Physics'],
            // ['name' => 'Prof. Emily Davis', 'email' => 'emily@example.com', 'employee_id' => 'T004', 'specialization' => 'English'],
        ];

        foreach ($teacherData as $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role_id' => $teacherRole->id,
            ]);

            Teacher::create([
                'user_id' => $user->id,
                'employee_id' => $data['employee_id'],
                'specialization' => $data['specialization'],
                'joining_date' => now()->subYears(rand(1, 5)),
            ]);
        }

        // Create Students
        $studentData = [
            // ['name' => 'Alice Williams', 'email' => 'alice@example.com', 'roll' => 'CS2024001', 'class_id' => 1],
            // ['name' => 'Bob Martinez', 'email' => 'bob@example.com', 'roll' => 'CS2024002', 'class_id' => 1],
            // ['name' => 'Charlie Anderson', 'email' => 'charlie@example.com', 'roll' => 'CS2024003', 'class_id' => 1],
            // ['name' => 'Diana Taylor', 'email' => 'diana@example.com', 'roll' => 'CS2024004', 'class_id' => 2],
            // ['name' => 'Ethan Wilson', 'email' => 'ethan@example.com', 'roll' => 'CS2024005', 'class_id' => 2],
            // ['name' => 'Fiona Moore', 'email' => 'fiona@example.com', 'roll' => 'EN2024001', 'class_id' => 3],
            // ['name' => 'George Lee', 'email' => 'george@example.com', 'roll' => 'BA2024001', 'class_id' => 4],
            // ['name' => 'Hannah White', 'email' => 'hannah@example.com', 'roll' => 'BA2024002', 'class_id' => 4],
        ];

        foreach ($studentData as $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role_id' => $studentRole->id,
            ]);

            Student::create([
                'user_id' => $user->id,
                'class_id' => $data['class_id'],
                'roll_number' => $data['roll'],
                'date_of_birth' => now()->subYears(rand(18, 22)),
                'guardian_name' => 'Guardian of ' . $data['name'],
                'guardian_phone' => '1234567890',
                'address' => '123 Main St, City',
            ]);
        }

        // Create Subjects
        $subjectData = [
            // ['name' => 'Programming Fundamentals', 'code' => 'CS101', 'class_id' => 1, 'teacher_id' => 1],
            // ['name' => 'Database Systems', 'code' => 'CS102', 'class_id' => 1, 'teacher_id' => 1],
            // ['name' => 'Calculus I', 'code' => 'MATH101', 'class_id' => 1, 'teacher_id' => 2],
            // ['name' => 'Data Structures', 'code' => 'CS201', 'class_id' => 2, 'teacher_id' => 1],
            // ['name' => 'Algorithms', 'code' => 'CS202', 'class_id' => 2, 'teacher_id' => 1],
            // ['name' => 'Physics I', 'code' => 'PHY101', 'class_id' => 3, 'teacher_id' => 3],
            // ['name' => 'Business Management', 'code' => 'BUS101', 'class_id' => 4, 'teacher_id' => 4],
        ];

        foreach ($subjectData as $data) {
            Subject::create($data + ['total_marks' => 100]);
        }

        // Create Sample Results
        $students = Student::all();
        $subjects = Subject::all();

        foreach ($students as $student) {
            // Get subjects for student's class
            $classSubjects = Subject::where('class_id', $student->class_id)->get();

            foreach ($classSubjects as $subject) {
                $marks = rand(45, 98);
                $calculation = GradeCalculator::calculate($marks, 100);

                Result::create([
                    'student_id' => $student->id,
                    'subject_id' => $subject->id,
                    'marks' => $marks,
                    'total_marks' => 100,
                    'percentage' => $calculation['percentage'],
                    'grade' => $calculation['grade'],
                    'semester' => 'Fall',
                    'academic_year' => date('Y'),
                ]);
            }
        }
    }
}