<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;

// Home Route
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isTeacher()) {
            return redirect()->route('teacher.dashboard');
        } elseif ($user->isStudent()) {
            return redirect()->route('student.dashboard');
        }
    }

    return redirect()->route('login');
});

// Authentication Routes
Auth::routes();

// Role-Based Redirects After Login
Route::middleware('auth')->group(function () {
    Route::get(
        '/home',
        function () {
            $user = auth()->user();

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->isTeacher()) {
                return redirect()->route('teacher.dashboard');
            } elseif ($user->isStudent()) {
                return redirect()->route('student.dashboard');
            }

            return redirect('/');
        }
    )->name('home');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Students
    Route::get('/students', [AdminController::class, 'students'])->name('students');
    Route::post('/students', [AdminController::class, 'storeStudent'])->name('students.store');
    Route::get('/students/{student}', function () {
        return redirect()->route('admin.students'); });
    Route::put('/students/{student}', [AdminController::class, 'updateStudent'])->name('students.update');
    Route::delete('/students/{student}', [AdminController::class, 'deleteStudent'])->name('students.delete');
    Route::patch('/students/{student}/status', [AdminController::class, 'updateStudentStatus'])->name('students.status');

    // Teachers
    Route::get('/teachers', [AdminController::class, 'teachers'])->name('teachers');
    Route::post('/teachers', [AdminController::class, 'storeTeacher'])->name('teachers.store');
    Route::get('/teachers/{teacher}', function () {
        return redirect()->route('admin.teachers'); });
    Route::put('/teachers/{teacher}', [AdminController::class, 'updateTeacher'])->name('teachers.update');
    Route::delete('/teachers/{teacher}', [AdminController::class, 'deleteTeacher'])->name('teachers.delete');

    // Classes
    Route::get('/classes', [AdminController::class, 'classes'])->name('classes');
    Route::post('/classes', [AdminController::class, 'storeClass'])->name('classes.store');
    Route::get('/classes/{class}', function () {
        return redirect()->route('admin.classes'); });
    Route::put('/classes/{class}', [AdminController::class, 'updateClass'])->name('classes.update');
    Route::delete('/classes/{class}', [AdminController::class, 'deleteClass'])->name('classes.delete');
    Route::post('/classes/{class}/students/{student}', [AdminController::class, 'addStudentToClass'])->name('classes.students.add');
    Route::delete('/classes/{class}/students/{student}', [AdminController::class, 'removeStudentFromClass'])->name('classes.students.remove');

    // Subjects
    Route::get('/subjects', [AdminController::class, 'subjects'])->name('subjects');
    Route::post('/subjects', [AdminController::class, 'storeSubject'])->name('subjects.store');
    Route::get('/subjects/{subject}', function () {
        return redirect()->route('admin.subjects'); });
    Route::put('/subjects/{subject}', [AdminController::class, 'updateSubject'])->name('subjects.update');
    Route::delete('/subjects/{subject}', [AdminController::class, 'deleteSubject'])->name('subjects.delete');

    // Results
    Route::get('/results', [AdminController::class, 'results'])->name('results');
});

// Teacher Routes
Route::middleware(['auth', 'teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');
    Route::get('/subjects', [TeacherController::class, 'subjects'])->name('subjects');
    Route::get('/subjects/{subject}/manage-results', [TeacherController::class, 'manageResults'])->name('manage.results');
    Route::post('/results', [TeacherController::class, 'storeResult'])->name('results.store');
    Route::put('/results/{result}', [TeacherController::class, 'updateResult'])->name('results.update');
    Route::delete('/results/{result}', [TeacherController::class, 'deleteResult'])->name('results.delete');
});

// Student Routes
Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/results', [StudentController::class, 'results'])->name('results');
    Route::get('/download-pdf', [StudentController::class, 'downloadPDF'])->name('download.pdf');
});
