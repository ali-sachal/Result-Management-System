@extends('layouts.app')

@section('title', 'Manage Results')

@section('page-title', 'Manage Results - ' . $subject->name)

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-info">
                    <strong>Subject:</strong> {{ $subject->name }} ({{ $subject->code }}) |
                    <strong>Class:</strong> {{ $subject->class->name }} |
                    <strong>Total Marks:</strong> {{ $subject->total_marks }}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card card-custom" data-aos="fade-up">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Enter Student Marks</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Roll No</th>
                                        <th>Student Name</th>
                                        <th>Marks</th>
                                        <th>Percentage</th>
                                        <th>Grade</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                        <tr>
                                            <td><strong>{{ $student->roll_number }}</strong></td>
                                            <td>{{ $student->user?->name ?? 'Deleted User' }}</td>
                                            <td>
                                                @if($student->results->first())
                                                    {{ $student->results->first()->marks }}/{{ $subject->total_marks }}
                                                @else
                                                    <span class="text-muted">Not entered</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($student->results->first())
                                                    {{ $student->results->first()->percentage }}%
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($student->results->first())
                                                    <span class="badge bg-{{ $student->results->first()->grade_color }}">
                                                        {{ $student->results->first()->grade }}
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($student->results->first())
                                                    <button class="btn btn-sm btn-primary"
                                                        onclick="editResult({{ $student->results->first()->id }}, {{ $student->results->first()->marks }})">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-success" onclick="addResult({{ $student->id }})">
                                                        <i class="fas fa-plus"></i> Add
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Result Modal -->
    <div class="modal fade" id="resultModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Result</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="resultForm">
                        <input type="hidden" id="resultId">
                        <input type="hidden" id="studentId">
                        <input type="hidden" id="subjectId" value="{{ $subject->id }}">

                        <div class="mb-3">
                            <label class="form-label">Marks (out of {{ $subject->total_marks }}) *</label>
                            <input type="number" id="marks" class="form-control" min="0" max="{{ $subject->total_marks }}"
                                step="0.01" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Semester</label>
                            <select id="semester" class="form-select">
                                <option value="Fall">Fall</option>
                                <option value="Spring">Spring</option>
                                <option value="Summer">Summer</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Academic Year *</label>
                            <input type="number" id="academicYear" class="form-control" value="{{ date('Y') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea id="remarks" class="form-control" rows="2"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveResult()">Save Result</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let modal;

        document.addEventListener('DOMContentLoaded', function () {
            modal = new bootstrap.Modal(document.getElementById('resultModal'));
        });

        function addResult(studentId) {
            document.getElementById('modalTitle').textContent = 'Add Result';
            document.getElementById('resultId').value = '';
            document.getElementById('studentId').value = studentId;
            document.getElementById('marks').value = '';
            document.getElementById('remarks').value = '';
            modal.show();
        }

        function editResult(resultId, currentMarks) {
            document.getElementById('modalTitle').textContent = 'Edit Result';
            document.getElementById('resultId').value = resultId;
            document.getElementById('marks').value = currentMarks;
            modal.show();
        }

        function saveResult() {
            const resultId = document.getElementById('resultId').value;
            const marks = document.getElementById('marks').value;
            const semester = document.getElementById('semester').value;
            const academicYear = document.getElementById('academicYear').value;
            const remarks = document.getElementById('remarks').value;

            if (!marks || !academicYear) {
                showError('Please fill all required fields');
                return;
            }

            const data = {
                marks: marks,
                total_marks: {{ $subject->total_marks }},
                semester: semester,
                academic_year: academicYear,
                remarks: remarks
            };

            if (resultId) {
                // Update existing result
                data._method = 'PUT';

                $.ajax({
                    url: `/teacher/results/${resultId}`,
                    method: 'POST',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        modal.hide();
                        showSuccess(response.message);
                        setTimeout(() => location.reload(), 1500);
                    },
                    error: function (xhr) {
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = Object.values(xhr.responseJSON.errors).flat().join('\n');
                            showError(errors);
                        } else {
                            showError('Failed to update result. Please try again.');
                        }
                    }
                });
            } else {
                // Create new result
                data.student_id = document.getElementById('studentId').value;
                data.subject_id = document.getElementById('subjectId').value;

                $.ajax({
                    url: '/teacher/results',
                    method: 'POST',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        modal.hide();
                        showSuccess(response.message);
                        setTimeout(() => location.reload(), 1500);
                    },
                    error: function (xhr) {
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = Object.values(xhr.responseJSON.errors).flat().join('\n');
                            showError(errors);
                        } else {
                            showError('Failed to save result. Please try again.');
                        }
                    }
                });
            }
        }
    </script>
@endpush