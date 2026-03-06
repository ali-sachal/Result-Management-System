@extends('layouts.app')

@section('title', 'My Subjects')
@section('page-title', 'My Subjects')

@section('content')
    <div class="container-fluid">

        <div class="row mb-4">
            <div class="col-12" data-aos="fade-down">
                <h4 class="fw-bold mb-1">My Assigned Subjects</h4>
                <p class="text-muted">Subjects you are responsible for teaching and grading</p>
            </div>
        </div>

        @if($subjects->isEmpty())
            <div class="card-custom text-center py-5" data-aos="fade-up">
                <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No subjects assigned yet</h5>
                <p class="text-muted small">Please contact the admin to get subjects assigned to you.</p>
            </div>
        @else
            <div class="row g-4">
                @foreach($subjects as $subject)
                    <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                        <div class="card-custom h-100">
                            <div class="card-body">
                                {{-- Subject Header --}}
                                <div class="d-flex align-items-start justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div
                                            style="width:48px;height:48px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                                            <i class="fas fa-book text-white"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0" style="font-size:15px">{{ $subject->name }}</h6>
                                            <small class="text-muted">{{ $subject->code }}</small>
                                        </div>
                                    </div>
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1" style="font-size:11px">
                                        {{ $subject->total_marks }} Marks
                                    </span>
                                </div>

                                {{-- Class Info --}}
                                <div class="mb-3 p-3 rounded-3" style="background:#f8fafc">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-chalkboard-teacher text-indigo-500"
                                            style="color:#6366f1;font-size:13px"></i>
                                        <span style="font-size:13px;color:#64748b">
                                            <strong>Class:</strong> {{ $subject->class?->name ?? 'N/A' }}
                                        </span>
                                    </div>
                                    @if($subject->description)
                                        <div class="mt-2 d-flex align-items-start gap-2">
                                            <i class="fas fa-info-circle" style="color:#94a3b8;font-size:12px;margin-top:2px"></i>
                                            <span style="font-size:12px;color:#94a3b8">{{ $subject->description }}</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Action Button --}}
                                <a href="{{ route('teacher.manage.results', $subject->id) }}" class="btn btn-primary w-100">
                                    <i class="fas fa-edit me-2"></i>Manage Results
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
@endsection