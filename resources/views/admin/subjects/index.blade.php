@extends('layouts.app')

@section('title', 'Manage Subjects')
@section('page-title', 'Subjects Management')

@section('content')
<div class="container-fluid">

    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">All Subjects</h4>
                <p class="text-muted mb-0">Manage subjects and teacher assignments</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                <i class="fas fa-plus me-2"></i>Add Subject
            </button>
        </div>
    </div>

    <div class="card-custom" data-aos="fade-up">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Code</th>
                            <th>Subject Name</th>
                            <th>Class</th>
                            <th>Teacher</th>
                            <th>Total Marks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subjects as $subject)
                        <tr>
                            <td class="ps-4">
                                <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold">{{ $subject->code }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $subject->name }}</div>
                                @if($subject->description)
                                    <small class="text-muted">{{ Str::limit($subject->description, 50) }}</small>
                                @endif
                            </td>
                            <td><span class="badge bg-info bg-opacity-10 text-info">{{ $subject->class?->name ?? 'No Class' }}</span></td>
                            <td>
                                @if($subject->teacher && $subject->teacher->user)
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($subject->teacher->user->name) }}&size=28&background=6366f1&color=fff"
                                             class="rounded-circle" width="28" height="28">
                                        <span style="font-size:13px">{{ $subject->teacher->user->name }}</span>
                                    </div>
                                @else
                                    <span class="text-muted small">Unassigned</span>
                                @endif
                            </td>
                            <td><span class="fw-semibold">{{ $subject->total_marks }}</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-1"
                                    onclick="editSubject(
                                        {{ $subject->id }},
                                        '{{ addslashes($subject->name) }}',
                                        '{{ addslashes($subject->code) }}',
                                        {{ $subject->class_id }},
                                        {{ $subject->teacher_id ?? 'null' }},
                                        {{ $subject->total_marks }},
                                        '{{ addslashes($subject->description ?? '') }}'
                                    )">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteSubject({{ $subject->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-book fa-2x text-muted mb-2 d-block"></i>
                                No subjects found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $subjects->links() }}</div>
        </div>
    </div>
</div>

{{-- Add Subject Modal --}}
<div class="modal fade" id="addSubjectModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-book me-2 text-primary"></i>Add New Subject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.subjects.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Subject Name *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Subject Code *</label>
                            <input type="text" name="code" class="form-control" placeholder="e.g. CS101" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Class *</label>
                            <select name="class_id" class="form-select" required>
                                <option value="">Select Class</option>
                                @foreach($classes as $cls)
                                    <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Assign Teacher
                                <small class="text-muted fw-normal">(max 2 subjects per teacher)</small>
                            </label>
                            <select name="teacher_id" class="form-select">
                                <option value="">No Teacher</option>
                                @foreach($teachers as $teacher)
                                    @php $isFull = $teacher->subjects_count >= 2; @endphp
                                    <option value="{{ $teacher->id }}"
                                        {{ $isFull ? 'disabled' : '' }}
                                        style="{{ $isFull ? 'color:#94a3b8;background:#f8fafc;' : '' }}">
                                        {{ $teacher->user?->name ?? 'Deleted User' }}
                                        ({{ $teacher->subjects_count }}/2 subjects)
                                        {{ $isFull ? '— Full' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1 text-info"></i>
                                Greyed-out teachers have reached the 2-subject limit.
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Total Marks *</label>
                            <input type="number" name="total_marks" class="form-control" value="100" min="1" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Subject</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Subject Modal --}}
<div class="modal fade" id="editSubjectModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit me-2 text-warning"></i>Edit Subject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editSubjectForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Subject Name *</label>
                            <input type="text" name="name" id="e_sub_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Subject Code *</label>
                            <input type="text" name="code" id="e_sub_code" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Class *</label>
                            <select name="class_id" id="e_sub_class" class="form-select" required>
                                @foreach($classes as $cls)
                                    <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Assign Teacher
                                <small class="text-muted fw-normal">(max 2 subjects per teacher)</small>
                            </label>
                            <select name="teacher_id" id="e_sub_teacher" class="form-select">
                                <option value="">No Teacher</option>
                                @foreach($teachers as $teacher)
                                    @php $isFull = $teacher->subjects_count >= 2; @endphp
                                    <option value="{{ $teacher->id }}"
                                        data-count="{{ $teacher->subjects_count }}"
                                        {{ $isFull ? 'disabled' : '' }}
                                        style="{{ $isFull ? 'color:#94a3b8;background:#f8fafc;' : '' }}">
                                        {{ $teacher->user?->name ?? 'Deleted User' }}
                                        ({{ $teacher->subjects_count }}/2 subjects)
                                        {{ $isFull ? '— Full' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1 text-info"></i>
                                Greyed-out teachers have reached the 2-subject limit.
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Total Marks *</label>
                            <input type="number" name="total_marks" id="e_sub_marks" class="form-control" min="1" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" id="e_sub_desc" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning text-white"><i class="fas fa-save me-1"></i>Update Subject</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function editSubject(id, name, code, classId, teacherId, totalMarks, desc) {
        document.getElementById('e_sub_name').value = name;
        document.getElementById('e_sub_code').value = code;
        document.getElementById('e_sub_class').value = classId;
        document.getElementById('e_sub_marks').value = totalMarks;
        document.getElementById('e_sub_desc').value = desc;
        document.getElementById('editSubjectForm').action = `/admin/subjects/${id}`;

        // Re-enable the currently assigned teacher's option (their count includes this
        // very subject, so keeping them assigned won't exceed the limit).
        const teacherSelect = document.getElementById('e_sub_teacher');
        teacherSelect.querySelectorAll('option[disabled]').forEach(opt => {
            if (parseInt(opt.value) === parseInt(teacherId)) {
                opt.disabled  = false;
                opt.style.color      = '';
                opt.style.background = '';
            }
        });

        teacherSelect.value = teacherId || '';
        new bootstrap.Modal(document.getElementById('editSubjectModal')).show();
    }

    function deleteSubject(id) {
        Swal.fire({
            title: 'Delete Subject?',
            text: 'All results for this subject will also be deleted!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6366f1',
            confirmButtonText: 'Yes, delete!',
        }).then(result => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/subjects/${id}`;
                form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">
                                  <input type="hidden" name="_method" value="DELETE">`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endpush
