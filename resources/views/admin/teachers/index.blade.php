@extends('layouts.app')

@section('title', 'Manage Teachers')
@section('page-title', 'Teachers Management')

@section('content')
    <div class="container-fluid">

        {{-- Flash Messages --}}
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
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <ul class="mb-0 ps-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="fw-bold mb-1">All Teachers</h4>
                    <p class="text-muted mb-0">Manage faculty members and their assignments</p>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTeacherModal">
                    <i class="fas fa-plus me-2"></i>Add New Teacher
                </button>
            </div>
        </div>

        <div class="card-custom" data-aos="fade-up">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Employee ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Specialization</th>
                                <th>Qualification</th>
                                <th>Joining Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($teachers as $teacher)
                                <tr>
                                    <td class="ps-4">
                                        <span
                                            class="badge bg-primary bg-opacity-10 text-primary fw-bold">{{ $teacher->employee_id }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $teacher->user?->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($teacher->user?->name ?? 'Teacher') . '&background=6366f1&color=fff' }}"
                                                alt="Avatar" class="rounded-circle" width="36" height="36">
                                            <div>
                                                <div class="fw-semibold" style="font-size:14px">
                                                    {{ $teacher->user?->name ?? 'Deleted User' }}
                                                </div>
                                                <div style="font-size:11px;color:#94a3b8">{{ $teacher->subjects_count ?? 0 }}
                                                    subjects</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="font-size:13px">{{ $teacher->user?->email ?? 'N/A' }}</td>
                                    <td style="font-size:13px">{{ $teacher->user?->phone ?? 'N/A' }}</td>
                                    <td>
                                        @if($teacher->specialization)
                                            <span
                                                class="badge bg-info bg-opacity-10 text-info">{{ $teacher->specialization }}</span>
                                        @else
                                            <span class="text-muted small">N/A</span>
                                        @endif
                                    </td>
                                    <td style="font-size:13px">{{ $teacher->qualification ?? 'N/A' }}</td>
                                    <td style="font-size:13px">
                                        {{ $teacher->joining_date ? \Carbon\Carbon::parse($teacher->joining_date)->format('d M Y') : 'N/A' }}
                                    </td>
                                    <td>
                                        {{-- Edit Button --}}
                                        <button class="btn btn-sm btn-outline-primary me-1" title="Edit Teacher" onclick="editTeacher(
                                                        {{ $teacher->id }},
                                                        '{{ addslashes($teacher->user?->name ?? 'Deleted User') }}',
                                                        '{{ $teacher->user?->email ?? '' }}',
                                                        '{{ $teacher->employee_id }}',
                                                        '{{ $teacher->user?->phone ?? '' }}',
                                                        '{{ addslashes($teacher->qualification ?? '') }}',
                                                        '{{ addslashes($teacher->specialization ?? '') }}',
                                                        '{{ $teacher->joining_date }}'
                                                    )">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        {{-- Delete Button --}}
                                        <button class="btn btn-sm btn-outline-danger" title="Delete Teacher"
                                            onclick="deleteTeacher({{ $teacher->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="fas fa-chalkboard-teacher fa-2x text-muted mb-2 d-block"></i>
                                        No teachers found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3">{{ $teachers->links() }}</div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
    Add Teacher Modal
    ═══════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="addTeacherModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary bg-opacity-10 border-primary">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-user-plus me-2 text-primary"></i>Add New Teacher
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.teachers.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required placeholder="Enter full name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" required placeholder="Enter email">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="password" id="add_password" class="form-control" required
                                        placeholder="Min. 8 characters">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="togglePwd('add_password', this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Employee ID <span class="text-danger">*</span></label>
                                <input type="text" name="employee_id" class="form-control" required
                                    placeholder="e.g., EMP-001">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Phone</label>
                                <input type="text" name="phone" class="form-control" placeholder="e.g., 03001234567">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Qualification</label>
                                <input type="text" name="qualification" class="form-control"
                                    placeholder="e.g., PhD, Masters">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Specialization</label>
                                <input type="text" name="specialization" class="form-control"
                                    placeholder="e.g., Computer Science">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Joining Date</label>
                                <input type="date" name="joining_date" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Add Teacher</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
    Edit Teacher Modal (Admin Only)
    ═══════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="editTeacherModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning bg-opacity-10 border-warning">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-user-edit me-2 text-warning"></i>Edit Teacher
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editTeacherForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            {{-- Personal Info --}}
                            <div class="col-12 mb-2">
                                <p class="fw-semibold text-muted mb-0"
                                    style="font-size:13px;text-transform:uppercase;letter-spacing:.05em">
                                    <i class="fas fa-user me-1"></i> Personal Information
                                </p>
                                <hr class="mt-1 mb-3">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="edit_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="edit_email" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Phone</label>
                                <input type="text" name="phone" id="edit_phone" class="form-control" placeholder="Optional">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Employee ID <span class="text-danger">*</span></label>
                                <input type="text" name="employee_id" id="edit_employee_id" class="form-control" required>
                            </div>

                            {{-- Academic Info --}}
                            <div class="col-12 mb-2 mt-1">
                                <p class="fw-semibold text-muted mb-0"
                                    style="font-size:13px;text-transform:uppercase;letter-spacing:.05em">
                                    <i class="fas fa-graduation-cap me-1"></i> Academic Information
                                </p>
                                <hr class="mt-1 mb-3">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Qualification</label>
                                <input type="text" name="qualification" id="edit_qualification" class="form-control"
                                    placeholder="e.g., PhD, Masters">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Specialization</label>
                                <input type="text" name="specialization" id="edit_specialization" class="form-control"
                                    placeholder="e.g., Computer Science">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Joining Date</label>
                                <input type="date" name="joining_date" id="edit_joining_date" class="form-control">
                            </div>

                            {{-- Password Change (Optional) --}}
                            <div class="col-12 mb-2 mt-1">
                                <p class="fw-semibold text-muted mb-0"
                                    style="font-size:13px;text-transform:uppercase;letter-spacing:.05em">
                                    <i class="fas fa-lock me-1"></i> Change Password
                                    <span class="text-muted fw-normal" style="font-size:12px">(leave blank to keep
                                        current)</span>
                                </p>
                                <hr class="mt-1 mb-3">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">New Password</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="edit_password" class="form-control"
                                        placeholder="Min. 8 characters" minlength="8">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="togglePwd('edit_password', this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="edit_password_confirm"
                                        class="form-control" placeholder="Re-enter new password">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="togglePwd('edit_password_confirm', this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-warning text-white fw-bold">
                            <i class="fas fa-save me-1"></i>Update Teacher
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // ── Toggle password visibility ──────────────────────────────────────────
        function togglePwd(fieldId, btn) {
            const input = document.getElementById(fieldId);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        // ── Open Edit Teacher Modal ─────────────────────────────────────────────
        function editTeacher(id, name, email, employeeId, phone, qualification, specialization, joiningDate) {
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_employee_id').value = employeeId;
            document.getElementById('edit_phone').value = phone || '';
            document.getElementById('edit_qualification').value = qualification || '';
            document.getElementById('edit_specialization').value = specialization || '';
            document.getElementById('edit_joining_date').value = joiningDate || '';
            // Clear password fields each time
            document.getElementById('edit_password').value = '';
            document.getElementById('edit_password_confirm').value = '';

            document.getElementById('editTeacherForm').action = `/admin/teachers/${id}`;
            new bootstrap.Modal(document.getElementById('editTeacherModal')).show();
        }

        // ── Delete Teacher ──────────────────────────────────────────────────────
        function deleteTeacher(id) {
            Swal.fire({
                title: 'Delete Teacher?',
                text: 'This will permanently delete the teacher account and all related data!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6366f1',
                confirmButtonText: 'Yes, delete!',
            }).then(result => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/teachers/${id}`;
                    form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">
                                          <input type="hidden" name="_method" value="DELETE">`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endpush