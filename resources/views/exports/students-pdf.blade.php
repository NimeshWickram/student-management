<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Students Report — EduManager</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #1a1a1a; line-height: 1.5; }

        .header {
            background: linear-gradient(135deg, #0a0a0a 0%, #262626 100%);
            color: #fff;
            padding: 24px 32px;
            margin-bottom: 0;
        }
        .header h1 { font-size: 22px; font-weight: 800; letter-spacing: -0.5px; margin-bottom: 4px; }
        .header p { font-size: 11px; color: #a3a3a3; }

        .meta-bar {
            display: flex;
            background: #f5f5f5;
            padding: 10px 32px;
            border-bottom: 2px solid #e5e5e5;
            font-size: 10px;
            color: #525252;
        }
        .meta-bar .meta-item { margin-right: 28px; }
        .meta-bar .meta-label { font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #0a0a0a; }

        .content { padding: 20px 32px; }

        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        thead th {
            background: #0a0a0a;
            color: #fff;
            padding: 10px 12px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: left;
        }
        tbody td {
            padding: 9px 12px;
            font-size: 11px;
            border-bottom: 1px solid #e5e5e5;
            color: #404040;
        }
        tbody tr:nth-child(even) { background: #fafafa; }
        .name-cell { font-weight: 600; color: #0a0a0a; }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            font-size: 9px;
            font-weight: 700;
            background: #f5f5f5;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            color: #404040;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            padding: 12px 32px;
            font-size: 9px;
            color: #a3a3a3;
            border-top: 1px solid #e5e5e5;
        }

        .filter-tag {
            display: inline-block;
            padding: 3px 10px;
            font-size: 10px;
            font-weight: 600;
            background: #6366f1;
            color: #fff;
            border-radius: 12px;
            margin-left: 8px;
        }

        .summary-row {
            margin-top: 16px;
            padding: 12px 16px;
            background: #f5f5f5;
            border-radius: 6px;
            font-size: 11px;
            color: #525252;
        }
        .summary-row strong { color: #0a0a0a; }
    </style>
</head>
<body>
    <div class="header">
        <h1>📋 Student Report</h1>
        <p>EduManager — Student Management System</p>
    </div>

    <div class="meta-bar">
        <div class="meta-item"><span class="meta-label">Generated:</span> {{ now()->format('d M Y, h:i A') }}</div>
        <div class="meta-item"><span class="meta-label">Total Records:</span> {{ $students->count() }}</div>
        @if($search)
        <div class="meta-item"><span class="meta-label">Filter:</span> <span class="filter-tag">{{ $search }}</span></div>
        @endif
    </div>

    <div class="content">
        <table>
            <thead>
                <tr>
                    <th style="width:5%">#</th>
                    <th style="width:20%">Name</th>
                    <th style="width:22%">Email</th>
                    <th style="width:15%">Phone</th>
                    <th style="width:20%">Course</th>
                    <th style="width:18%">Registered</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $index => $student)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="name-cell">{{ $student->first_name }} {{ $student->last_name }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->phone_number }}</td>
                    <td><span class="badge">{{ $student->course }}</span></td>
                    <td>{{ $student->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary-row">
            <strong>Summary:</strong> {{ $students->count() }} student(s) exported
            @if($search) — filtered by "<strong>{{ $search }}</strong>" @endif
        </div>
    </div>

    <div class="footer">
        EduManager © {{ date('Y') }} — This report was generated automatically. | Page 1
    </div>
</body>
</html>
