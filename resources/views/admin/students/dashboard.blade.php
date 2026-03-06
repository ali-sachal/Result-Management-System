@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('page-title', 'My Dashboard')

@section('content')
    <div class="container-fluid">
        <!-- Student Info Card -->
        <div class="row mb-4" data-aos="fade-down">
            <div class="col-12">
                <div class="student-profile-card">
                    <div class="profile-header">
                        <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                            alt="Avatar" class="profile-avatar">
                        <div class="profile-info">
                            <h3 class="profile-name">{{ auth()->user()->name }}</h3>
                            <p class="profile-details">
                                <span class="badge bg-primary me-2">{{ $student->roll_number }}</span>
                                <span class="badge bg-info">{{ $student->class?->name ?? 'No Class' }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="profile-stats">
                        <div class="stat-item">
                            <div class="stat-value">{{ $totalSubjects }}</div>
                            <div class="stat-label">Subjects</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ number_format($averagePercentage, 1) }}%</div>
                            <div class="stat-label">Average</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $passedSubjects }}</div>
                            <div class="stat-label">Passed</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $failedSubjects }}</div>
                            <div class="stat-label">Failed</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overall Performance -->
        <div class="row mb-4">
            <div class="col-lg-4" data-aos="fade-right">
                <div class="card card-custom">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Overall Performance</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="circular-progress" data-percentage="{{ $averagePercentage }}">
                            <svg width="200" height="200">
                                <circle cx="100" cy="100" r="80" fill="none" stroke="#e0e0e0" stroke-width="15" />
                                <circle cx="100" cy="100" r="80" fill="none" stroke="url(#gradient)" stroke-width="15"
                                    stroke-dasharray="502.4"
                                    stroke-dashoffset="{{ 502.4 - (502.4 * $averagePercentage / 100) }}"
                                    stroke-linecap="round" transform="rotate(-90 100 100)" />
                                <defs>
                                    <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" style="stop-color:#667eea;stop-opacity:1" />
                                        <stop offset="100%" style="stop-color:#764ba2;stop-opacity:1" />
                                    </linearGradient>
                                </defs>
                            </svg>
                            <div class="percentage-text">
                                <h2>{{ number_format($averagePercentage, 1) }}%</h2>
                                <p>Average Score</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8" data-aos="fade-left">
                <div class="card card-custom">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">My Results</h5>
                        <a href="{{ route('student.download.pdf') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-download me-2"></i>Download PDF
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Subject</th>
                                        <th>Marks</th>
                                        <th>Percentage</th>
                                        <th>Grade</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($results as $result)
                                        <tr>
                                            <td><strong>{{ $result->subject->name }}</strong></td>
                                            <td>{{ $result->marks }}/{{ $result->total_marks }}</td>
                                            <td>
                                                <div class="progress" style="height: 25px;">
                                                    <div class="progress-bar bg-{{ $result->grade_color }}" role="progressbar"
                                                        style="width: {{ $result->percentage }}%">
                                                        {{ $result->percentage }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $result->grade_color }} fs-6">{{ $result->grade }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $result->percentage >= 60 ? 'success' : 'danger' }}">
                                                    {{ $result->percentage >= 60 ? 'Pass' : 'Fail' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">No results available yet</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .student-profile-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 30px;
            color: white;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
        }

        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 5px solid rgba(255, 255, 255, 0.3);
            margin-right: 20px;
        }

        .profile-name {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .profile-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .stat-item {
            text-align: center;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            backdrop-filter: blur(10px);
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 14px;
            opacity: 0.9;
        }

        .circular-progress {
            position: relative;
            display: inline-block;
        }

        .percentage-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        .percentage-text h2 {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 5px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .percentage-text p {
            font-size: 14px;
            color: #666;
            margin: 0;
        }
    </style>
@endpush