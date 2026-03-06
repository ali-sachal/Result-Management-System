@extends('layouts.app')

@section('title', 'Student Dashboard')
@section('page-title', 'My Dashboard')

@section('content')
    <div class="container-fluid">

        {{-- Welcome Banner --}}
        <div class="row mb-4">
            <div class="col-12" data-aos="fade-down">
                <div
                    style="background:linear-gradient(135deg,#6366f1,#8b5cf6,#06b6d4);border-radius:16px;padding:28px 32px;color:#fff;position:relative;overflow:hidden;">
                    <div
                        style="position:absolute;right:-20px;top:-20px;width:160px;height:160px;background:rgba(255,255,255,0.07);border-radius:50%">
                    </div>
                    <div
                        style="position:absolute;right:80px;bottom:-30px;width:100px;height:100px;background:rgba(255,255,255,0.05);border-radius:50%">
                    </div>
                    <div class="d-flex align-items-center gap-4">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&size=70&background=ffffff&color=6366f1&bold=true"
                            class="rounded-circle border border-3 border-white shadow" width="70" height="70">
                        <div>
                            <h3 class="fw-bold mb-1">Welcome back, {{ auth()->user()->name }}! 👋</h3>
                            <p class="mb-1" style="opacity:.85;font-size:14px">
                                <i class="fas fa-id-badge me-1"></i> Roll No: <strong>{{ $student->roll_number }}</strong>
                                &nbsp;&bull;&nbsp;
                                <i class="fas fa-building me-1"></i> {{ $student->class?->name ?? 'No Class Assigned' }}
                            </p>
                            <p class="mb-0" style="opacity:.75;font-size:13px">Academic Year {{ date('Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-sm-6" data-aos="fade-up" data-aos-delay="50">
                <div class="stats-card gradient-purple">
                    <div class="stats-icon"><i class="fas fa-book-open"></i></div>
                    <div class="stats-content">
                        <h3 class="stats-number" data-counter="{{ $totalSubjects }}">0</h3>
                        <p class="stats-label">Total Subjects</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6" data-aos="fade-up" data-aos-delay="100">
                <div class="stats-card gradient-blue">
                    <div class="stats-icon"><i class="fas fa-percentage"></i></div>
                    <div class="stats-content">
                        <h3 class="stats-number">{{ number_format($averagePercentage ?? 0, 1) }}%</h3>
                        <p class="stats-label">Average Score</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6" data-aos="fade-up" data-aos-delay="150">
                <div class="stats-card gradient-green">
                    <div class="stats-icon"><i class="fas fa-check-circle"></i></div>
                    <div class="stats-content">
                        <h3 class="stats-number" data-counter="{{ $passedSubjects }}">0</h3>
                        <p class="stats-label">Subjects Passed</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6" data-aos="fade-up" data-aos-delay="200">
                <div class="stats-card gradient-red">
                    <div class="stats-icon"><i class="fas fa-times-circle"></i></div>
                    <div class="stats-content">
                        <h3 class="stats-number" data-counter="{{ $failedSubjects }}">0</h3>
                        <p class="stats-label">Subjects Failed</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- Circular Progress --}}
            <div class="col-lg-4">
                <div class="card-custom h-100" data-aos="fade-right">
                    <div class="card-header">
                        <h6 class="card-title">Overall Performance</h6>
                    </div>
                    <div class="card-body text-center">
                        @php
                            $avg = round($averagePercentage ?? 0);
                            $circumference = 2 * pi() * 70;
                            $offset = $circumference - ($avg / 100) * $circumference;
                            $grade = match (true) {
                                $avg >= 90 => 'A+',
                                $avg >= 80 => 'A',
                                $avg >= 70 => 'B',
                                $avg >= 60 => 'C',
                                default => 'F'
                            };
                            $color = match ($grade) {
                                'A+', 'A' => '#22c55e',
                                'B' => '#0ea5e9',
                                'C' => '#f59e0b',
                                default => '#ef4444'
                            };
                        @endphp
                        <div class="progress-circle-wrap">
                            <svg width="180" height="180" viewBox="0 0 180 180">
                                <circle cx="90" cy="90" r="70" fill="none" stroke="#e2e8f0" stroke-width="12" />
                                <circle class="progress-ring" cx="90" cy="90" r="70" fill="none" stroke="{{ $color }}"
                                    stroke-width="12" stroke-linecap="round" stroke-dasharray="{{ $circumference }}"
                                    stroke-dashoffset="{{ $offset }}" transform="rotate(-90 90 90)" />
                                <text x="90" y="85" text-anchor="middle" font-size="26" font-weight="800"
                                    fill="#1e293b">{{ $avg }}%</text>
                                <text x="90" y="110" text-anchor="middle" font-size="18" font-weight="700"
                                    fill="{{ $color }}">{{ $grade }}</text>
                            </svg>
                        </div>
                        <p class="text-muted mb-0" style="font-size:14px">Based on {{ $totalSubjects }} subject(s)</p>
                        @php
                            $gpa = match ($grade) {
                                'A+' => 4.0, 'A' => 3.7, 'B' => 3.0, 'C' => 2.0, default => 0.0
                            };
                        @endphp
                        <div class="mt-3 p-3 rounded-3" style="background:#f8fafc">
                            <div class="fw-bold" style="font-size:22px;color:{{ $color }}">{{ $gpa }}</div>
                            <div class="text-muted" style="font-size:12px">Cumulative GPA</div>
                        </div>
                        <a href="{{ route('student.download.pdf') }}" class="btn btn-primary w-100 mt-3">
                            <i class="fas fa-download me-2"></i>Download Result Card
                        </a>
                    </div>
                </div>
            </div>

            {{-- Subject-wise Results --}}
            <div class="col-lg-8" data-aos="fade-left">
                <div class="card-custom h-100">
                    <div class="card-header">
                        <h6 class="card-title">Subject-wise Results</h6>
                        <a href="{{ route('student.results') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Subject</th>
                                        <th>Marks</th>
                                        <th>Score</th>
                                        <th>Grade</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($results->take(8) as $result)
                                        <tr style="animation:fadeInUp 0.5s {{ $loop->index * 0.08 }}s both">
                                            <td class="ps-4">
                                                <div class="fw-semibold" style="font-size:14px">
                                                    {{ $result->subject?->name ?? 'Deleted Subject' }}
                                                </div>
                                                <div style="font-size:11px;color:#94a3b8">{{ $result->subject?->code ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td class="fw-semibold">{{ $result->marks }}<span
                                                    class="text-muted fw-normal">/{{ $result->total_marks }}</span></td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="progress"
                                                        style="width:70px;height:6px;background:#e2e8f0;border-radius:4px">
                                                        <div class="progress-bar bg-{{ $result->percentage >= 60 ? ($result->percentage >= 80 ? 'success' : 'info') : 'danger' }}"
                                                            style="width:{{ $result->percentage }}%"></div>
                                                    </div>
                                                    <span
                                                        style="font-size:12px">{{ number_format($result->percentage, 1) }}%</span>
                                                </div>
                                            </td>
                                            <td>
                                                @php $gc = ['A+' => 'success', 'A' => 'primary', 'B' => 'info', 'C' => 'warning'][$result->grade] ?? 'danger'; @endphp
                                                <span class="badge bg-{{ $gc }}">{{ $result->grade }}</span>
                                            </td>
                                            <td>
                                                @if($result->percentage >= 60)
                                                    <span class="badge bg-success bg-opacity-10 text-success">Pass</span>
                                                @else
                                                    <span class="badge bg-danger bg-opacity-10 text-danger">Fail</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <i class="fas fa-chart-line fa-2x text-muted mb-2 d-block"></i>
                                                No results available yet.
                                            </td>
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