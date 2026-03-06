@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('page-title', 'Teacher Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="stats-card gradient-purple">
                <div class="stats-icon">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">{{ $assignedSubjects->count() }}</h3>
                    <p class="stats-label">Assigned Subjects</p>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="stats-card gradient-blue">
                <div class="stats-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">{{ $totalStudents }}</h3>
                    <p class="stats-label">Total Students</p>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="stats-card gradient-green">
                <div class="stats-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">{{ $totalResults }}</h3>
                    <p class="stats-label">Results Entered</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Assigned Subjects -->
    <div class="row">
        <div class="col-12">
            <div class="card card-custom" data-aos="fade-up">
                <div class="card-header">
                    <h5 class="card-title mb-0">My Assigned Subjects</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        @forelse($assignedSubjects as $subject)
                        <div class="col-lg-4 col-md-6">
                            <div class="subject-card">
                                <div class="subject-header">
                                    <div class="subject-icon">
                                        <i class="fas fa-book-open"></i>
                                    </div>
                                    <h5 class="subject-name">{{ $subject->name }}</h5>
                                </div>
                                <div class="subject-body">
                                    <p class="subject-info">
                                        <i class="fas fa-code me-2"></i>
                                        <strong>Code:</strong> {{ $subject->code }}
                                    </p>
                                    <p class="subject-info">
                                        <i class="fas fa-building me-2"></i>
                                        <strong>Class:</strong> {{ $subject->class->name }}
                                    </p>
                                    <p class="subject-info">
                                        <i class="fas fa-star me-2"></i>
                                        <strong>Total Marks:</strong> {{ $subject->total_marks }}
                                    </p>
                                </div>
                                <div class="subject-footer">
                                    <a href="{{ route('teacher.manage.results', $subject->id) }}" class="btn btn-primary btn-sm w-100">
                                        <i class="fas fa-edit me-2"></i>Manage Results
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                No subjects assigned yet.
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection