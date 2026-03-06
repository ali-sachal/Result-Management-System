@extends('layouts.app')

@section('title', 'All Results')
@section('page-title', 'Results Management')

@section('content')
<div class="container-fluid">

    <div class="row mb-4">
        <div class="col-12">
            <h4 class="fw-bold mb-1">All Results</h4>
            <p class="text-muted">Complete results database across all subjects and students</p>
        </div>
    </div>

    <div class="card-custom" data-aos="fade-up">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Student</th>
                            <th>Class</th>
                            <th>Subject</th>
                            <th>Marks</th>
                            <th>Percentage</th>
                            <th>Grade</th>
                            <th>Status</th>
                            <th>Semester</th>
                            <th>Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($results as $result)
                        <tr>
                            <td class="ps-4 text-muted">{{ $results->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($result->student?->user?->name ?? 'N A') }}&size=30&background=6366f1&color=fff"
                                         class="rounded-circle" width="30" height="30">
                                    <div>
                                        <div style="font-size:13px;font-weight:600">{{ $result->student?->user?->name ?? 'N/A' }}</div>
                                        <div style="font-size:11px;color:#94a3b8">{{ $result->student?->roll_number }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-size:13px">{{ $result->subject?->class?->name ?? 'N/A' }}</td>
                            <td>
                                <div style="font-size:13px;font-weight:600">{{ $result->subject?->name ?? 'N/A' }}</div>
                                <div style="font-size:11px;color:#94a3b8">{{ $result->subject?->code }}</div>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $result->marks }}</span>
                                <span class="text-muted">/{{ $result->total_marks }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress" style="width:60px;height:6px;background:#e2e8f0;border-radius:4px">
                                        <div class="progress-bar bg-{{ $result->percentage >= 60 ? 'success' : 'danger' }}"
                                             style="width:{{ $result->percentage }}%"></div>
                                    </div>
                                    <span style="font-size:13px">{{ number_format($result->percentage, 1) }}%</span>
                                </div>
                            </td>
                            <td>
                                @php
                                    $gc = match($result->grade) {
                                        'A+' => 'success',
                                        'A'  => 'primary',
                                        'B'  => 'info',
                                        'C'  => 'warning',
                                        default => 'danger'
                                    };
                                @endphp
                                <span class="badge bg-{{ $gc }} bg-opacity-15 text-{{ $gc }} fw-bold px-2 py-1">
                                    {{ $result->grade }}
                                </span>
                            </td>
                            <td>
                                @if($result->percentage >= 60)
                                    <span class="badge bg-success bg-opacity-10 text-success">Pass</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger">Fail</span>
                                @endif
                            </td>
                            <td style="font-size:13px">{{ $result->semester ?? '-' }}</td>
                            <td style="font-size:13px">{{ $result->academic_year }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <i class="fas fa-chart-bar fa-2x text-muted mb-2 d-block"></i>
                                No results found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $results->links() }}</div>
        </div>
    </div>
</div>
@endsection
