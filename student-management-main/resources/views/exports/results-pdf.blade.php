<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz Results Report — CodeXpress</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #1a1a2e;
            line-height: 1.5;
            background: #ffffff;
        }

        /* ── HEADER ── */
        .header {
            background: #1a1a2e;
            color: #fff;
            padding: 0;
            margin-bottom: 0;
        }
        .header-top {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            padding: 28px 36px 22px;
        }
        .header-logo {
            font-size: 10px;
            font-weight: 700;
            color: rgba(255,255,255,0.7);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 8px;
        }
        .header-title {
            font-size: 24px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.5px;
            margin-bottom: 4px;
        }
        .header-subtitle {
            font-size: 11px;
            color: rgba(255,255,255,0.75);
        }
        .header-bottom {
            background: #16213e;
            padding: 10px 36px;
            display: flex;
        }
        .header-meta {
            display: inline-block;
            margin-right: 28px;
            font-size: 9px;
            color: rgba(255,255,255,0.55);
        }
        .header-meta strong {
            color: rgba(255,255,255,0.9);
            font-weight: 700;
        }

        /* ── FILTER TAGS ── */
        .filter-bar {
            background: #f0f4ff;
            border-left: 4px solid #4f46e5;
            padding: 10px 36px;
            margin-bottom: 0;
            font-size: 9px;
            color: #4338ca;
        }
        .filter-bar strong { color: #1a1a2e; margin-right: 6px; }
        .filter-tag {
            display: inline-block;
            padding: 2px 10px;
            background: #4f46e5;
            color: #fff;
            border-radius: 10px;
            font-size: 8px;
            font-weight: 700;
            margin-right: 6px;
        }

        /* ── SUMMARY CARDS ── */
        .summary-section {
            padding: 18px 36px 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        .summary-title {
            font-size: 8px;
            font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 10px;
        }
        .summary-cards {
            display: table;
            width: 100%;
        }
        .summary-card {
            display: table-cell;
            width: 20%;
            padding: 12px 16px;
            border-radius: 8px;
            text-align: center;
        }
        .card-blue   { background: #eff6ff; border: 1px solid #bfdbfe; }
        .card-green  { background: #f0fdf4; border: 1px solid #bbf7d0; }
        .card-amber  { background: #fffbeb; border: 1px solid #fde68a; }
        .card-red    { background: #fff1f2; border: 1px solid #fecdd3; }
        .card-purple { background: #faf5ff; border: 1px solid #e9d5ff; }
        .card-number {
            font-size: 20px;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 4px;
        }
        .card-blue   .card-number { color: #2563eb; }
        .card-green  .card-number { color: #16a34a; }
        .card-amber  .card-number { color: #d97706; }
        .card-red    .card-number { color: #e11d48; }
        .card-purple .card-number { color: #7c3aed; }
        .card-label {
            font-size: 8px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ── GRADE DISTRIBUTION ── */
        .grade-section {
            padding: 14px 36px;
            border-bottom: 1px solid #e5e7eb;
        }
        .grade-title {
            font-size: 8px;
            font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 10px;
        }
        .grade-row { display: table; width: 100%; }
        .grade-item { display: table-cell; text-align: center; padding: 0 6px; }
        .grade-bar-wrap {
            height: 10px;
            background: #f3f4f6;
            border-radius: 5px;
            margin-bottom: 4px;
            overflow: hidden;
        }
        .grade-bar { height: 100%; border-radius: 5px; }
        .bar-a  { background: #16a34a; }
        .bar-b  { background: #2563eb; }
        .bar-c  { background: #d97706; }
        .bar-d  { background: #ea580c; }
        .bar-f  { background: #e11d48; }
        .grade-label { font-size: 9px; font-weight: 700; color: #374151; }
        .grade-count { font-size: 8px; color: #9ca3af; }

        /* ── TABLE ── */
        .table-section { padding: 16px 36px 0; }
        .table-title {
            font-size: 8px;
            font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 10px;
        }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #1a1a2e; }
        thead th {
            padding: 9px 10px;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: rgba(255,255,255,0.8);
            text-align: left;
        }
        thead th:last-child { text-align: center; }
        tbody tr:nth-child(even) { background: #f9fafb; }
        tbody tr:nth-child(odd)  { background: #ffffff; }
        tbody td {
            padding: 8px 10px;
            font-size: 9px;
            border-bottom: 1px solid #f3f4f6;
            color: #374151;
            vertical-align: middle;
        }
        .td-num {
            font-size: 8px;
            color: #9ca3af;
            font-weight: 600;
            text-align: center;
            width: 30px;
        }
        .td-name { font-weight: 700; color: #111827; }
        .td-sub  { color: #6b7280; font-size: 8.5px; }
        .td-center { text-align: center; }

        /* Grade pill */
        .grade-pill {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }
        .pill-grade { background: #ede9fe; color: #5b21b6; }

        /* Score badge — colour by performance */
        .score-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: 800;
            min-width: 46px;
            text-align: center;
        }
        .score-a  { background: #dcfce7; color: #15803d; }
        .score-b  { background: #dbeafe; color: #1d4ed8; }
        .score-c  { background: #fef9c3; color: #a16207; }
        .score-d  { background: #ffedd5; color: #c2410c; }
        .score-f  { background: #ffe4e6; color: #be123c; }

        /* Rank medal */
        .medal { font-size: 11px; }

        /* ── FOOTER ── */
        .footer {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            padding: 8px 36px;
            border-top: 2px solid #4f46e5;
            background: #fff;
            display: table;
            width: 100%;
        }
        .footer-left {
            display: table-cell;
            font-size: 8px;
            color: #9ca3af;
        }
        .footer-right {
            display: table-cell;
            text-align: right;
            font-size: 8px;
            color: #9ca3af;
        }
        .footer-brand {
            font-weight: 800;
            color: #4f46e5;
        }

        /* page break helper */
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

    @php
        $total      = $submissions->count();
        $avgScore   = $total > 0 ? round($submissions->avg('score'), 1) : 0;
        $highest    = $total > 0 ? $submissions->max('score') : 0;
        $lowest     = $total > 0 ? $submissions->min('score') : 0;
        $passCount  = $submissions->where('score', '>=', 50)->count();
        $failCount  = $total - $passCount;

        // Grade band counts (A ≥80, B 65-79, C 50-64, D 35-49, F <35)
        $gradeA = $submissions->where('score', '>=', 80)->count();
        $gradeB = $submissions->filter(fn($s) => $s->score >= 65 && $s->score < 80)->count();
        $gradeC = $submissions->filter(fn($s) => $s->score >= 50 && $s->score < 65)->count();
        $gradeD = $submissions->filter(fn($s) => $s->score >= 35 && $s->score < 50)->count();
        $gradeF = $submissions->where('score', '<', 35)->count();

        // Helper: score → badge class
        function scoreBadgeClass(int|float $s): string {
            if ($s >= 80) return 'score-a';
            if ($s >= 65) return 'score-b';
            if ($s >= 50) return 'score-c';
            if ($s >= 35) return 'score-d';
            return 'score-f';
        }

        // Helper: score → grade letter
        function gradeLetter(int|float $s): string {
            if ($s >= 80) return 'A';
            if ($s >= 65) return 'B';
            if ($s >= 50) return 'C';
            if ($s >= 35) return 'D';
            return 'F';
        }
    @endphp

    {{-- ── HEADER ── --}}
    <div class="header">
        <div class="header-top">
            <div class="header-logo">CodeXpress · Teacher Workspace</div>
            <div class="header-title">Quiz Results Report</div>
            <div class="header-subtitle">
                @if(!empty($filters['score_range']))
                    Filtered by Score: {{ ucwords(str_replace('_', ' ', $filters['score_range'])) }}
                @elseif(!empty($filters['search']))
                    Filtered by Student: {{ $filters['search'] }}
                @else
                    All Submissions Export
                @endif
            </div>
        </div>
        <div class="header-bottom">
            <div class="header-meta"><strong>Generated:</strong> {{ now()->format('d M Y, h:i A') }}</div>
            <div class="header-meta"><strong>Total Records:</strong> {{ $total }}</div>
            @if(!empty($filters['grade']))
                <div class="header-meta"><strong>Grade:</strong> {{ $filters['grade'] }}</div>
            @endif
            <div class="header-meta"><strong>Pass Rate:</strong> {{ $total > 0 ? round(($passCount/$total)*100) : 0 }}%</div>
        </div>
    </div>

    {{-- ── ACTIVE FILTERS ── --}}
    @if(!empty($filters['search']) || !empty($filters['score_range']) || !empty($filters['grade']) || !empty($filters['quiz_id']))
    <div class="filter-bar">
        <strong>Active Filters:</strong>
        @if(!empty($filters['search']))     <span class="filter-tag">Student: {{ $filters['search'] }}</span> @endif
        @if(!empty($filters['score_range']))<span class="filter-tag">{{ ucwords(str_replace('_', ' ', $filters['score_range'])) }}</span> @endif
        @if(!empty($filters['grade']))      <span class="filter-tag">Grade {{ $filters['grade'] }}</span> @endif
    </div>
    @endif

    {{-- ── SUMMARY CARDS ── --}}
    <div class="summary-section">
        <div class="summary-title">Summary Statistics</div>
        <div class="summary-cards">
            <div class="summary-card card-blue">
                <div class="card-number">{{ $total }}</div>
                <div class="card-label">Submissions</div>
            </div>
            <div class="summary-card card-purple">
                <div class="card-number">{{ $avgScore }}%</div>
                <div class="card-label">Average Score</div>
            </div>
            <div class="summary-card card-green">
                <div class="card-number">{{ $highest }}%</div>
                <div class="card-label">Highest Score</div>
            </div>
            <div class="summary-card card-amber">
                <div class="card-number">{{ $lowest }}%</div>
                <div class="card-label">Lowest Score</div>
            </div>
            <div class="summary-card card-red">
                <div class="card-number">{{ $failCount }}</div>
                <div class="card-label">Below 50%</div>
            </div>
        </div>
    </div>

    {{-- ── GRADE DISTRIBUTION BAR ── --}}
    @if($total > 0)
    <div class="grade-section">
        <div class="grade-title">Grade Distribution</div>
        <div class="grade-row">
            <div class="grade-item">
                <div class="grade-bar-wrap">
                    <div class="grade-bar bar-a" style="width:{{ $total > 0 ? round(($gradeA/$total)*100) : 0 }}%"></div>
                </div>
                <div class="grade-label">A (80–100)</div>
                <div class="grade-count">{{ $gradeA }} student(s)</div>
            </div>
            <div class="grade-item">
                <div class="grade-bar-wrap">
                    <div class="grade-bar bar-b" style="width:{{ $total > 0 ? round(($gradeB/$total)*100) : 0 }}%"></div>
                </div>
                <div class="grade-label">B (65–79)</div>
                <div class="grade-count">{{ $gradeB }} student(s)</div>
            </div>
            <div class="grade-item">
                <div class="grade-bar-wrap">
                    <div class="grade-bar bar-c" style="width:{{ $total > 0 ? round(($gradeC/$total)*100) : 0 }}%"></div>
                </div>
                <div class="grade-label">C (50–64)</div>
                <div class="grade-count">{{ $gradeC }} student(s)</div>
            </div>
            <div class="grade-item">
                <div class="grade-bar-wrap">
                    <div class="grade-bar bar-d" style="width:{{ $total > 0 ? round(($gradeD/$total)*100) : 0 }}%"></div>
                </div>
                <div class="grade-label">D (35–49)</div>
                <div class="grade-count">{{ $gradeD }} student(s)</div>
            </div>
            <div class="grade-item">
                <div class="grade-bar-wrap">
                    <div class="grade-bar bar-f" style="width:{{ $total > 0 ? round(($gradeF/$total)*100) : 0 }}%"></div>
                </div>
                <div class="grade-label">F (Below 35)</div>
                <div class="grade-count">{{ $gradeF }} student(s)</div>
            </div>
        </div>
    </div>
    @endif

    {{-- ── RESULTS TABLE ── --}}
    <div class="table-section">
        <div class="table-title">Detailed Results — {{ $total }} Record(s)</div>

        @if($total === 0)
            <div style="text-align:center; padding:40px; color:#9ca3af; font-size:12px;">
                No submissions found for the selected filters.
            </div>
        @else
        <table>
            <thead>
                <tr>
                    <th style="width:4%; text-align:center;">#</th>
                    <th style="width:22%;">Student Name</th>
                    <th style="width:22%;">Quiz Title</th>
                    <th style="width:16%;">Subject</th>
                    <th style="width:10%; text-align:center;">Class Grade</th>
                    <th style="width:10%; text-align:center;">Grade</th>
                    <th style="width:10%; text-align:center;">Score</th>
                    <th style="width:16%;">Completed At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($submissions as $index => $sub)
                @php
                    $score = $sub->score ?? 0;
                    $rank  = $index + 1;
                    $medal = $rank === 1 ? '🥇' : ($rank === 2 ? '🥈' : ($rank === 3 ? '🥉' : ''));
                @endphp
                <tr>
                    <td class="td-num">
                        @if($medal)<span class="medal">{{ $medal }}</span>@else{{ $rank }}@endif
                    </td>
                    <td class="td-name">{{ $sub->student->full_name ?? ($sub->student->first_name . ' ' . $sub->student->last_name) ?? '—' }}</td>
                    <td class="td-sub">{{ $sub->quiz->title ?? '—' }}</td>
                    <td class="td-sub">{{ $sub->quiz->subject->name ?? '—' }}</td>
                    <td class="td-center">
                        <span class="grade-pill pill-grade">Grade {{ $sub->quiz->grade ?? '—' }}</span>
                    </td>
                    <td class="td-center">
                        <span class="score-badge {{ scoreBadgeClass($score) }}" style="background:transparent; border:none; font-weight:800;">
                            {{ gradeLetter($score) }}
                        </span>
                    </td>
                    <td class="td-center">
                        <span class="score-badge {{ scoreBadgeClass($score) }}">{{ $score }}%</span>
                    </td>
                    <td style="font-size:8px; color:#6b7280;">{{ $sub->created_at->format('d M Y, h:i A') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    {{-- ── FOOTER ── --}}
    <div class="footer">
        <div class="footer-left">
            <span class="footer-brand">CodeXpress</span> &mdash; Confidential Report &mdash; For Internal Use Only
        </div>
        <div class="footer-right">
            Generated on {{ now()->format('d M Y \a\t h:i A') }}
        </div>
    </div>

</body>
</html>
