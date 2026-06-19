<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subjects Report — CodeXpress</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #1a1a1a; line-height: 1.5; }

        .header {
            background: linear-gradient(135deg, #0a0a0a 0%, #262626 100%);
            color: #fff;
            padding: 24px 32px;
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
        .badge-code {
            display: inline-block;
            padding: 2px 8px;
            font-size: 9px;
            font-weight: 700;
            background: rgba(245,158,11,.1);
            border: 1px solid rgba(245,158,11,.25);
            border-radius: 12px;
            color: #d97706;
            font-family: monospace;
        }
        .desc-cell { max-width: 180px; overflow: hidden; text-overflow: ellipsis; }

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
            background: #f59e0b;
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
        <h1>📚 Subject Report</h1>
        <p>CodeXpress — Student Management System</p>
    </div>

    <div class="meta-bar">
        <div class="meta-item"><span class="meta-label">Generated:</span> {{ now()->format('d M Y, h:i A') }}</div>
        <div class="meta-item"><span class="meta-label">Total Records:</span> {{ $subjects->count() }}</div>
        @if($search)
        <div class="meta-item"><span class="meta-label">Filter:</span> <span class="filter-tag">{{ $search }}</span></div>
        @endif
    </div>

    <div class="content">
        <table>
            <thead>
                <tr>
                    <th style="width:5%">#</th>
                    <th style="width:22%">Name</th>
                    <th style="width:10%">Code</th>
                    <th style="width:33%">Description</th>
                    <th style="width:10%">Credits</th>
                    <th style="width:20%">Added</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subjects as $index => $subject)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="name-cell">{{ $subject->name }}</td>
                    <td><span class="badge-code">{{ $subject->code }}</span></td>
                    <td class="desc-cell">{{ $subject->description ?: '—' }}</td>
                    <td>{{ $subject->credits }}</td>
                    <td>{{ $subject->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary-row">
            <strong>Summary:</strong> {{ $subjects->count() }} subject(s) exported
            @if($search) — filtered by "<strong>{{ $search }}</strong>" @endif
        </div>
    </div>

    <div class="footer">
        CodeXpress © {{ date('Y') }} — This report was generated automatically. | Page 1
    </div>
</body>
</html>
