<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result Card - {{ $student->user?->name ?? 'Student' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 13px;
            color: #1e293b;
            background: #fff;
        }

        .header {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: #fff;
            padding: 30px 40px;
            text-align: center;
        }

        .header h1 {
            font-size: 26px;
            font-weight: 800;
            margin-bottom: 4px;
            letter-spacing: -0.5px;
        }

        .header p {
            font-size: 14px;
            opacity: 0.85;
        }

        .badge-strip {
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            padding: 16px 40px;
            display: flex;
            justify-content: space-between;
        }

        .badge-item {
            text-align: center;
        }

        .badge-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
        }

        .badge-value {
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
        }

        .section {
            padding: 24px 40px;
        }

        .section-title {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #6366f1;
            margin-bottom: 14px;
            padding-bottom: 6px;
            border-bottom: 2px solid #e0e7ff;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .info-row {
            display: flex;
            gap: 6px;
        }

        .info-label {
            color: #64748b;
            min-width: 110px;
            font-size: 12px;
        }

        .info-value {
            font-weight: 600;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        thead th {
            background: #6366f1;
            color: #fff;
            padding: 10px 12px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tbody td {
            padding: 10px 12px;
            border-bottom: 1px solid #f1f5f9;
        }

        tbody tr:nth-child(even) {
            background: #f8faff;
        }

        .grade-badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 12px;
            font-weight: 800;
            font-size: 11px;
        }

        .grade-aplus {
            background: #dcfce7;
            color: #166534;
        }

        .grade-a {
            background: #e0e7ff;
            color: #3730a3;
        }

        .grade-b {
            background: #cffafe;
            color: #164e63;
        }

        .grade-c {
            background: #fef3c7;
            color: #92400e;
        }

        .grade-fail {
            background: #fee2e2;
            color: #991b1b;
        }

        .pass {
            color: #16a34a;
            font-weight: 700;
        }

        .fail {
            color: #dc2626;
            font-weight: 700;
        }

        .summary-box {
            background: linear-gradient(135deg, #f0f4ff, #e0e7ff);
            border: 2px solid #c7d2fe;
            border-radius: 10px;
            padding: 20px 24px;
            margin: 0 40px 24px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        .summary-item {
            text-align: center;
        }

        .summary-number {
            font-size: 22px;
            font-weight: 800;
            color: #6366f1;
        }

        .summary-label {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .footer {
            background: #f8fafc;
            border-top: 2px solid #e2e8f0;
            padding: 18px 40px;
            text-align: center;
            color: #94a3b8;
            font-size: 11px;
        }

        .seal {
            margin: 20px 40px;
            text-align: right;
        }

        .seal-line {
            border-top: 1px solid #475569;
            width: 160px;
            margin-left: auto;
            padding-top: 6px;
            color: #64748b;
            font-size: 11px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>🎓 Result Management System</h1>
        <p>Official Academic Result Card &bull; Generated: {{ date('d M Y') }}</p>
    </div>

    <div class="badge-strip">
        <div class="badge-item">
            <div class="badge-label">Student Name</div>
            <div class="badge-value">{{ $student->user?->name ?? 'Deleted User' }}</div>
        </div>
        <div class="badge-item">
            <div class="badge-label">Roll Number</div>
            <div class="badge-value">{{ $student->roll_number }}</div>
        </div>
        <div class="badge-item">
            <div class="badge-label">Class / Program</div>
            <div class="badge-value">{{ $student->class?->name ?? 'No Class' }}</div>
        </div>
        <div class="badge-item">
            <div class="badge-label">Academic Year</div>
            <div class="badge-value">{{ date('Y') }}</div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Student Information</div>
        <div class="info-grid">
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value">{{ $student->user?->email ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Date of Birth:</span>
                <span
                    class="info-value">{{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('d M Y') : 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Guardian:</span>
                <span class="info-value">{{ $student->guardian_name ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Address:</span>
                <span class="info-value">{{ $student->address ?? 'N/A' }}</span>
            </div>
        </div>
    </div>

    <div class="summary-box">
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-number">{{ $results->count() }}</div>
                <div class="summary-label">Total Subjects</div>
            </div>
            <div class="summary-item">
                <div class="summary-number">{{ number_format($averagePercentage ?? 0, 1) }}%</div>
                <div class="summary-label">Average Score</div>
            </div>
            <div class="summary-item">
                <div class="summary-number">{{ $results->where('percentage', '>=', 60)->count() }}</div>
                <div class="summary-label">Subjects Passed</div>
            </div>
            <div class="summary-item">
                @php
                    $avg = round($averagePercentage ?? 0);
                    $overallGrade = match (true) {
                        $avg >= 90 => 'A+', $avg >= 80 => 'A',
                        $avg >= 70 => 'B', $avg >= 60 => 'C', default => 'F'
                    };
                @endphp
                <div class="summary-number"
                    style="color:{{ in_array($overallGrade, ['A+', 'A']) ? '#16a34a' : ($overallGrade === 'F' ? '#dc2626' : '#d97706') }}">
                    {{ $overallGrade }}</div>
                <div class="summary-label">Overall Grade</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Subject-wise Results</div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Subject Name</th>
                    <th>Code</th>
                    <th>Marks</th>
                    <th>Total</th>
                    <th>Percentage</th>
                    <th>Grade</th>
                    <th>Status</th>
                    <th>Semester</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results as $i => $result)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td><strong>{{ $result->subject->name }}</strong></td>
                        <td>{{ $result->subject->code }}</td>
                        <td>{{ $result->marks }}</td>
                        <td>{{ $result->total_marks }}</td>
                        <td>{{ number_format($result->percentage, 1) }}%</td>
                        <td>
                            @php
                                $cls = match ($result->grade) {
                                    'A+' => 'grade-aplus', 'A' => 'grade-a',
                                    'B' => 'grade-b', 'C' => 'grade-c',
                                    default => 'grade-fail'
                                };
                            @endphp
                            <span class="grade-badge {{ $cls }}">{{ $result->grade }}</span>
                        </td>
                        <td class="{{ $result->percentage >= 60 ? 'pass' : 'fail' }}">
                            {{ $result->percentage >= 60 ? 'Pass' : 'Fail' }}
                        </td>
                        <td>{{ $result->semester ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="seal">
        <div class="seal-line">Authorized Signature</div>
    </div>

    <div class="footer">
        <p>This is an official document generated by the Result Management System &bull; {{ config('app.name') }}</p>
        <p style="margin-top:4px">Printed on {{ now()->format('d M Y, h:i A') }}</p>
    </div>

</body>

</html>