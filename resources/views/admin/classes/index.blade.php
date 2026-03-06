@extends('layouts.app')

@section('title', 'Manage Classes')
@section('page-title', 'Classes Management')

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

        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="fw-bold mb-1">All Classes</h4>
                    <p class="text-muted mb-0">Manage university classes and programs</p>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClassModal">
                    <i class="fas fa-plus me-2"></i>Add New Class
                </button>
            </div>
        </div>

        {{-- Class Cards --}}
        <div class="row g-4" data-aos="fade-up">
            @forelse($classes as $class)
                <div class="col-xl-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 50 }}">
                    <div class="card-custom h-100 hover-lift">
                        <div class="card-body p-0">
                            {{-- Card Header --}}
                            <div
                                style="background: linear-gradient(135deg, #667eea, #764ba2); padding: 20px; border-radius: 14px 14px 0 0;">
                                <div class="d-flex align-items-center gap-3">
                                    <div
                                        style="width:48px;height:48px;background:rgba(255,255,255,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-graduation-cap text-white fa-lg"></i>
                                    </div>
                                    <div>
                                        <div class="text-white fw-bold" style="font-size:15px">{{ $class->name }}</div>
                                        <div class="text-white-50" style="font-size:12px">Class ID: #{{ $class->id }}</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Card Body --}}
                            <div class="p-4">
                                @if($class->description)
                                    <p class="text-muted mb-3" style="font-size:13px">{{ $class->description }}</p>
                                @endif
                                {{-- clickable student count --}}
                                <div class="d-flex justify-content-between mb-2" style="cursor:pointer" onclick="openManageStudents(
                                         {{ $class->id }},
                                         '{{ addslashes($class->name) }}',
                                         {{ $class->students->pluck('id')->toJson() }}
                                     )" title="Click to view / manage students">
                                    <span class="text-muted" style="font-size:13px">
                                        <i class="fas fa-users me-1 text-primary"></i>Students
                                    </span>
                                    <span class="fw-bold text-primary text-decoration-underline">
                                        {{ $class->students_count }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between mb-4">
                                    <span class="text-muted" style="font-size:13px"><i
                                            class="fas fa-book me-1 text-success"></i>Subjects</span>
                                    <span class="fw-bold text-success">{{ $class->subjects_count }}</span>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="d-flex gap-2 flex-wrap">
                                    {{-- Manage Students Button --}}
                                    <button class="btn btn-sm btn-success flex-fill" onclick="openManageStudents(
                                            {{ $class->id }},
                                            '{{ addslashes($class->name) }}',
                                            {{ $class->students->pluck('id')->toJson() }}
                                        )">
                                        <i class="fas fa-user-cog me-1"></i>Students
                                    </button>
                                    {{-- Edit Class --}}
                                    <button class="btn btn-sm btn-outline-primary flex-fill"
                                        onclick="editClass({{ $class->id }}, '{{ addslashes($class->name) }}', '{{ addslashes($class->description ?? '') }}')">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </button>
                                    {{-- Delete Class --}}
                                    <button class="btn btn-sm btn-outline-danger flex-fill"
                                        onclick="deleteClass({{ $class->id }})">
                                        <i class="fas fa-trash me-1"></i>Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card-custom text-center py-5">
                        <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No classes found</h5>
                        <p class="text-muted">Click "Add New Class" to get started.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-4">{{ $classes->links() }}</div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
    Add Class Modal
    ════════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="addClassModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus-circle me-2 text-primary"></i>Add New Class</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.classes.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Class Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Computer Science - Year 1"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control" rows="3"
                                placeholder="Optional description..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Class</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
    Edit Class Modal
    ════════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="editClassModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit me-2 text-warning"></i>Edit Class</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editClassForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Class Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_class_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" id="edit_class_description" class="form-control"
                                rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning text-white"><i class="fas fa-save me-1"></i>Update
                            Class</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
    Manage / View Students Modal (Admin Only)
    ════════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="manageStudentsModal" tabindex="-1" aria-labelledby="manageStudentsLabel">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">

                {{-- Header --}}
                <div class="modal-header" style="background:linear-gradient(135deg,#667eea,#764ba2);">
                    <div>
                        <h5 class="modal-title text-white fw-bold mb-0" id="manageStudentsLabel">
                            <i class="fas fa-users me-2"></i>Students in <span id="ms_className"></span>
                        </h5>
                        <small class="text-white-50">Click a student's Remove button to move them to another class</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-0">

                    {{-- ── STUDENTS LIST (shown first) ─────────────────────── --}}
                    <div class="p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="fw-bold mb-0 text-primary">
                                <i class="fas fa-list me-1"></i>Enrolled Students
                            </h6>
                            <span id="ms_count" class="badge bg-primary rounded-pill px-3" style="font-size:13px">0</span>
                        </div>
                        <div id="ms_studentList">
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-spinner fa-spin fa-2x mb-2 d-block"></i>Loading...
                            </div>
                        </div>
                    </div>

                    {{-- ── ADD STUDENT (accordion at bottom) ───────────────── --}}
                    <div class="accordion border-top" id="addStudentAccordion">
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed bg-light fw-semibold text-success" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#addStudentPane">
                                    <i class="fas fa-user-plus me-2"></i>Add a Student to this Class
                                </button>
                            </h2>
                            <div id="addStudentPane" class="accordion-collapse collapse">
                                <div class="accordion-body bg-light pt-2">
                                    <div class="input-group">
                                        <select id="ms_addSelect" class="form-select">
                                            <option value="">— Select a student —</option>
                                        </select>
                                        <button class="btn btn-success" type="button" onclick="submitAddStudent()">
                                            <i class="fas fa-plus me-1"></i>Add
                                        </button>
                                    </div>
                                    <small class="text-muted mt-2 d-block">
                                        Students already in this class are hidden. Selecting a student from another class
                                        will <strong>move</strong> them here.
                                    </small>
                                    <form id="addStudentForm" method="POST" style="display:none;">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>{{-- /modal-body --}}

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Pre-render all students data as JSON for JS use --}}
    <script id="allStudentsData" type="application/json">
    {!! json_encode($studentsForJs) !!}
    </script>

    {{-- Pre-render classes map --}}
    <script id="classesData" type="application/json">
    {!! json_encode($classesForJs) !!}
    </script>

