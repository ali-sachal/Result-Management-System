@extends('layouts.app')

@section('title', 'Manage Teachers')

@section('page-title', 'Teachers Management')

@section('content')
    <div class="container-fluid">

        {{-- Header with Add Button --}}
        <div class="row mb-4">
            <div class="col-12">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTeacherModal">
                    <i class="fas fa-plus me-2"></i>Add New Teacher
                </button>
            </div>
        </div>

        {{-- Teachers Table --}}
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0 pt-3">
                        <h5 class="mb-0">All Teachers</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Employee ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Specialization</th>
                                        <th>Qualification</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($teachers as $teacher)
                                        <tr>
                                            <td class="ps-3"><strong>{{ $teacher->employee_id }}</strong></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $teacher->user?->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($teacher->user?->name ?? 'Teacher') }}"
                                                        alt="Avatar" class="rounded-circle me-2" width="32" height="32">
                                                    <span>{{ $teacher->user?->name ?? 'Deleted User' }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $teacher->user?->email ?? 'N/A' }}</td>
                                            <td>{{ $teacher->user?->phone ?? 'N/A' }}</td>
                                            <td>{{ $teacher->specialization ?? 'N/A' }}</td>
                                            <td>{{ $teacher->qualification ?? 'N/A' }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-primary"
                                                    onclick="editTeacher({{ $teacher->id }}, '{{ addslashes($teacher->user?->name ?? 'Deleted User') }}', '{{ $teacher->user?->email ?? '' }}', '{{ $teacher->employee_id }}', '{{ $teacher->user?->phone ?? '' }}', '{{ addslashes($teacher->qualification ?? '') }}', '{{ addslashes($teacher->specialization ?? '') }}', '{{ $teacher->joining_date }}')">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="deleteTeacher({{ $teacher->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">No teachers found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="p-3">
                            {{ $teachers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Teacher Modal --}}
    <div class="modal fade" id="addTeacherModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Teacher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.teachers.store') }}" method="POST">
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
                                <label class="form-label">Employee ID *</label>
                                <input type="text" name="employee_id" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Qualification</label>
                                <input type="text" name="qualification" class="form-control"
                                    placeholder="e.g., PhD, Masters">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Specialization</label>
                                <input type="text" name="specialization" class="form-control"
                                    placeholder="e.g., Computer Science">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Joining Date</label>
                                <input type="date" name="joining_date" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Teacher</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Teacher Modal --}}
    <div class="modal fade" id="editTeacherModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Teacher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editTeacherForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name *</label>
                                <input type="text" name="name" id="edit_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" id="edit_email" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Employee ID *</label>
                                <input type="text" name="employee_id" id="edit_employee_id" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" id="edit_phone" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Qualification</label>
                                <input type="text" name="qualification" id="edit_qualification" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Specialization</label>
                                <input type="text" name="specialization" id="edit_specialization" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Joining Date</label>
                                <input type="date" name="joining_date" id="edit_joining_date" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Teacher</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let editModal;

        document.addEventListener('DOMContentLoaded', function () {
            editModal = new bootstrap.Modal(document.getElementById('editTeacherModal'));
        });

        function editTeacher(id, name, email, employeeId, phone, qualification, specialization, joiningDate) {
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_employee_id').value = employeeId;
            document.getElementById('edit_phone').value = phone || '';
            document.getElementById('edit_qualification').value = qualification || '';
            document.getElementById('edit_specialization').value = specialization || '';
            document.getElementById('edit_joining_date').value = joiningDate || '';

            document.getElementById('editTeacherForm').action = `/admin/teachers/${id}`;
            editModal.show();
        }

        function deleteTeacher(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will delete the teacher and all related data!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/teachers/${id}`;

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