@extends('layouts.app')

@section('title', 'Manage Students')

@section('page-title', 'Students Management')

@section('content')
    <div class="container-fluid">

        {{-- Alert messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row mb-4">
            <div class="col-12">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                    <i class="fas fa-plus me-2"></i>Add New Student
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0 pt-3">
                        <h5 class="mb-0">All Students</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Roll No</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Class</th>
                                        <th>Phone</th>
                                        <th>Guardian</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($students as $student)
                                        <tr>
                                            <td class="ps-3"><strong>{{ $student->roll_number }}</strong></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @php
                                                        $avatarName = $student->user?->name ?? 'Student';
                                                        $avatarUrl = $student->user?->avatar
                                                            ?? 'https://ui-avatars.com/api/?name=' . urlencode($avatarName) . '&background=6366f1&color=fff';
                                                    @endphp
                                                    <img src="{{ $avatarUrl }}" alt="Avatar" class="rounded-circle me-2"
                                                        width="32" height="32">
                                                    <span>{{ $student->user?->name ?? 'Deleted User' }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $student->user?->email ?? 'N/A' }}</td>
                                            <td>
                                                @if($student->class)
                                                    <span class="badge bg-info">{{ $student->class->name }}</span>
                                                @else
                                                    <span class="badge bg-secondary">No Class</span>
                                                @endif
                                            </td>
                                            <td>{{ $student->user?->phone ?? 'N/A' }}</td>
                                            <td>{{ $student->guardian_name ?? 'N/A' }}</td>
                                            <td>
                                                @php
                                                    $status = $student->status ?? 'active';
                                                    $badgeClass = match ($status) {
                                                        'active' => 'bg-success',
                                                        'inactive' => 'bg-secondary',
                                                        'suspended' => 'bg-danger',
                                                        default => 'bg-secondary',
                                                    };
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" onclick="editStudent(
                                                                                {{ $student->id }},
                                                                                '{{ addslashes($student->user?->name ?? 'Deleted User') }}',
                                                                                '{{ addslashes($student->user?->email ?? '') }}',
                                                                                '{{ addslashes($student->roll_number) }}',
                                                                                '{{ $student->class_id }}',
                                                                                '{{ addslashes($student->user?->phone ?? '') }}',
                                                                                '{{ $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '' }}',
                                                                                '{{ addslashes($student->guardian_name ?? '') }}',
                                                                                '{{ addslashes($student->guardian_phone ?? '') }}',
                                                                                '{{ str_replace(["\r", "\n"], ['\r', '\n'], addslashes($student->address ?? '')) }}'
                                                                            )">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                {{-- Edit Status button (admin only) --}}
                                                <button class="btn btn-sm btn-warning text-white" title="Edit Status"
                                                    onclick="openStatusModal({{ $student->id }}, '{{ addslashes($student->user?->name ?? 'Student') }}', '{{ $student->status ?? 'active' }}')">
                                                    <i class="fas fa-toggle-on"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="deleteStudent({{ $student->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-muted">No students found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="p-3">
                            {{ $students->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Student Modal --}}
    <div class="modal fade" id="addStudentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.students.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name *</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password *</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Roll Number *</label>
                                <input type="text" name="roll_number" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Class *</label>
                                <select name="class_id" class="form-select" required>
                                    <option value="">Select Class</option>
                                    @foreach($classes as $cls)
                                        <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="date_of_birth" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Guardian Name</label>
                                <input type="text" name="guardian_name" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Guardian Phone</label>
                                <input type="text" name="guardian_phone" class="form-control">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Student Modal --}}
    <div class="modal fade" id="editStudentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editStudentForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name *</label>
                                <input type="text" name="name" id="edit_student_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" id="edit_student_email" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Roll Number *</label>
                                <input type="text" name="roll_number" id="edit_student_roll" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Class *</label>
                                <select name="class_id" id="edit_student_class" class="form-select" required>
                                    <option value="">Select Class</option>
                                    @foreach($classes as $cls)
                                        <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" id="edit_student_phone" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="date_of_birth" id="edit_student_dob" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Guardian Name</label>
                                <input type="text" name="guardian_name" id="edit_student_guardian" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Guardian Phone</label>
                                <input type="text" name="guardian_phone" id="edit_student_guardian_phone"
                                    class="form-control">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" id="edit_student_address" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Status Modal (Admin Only) --}}
    <div class="modal fade" id="editStatusModal" tabindex="-1" aria-labelledby="editStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-warning bg-opacity-10 border-warning">
                    <h5 class="modal-title fw-bold" id="editStatusModalLabel">
                        <i class="fas fa-toggle-on me-2 text-warning"></i>Edit Student Status
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="statusForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <p class="mb-3 text-muted small">
                            Update status for: <strong id="statusStudentName"></strong>
                        </p>
                        <label class="form-label fw-semibold">Select New Status</label>
                        <select name="status" id="statusSelect" class="form-select">
                            <option value="active">✅ Active</option>
                            <option value="inactive">⬜ Inactive</option>
                            <option value="suspended">🚫 Suspended</option>
                        </select>
                        <div class="mt-3">
                            <div id="statusPreview" class="badge fs-6 w-100 py-2"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning btn-sm text-white fw-bold">
                            <i class="fas fa-save me-1"></i>Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // ── Edit Student ────────────────────────────────────────────────────────────
        function editStudent(id, name, email, roll, classId, phone, dob, gName, gPhone, address) {
            document.getElementById('edit_student_name').value = name;
            document.getElementById('edit_student_email').value = email;
            document.getElementById('edit_student_roll').value = roll;
            document.getElementById('edit_student_class').value = classId;
            document.getElementById('edit_student_phone').value = phone || '';
            document.getElementById('edit_student_dob').value = dob || '';
            document.getElementById('edit_student_guardian').value = gName || '';
            document.getElementById('edit_student_guardian_phone').value = gPhone || '';
            document.getElementById('edit_student_address').value = address || '';

            document.getElementById('editStudentForm').action = `/admin/students/${id}`;
            new bootstrap.Modal(document.getElementById('editStudentModal')).show();
        }

        // ── Status Modal ────────────────────────────────────────────────────────────
        function openStatusModal(studentId, studentName, currentStatus) {
            const modal = document.getElementById('editStatusModal');
            const form = document.getElementById('statusForm');
            const nameEl = document.getElementById('statusStudentName');
            const select = document.getElementById('statusSelect');

            // Set form action to the PATCH route
            form.action = `/admin/students/${studentId}/status`;
            nameEl.textContent = studentName;
            select.value = currentStatus;
            updateStatusPreview(currentStatus);

            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        }

        function updateStatusPreview(status) {
            const preview = document.getElementById('statusPreview');
            const classes = {
                active: 'bg-success',
                inactive: 'bg-secondary',
                suspended: 'bg-danger',
            };
            preview.className = 'badge fs-6 w-100 py-2 ' + (classes[status] || 'bg-secondary');
            preview.textContent = status.charAt(0).toUpperCase() + status.slice(1);
        }

        document.getElementById('statusSelect').addEventListener('change', function () {
            updateStatusPreview(this.value);
        });

        // ── Delete Student ──────────────────────────────────────────────────────────
        function deleteStudent(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will delete the student and all related data!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/students/${id}`;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';

                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';

                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endpush