@endsection

@push('scripts')
    <script>
        // ── Global data ───────────────────────────────────────────────────────────
        const ALL_STUDENTS = JSON.parse(document.getElementById('allStudentsData').textContent);
        const CLASSES_MAP = JSON.parse(document.getElementById('classesData').textContent);

        let _currentClassId = null;
        let _currentClassStudentIds = [];

        // ── Open Manage Students Modal ────────────────────────────────────────────
        function openManageStudents(classId, className, studentIds) {
            _currentClassId = classId;
            _currentClassStudentIds = studentIds;

            document.getElementById('ms_className').textContent = className;

            // Populate "Add" dropdown — students NOT in this class
            const addSelect = document.getElementById('ms_addSelect');
            addSelect.innerHTML = '<option value="">— Select a student to add —</option>';
            ALL_STUDENTS
                .filter(s => !studentIds.includes(s.id))
                .forEach(s => {
                    const currentClass = CLASSES_MAP[s.class_id] ? ` (currently in ${CLASSES_MAP[s.class_id]})` : '';
                    const opt = document.createElement('option');
                    opt.value = s.id;
                    opt.textContent = `${s.name} [${s.roll}]${currentClass}`;
                    addSelect.appendChild(opt);
                });

            // Populate current students list
            renderCurrentStudents(classId, studentIds);

            new bootstrap.Modal(document.getElementById('manageStudentsModal')).show();
        }

        function renderCurrentStudents(classId, studentIds) {
            const list = document.getElementById('ms_studentList');
            const counter = document.getElementById('ms_count');
            const inClass = ALL_STUDENTS.filter(s => studentIds.includes(s.id));

            counter.textContent = inClass.length;

            if (inClass.length === 0) {
                list.innerHTML = `
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-user-slash fa-3x mb-3 d-block opacity-50"></i>
                        <p class="mb-0">No students enrolled in this class yet.</p>
                        <small>Use the <strong>Add a Student</strong> panel below to enrol someone.</small>
                    </div>`;
                return;
            }

            list.innerHTML = `
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle" style="font-size:14px">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width:40px">#</th>
                            <th>Student</th>
                            <th>Roll No</th>
                            <th class="text-end pe-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${inClass.map((s, i) => `
                        <tr>
                            <td class="ps-3 text-muted">${i + 1}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(s.name)}&background=6366f1&color=fff&size=32"
                                         width="32" height="32" class="rounded-circle">
                                    <span class="fw-semibold">${escHtml(s.name)}</span>
                                </div>
                            </td>
                            <td><span class="badge bg-secondary bg-opacity-75">${escHtml(s.roll)}</span></td>
                            <td class="text-end pe-3">
                                <button class="btn btn-sm btn-outline-danger"
                                        onclick="submitRemoveStudent(${classId}, ${s.id}, '${escHtml(s.name)}')"
                                        title="Remove from this class">
                                    <i class="fas fa-user-minus me-1"></i>Remove
                                </button>
                            </td>
                        </tr>`).join('')}
                    </tbody>
                </table>
            </div>`;
        }

        // ── Add Student ───────────────────────────────────────────────────────────
        function submitAddStudent() {
            const select = document.getElementById('ms_addSelect');
            const studentId = select.value;
            if (!studentId) {
                select.classList.add('is-invalid');
                setTimeout(() => select.classList.remove('is-invalid'), 2000);
                return;
            }

            const form = document.getElementById('addStudentForm');
            form.action = `/admin/classes/${_currentClassId}/students/${studentId}`;
            form.submit();
        }

        // ── Remove Student ────────────────────────────────────────────────────────
        function submitRemoveStudent(classId, studentId, studentName) {
            Swal.fire({
                title: 'Remove Student?',
                html: `Remove <strong>${studentName}</strong> from this class?<br>
                       <small class="text-muted">They will be moved to the first available other class.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6366f1',
                confirmButtonText: 'Yes, remove',
            }).then(result => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/classes/${classId}/students/${studentId}`;
                    form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">
                                      <input type="hidden" name="_method" value="DELETE">`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // ── Helpers ───────────────────────────────────────────────────────────────
        function escHtml(str) {
            return String(str).replace(/[&<>"']/g, m =>
                ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[m]));
        }

        // ── Edit Class ────────────────────────────────────────────────────────────
        function editClass(id, name, description) {
            document.getElementById('edit_class_name').value = name;
            document.getElementById('edit_class_description').value = description;
            document.getElementById('editClassForm').action = `/admin/classes/${id}`;
            new bootstrap.Modal(document.getElementById('editClassModal')).show();
        }

        // ── Delete Class ──────────────────────────────────────────────────────────
        function deleteClass(id) {
            Swal.fire({
                title: 'Delete Class?',
                text: 'This will remove the class and all related subjects/results!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6366f1',
                confirmButtonText: 'Yes, delete!',
            }).then(result => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/classes/${id}`;
                    form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">
                                      <input type="hidden" name="_method" value="DELETE">`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endpush