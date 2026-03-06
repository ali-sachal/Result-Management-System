@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('page-title', 'Dashboard')

@section('content')
    <div class="container-fluid">

        {{-- Stats Cards Row --}}
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-sm-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                            <i class="fas fa-users fa-lg text-primary"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Total Students</div>
                            <div class="fs-3 fw-bold">{{ $totalStudents }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="fas fa-chalkboard-teacher fa-lg text-success"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Total Teachers</div>
                            <div class="fs-3 fw-bold">{{ $totalTeachers }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                            <i class="fas fa-book fa-lg text-warning"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Total Subjects</div>
                            <div class="fs-3 fw-bold">{{ $totalSubjects }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                            <i class="fas fa-poll fa-lg text-info"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Total Results</div>
                            <div class="fs-3 fw-bold">{{ $totalResults }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chart + Recent Results Row --}}
        <div class="row g-4">

            {{-- Performance Chart --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent fw-semibold border-0 pt-3">
                        Grade Distribution
                    </div>
                    <div class="card-body">
                        <canvas id="gradeChart" height="280"></canvas>
                    </div>
                </div>
            </div>

            {{-- Recent Results Table --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm h-100">
                    <div
                        class="card-header bg-transparent fw-semibold border-0 pt-3 d-flex justify-content-between align-items-center">
                        Recent Results
                        <a href="{{ route('admin.results') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Student</th>
                                        <th>Subject</th>
                                        <th>Marks</th>
                                        <th>Grade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentResults as $result)
                                        <tr>
                                            <td class="ps-3">
                                                @if($result->student && $result->student->user)
                                                    {{ $result->student->user->name }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if($result->subject)
                                                    {{ $result->subject->name }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                {{ $result->marks }} / {{ $result->total_marks }}
                                            </td>
                                            <td>
                                                @php
                                                    $badgeClass = 'danger';
                                                    if ($result->grade === 'A+' || $result->grade === 'A') {
                                                        $badgeClass = 'success';
                                                    } elseif ($result->grade === 'B') {
                                                        $badgeClass = 'primary';
                                                    } elseif ($result->grade === 'C') {
                                                        $badgeClass = 'warning';
                                                    }
                                                @endphp
                                                <span class="badge bg-{{ $badgeClass }}">
                                                    {{ $result->grade }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">No results found.</td>
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

@push('scripts')
    <script>
        const ctx = document.getElementById('gradeChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($performanceData['labels']) !!},
                    datasets: [{
                        data: {!! json_encode($performanceData['data']) !!},
                        backgroundColor: [
                            '#0d6efd', '#198754', '#ffc107', '#dc3545',
                            '#0dcaf0', '#6f42c1', '#fd7e14', '#20c997'
                        ],
                        borderWidth: 2,
                        borderColor: 'transparent'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        }
    </script>
@endpush