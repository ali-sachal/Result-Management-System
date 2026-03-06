@extends('layouts.app')

@section('title', 'My Results')
@section('page-title', 'My Results')

@section('content')
<div class="container-fluid">

    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">My Results</h4>
                <p class="text-muted mb-0">Complete academic performance record</p>
            </div>
            <a href="{{ route('student.download.pdf') }}" class="btn btn-success">
                <i class="fas fa-file-pdf me-2"></i>Download PDF
            </a>
        </div>
    </div>

    <div class="card-custom" data-aos="fade-up">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Subject</th>
                            <th>Marks</th>
                            <th>Performance</th>
                            <th>Grade</th>
                            <th>Status</th>
                            <th>Semester</th>
                            <th>Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($results as $result)
                        <tr data-aos="fade-up" data-aos-delay="{{ ($loop->index % 5) * 50 }}">
                            <td class="ps-4 text-muted">{{ $results->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="fw-semibold">{{ $result->subject->name }}</div>
                                <small class="text-muted">{{ $result->subject->code }}</small>
                            </td>
                            <td>
                                <span class="fw-bold fs-6">{{ $result->marks }}</span>
                                <span class="text-muted">/{{ $result->total_marks }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-fill" style="height:8px;border-radius:6px;max-width:100px">
                                        <div class="progress-bar {{ $result->percentage >= 60 ? ($result->percentage >= 80 ? 'bg-success' : 'bg-info') : 'bg-danger' }}"
                                             style="width:{{ $result->percentage }}%"></div>
                                    </div>
                                    <span style="font-size:13px;min-width:42px">{{ number_format($result->percentage,1) }}%</span>
                                </div>
                            </td>
                            <td>
                                @php
                                    $gc = match($result->grade) {
                                        'A+' => 'success', 'A' => 'primary',
                                        'B'  => 'info',    'C' => 'warning',
                                        default => 'danger'
                                    };
                                @endphp
                                <span class="badge bg-{{ $gc }} fs-6 px-3">{{ $result->grade }}</span>
                            </td>
                            <td>
                                @if($result->percentage >= 60)
                                    <span class="badge bg-success bg-opacity-10 text-success fw-semibold">
                                        <i class="fas fa-check me-1"></i>Pass
                                    </span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger fw-semibold">
                                        <i class="fas fa-times me-1"></i>Fail
                                    </span>
                                @endif
                            </td>
                            <td style="font-size:13px">{{ $result->semester ?? '-' }}</td>
                            <td style="font-size:13px">{{ $result->academic_year }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-chart-line fa-3x text-muted mb-3 d-block"></i>
                                <h6 class="text-muted">No results available</h6>
                                <p class="text-muted small">Results will appear here once your teacher enters them.</p>
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
