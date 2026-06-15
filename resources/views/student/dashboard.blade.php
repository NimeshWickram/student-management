@extends('student.layouts.layout')

@section('title', 'Student Portal Dashboard — CodeXpress')
@section('page-title', 'Portal Dashboard')
@section('breadcrumb')
<a href="{{ route('student.dashboard') }}">Student Panel</a> / Home
@endsection

@section('styles')
<style>
    .welcome-banner {
        background: linear-gradient(135deg, #4f46e5 0%, #312e81 100%);
        border-radius: var(--radius);
        padding: 2.25rem;
        color: var(--white);
        margin-bottom: 2rem;
        box-shadow: 0 10px 25px rgba(79, 70, 229, 0.15);
        position: relative;
        overflow: hidden;
    }
    .welcome-banner::after {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.05);
        top: -100px;
        right: -50px;
        pointer-events: none;
    }
    .welcome-banner h1 {
        font-size: 1.85rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        margin-bottom: 0.5rem;
    }
    .welcome-banner p {
        font-size: 0.95rem;
        opacity: 0.9;
        line-height: 1.6;
        max-width: 600px;
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }
    .stat-card {
        background: var(--white);
        border: 1px solid var(--g200);
        border-radius: var(--radius);
        padding: 1.75rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: transform var(--tr), box-shadow var(--tr);
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }
    .stat-details h3 {
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--g500);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.4rem;
    }
    .stat-val {
        font-size: 2rem;
        font-weight: 800;
        color: var(--black);
        letter-spacing: -0.02em;
        line-height: 1.1;
    }
    .stat-desc {
        font-size: 0.75rem;
        color: var(--g400);
        margin-top: 0.4rem;
    }
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .stat-icon svg { width: 24px; height: 24px; }

    .stat-card.course .stat-icon { background: rgba(79, 70, 229, 0.1); color: var(--accent); }
    .stat-card.quizzes .stat-icon { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .stat-card.score .stat-icon { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }

    .grid-container {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        align-items: start;
    }

    @media (max-width: 968px) {
        .grid-container {
            grid-template-columns: 1fr;
        }
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        color: var(--black);
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .section-title svg { width: 20px; height: 20px; color: var(--accent); }

    .list-wrapper {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .quiz-item-card {
        background: var(--white);
        border: 1px solid var(--g200);
        border-radius: var(--radius);
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.01);
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
        transition: border-color var(--tr), box-shadow var(--tr);
    }
    .quiz-item-card:hover {
        border-color: var(--g300);
        box-shadow: 0 8px 25px rgba(0,0,0,0.05);
    }
    .quiz-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
    }
    .quiz-title-area h4 {
        font-size: 1rem;
        font-weight: 700;
        color: var(--black);
        letter-spacing: -0.01em;
        margin-bottom: 0.25rem;
    }
    .quiz-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        font-size: 0.78rem;
        color: var(--g500);
        align-items: center;
    }
    .meta-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    .meta-tag svg { width: 14px; height: 14px; }
    
    .status-badge {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.3rem 0.75rem;
        border-radius: 30px;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    .status-badge.pending {
        background: rgba(79, 70, 229, 0.06);
        color: var(--accent);
        border: 1px solid rgba(79, 70, 229, 0.15);
    }
    .status-badge.completed {
        background: rgba(16, 185, 129, 0.06);
        color: #047857;
        border: 1px solid rgba(16, 185, 129, 0.15);
    }

    .quiz-action-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 1rem;
        border-top: 1px solid var(--g200);
    }
    
    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.55rem 1.25rem;
        font-family: inherit;
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: var(--radius-sm);
        text-decoration: none;
        transition: all var(--tr);
        cursor: pointer;
    }
    .btn-action.primary {
        background: var(--accent);
        color: var(--white);
        border: 1px solid var(--accent);
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.2);
    }
    .btn-action.primary:hover {
        background: var(--accent-light);
        border-color: var(--accent-light);
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(79, 70, 229, 0.35);
    }
    .btn-action.outline {
        background: var(--white);
        color: var(--g800);
        border: 1px solid var(--g300);
    }
    .btn-action.outline:hover {
        background: var(--g50);
        border-color: var(--black);
        color: var(--black);
    }

    .subject-pill-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .subject-pill-card {
        background: var(--white);
        border: 1px solid var(--g200);
        border-radius: var(--radius-sm);
        padding: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    .subj-header {
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--black);
    }
    .subj-code {
        color: var(--accent);
        font-family: monospace;
        font-size: 0.8rem;
        background: var(--accent-glow);
        padding: 0.1rem 0.4rem;
        border-radius: 4px;
    }
    .subj-desc {
        font-size: 0.78rem;
        color: var(--g500);
        line-height: 1.4;
        margin-top: 0.25rem;
    }
    .subj-footer {
        font-size: 0.7rem;
        color: var(--g400);
        margin-top: 0.4rem;
        display: flex;
        justify-content: flex-end;
    }
    .empty-state {
        text-align: center;
        padding: 3rem;
        background: var(--white);
        border: 1px dashed var(--g300);
        border-radius: var(--radius);
        color: var(--g400);
    }
    .empty-state svg { width: 48px; height: 48px; margin-bottom: 1rem; color: var(--g300); }
    .empty-state h4 { color: var(--black); font-weight: 700; margin-bottom: 0.25rem; }
    .empty-state p { font-size: 0.825rem; }
</style>
@endsection

@section('content')
    <div class="welcome-banner">
        <h1>Welcome back, {{ $student->first_name }}! 👋</h1>
        <p style="margin-bottom: 1.25rem;">You are logged into the Student Portal. Here you can access educational assessments, track subject outlines, and evaluate your quiz performance scores dynamically.</p>
        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            <span style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.35); color: var(--white); padding: 0.35rem 0.85rem; border-radius: 30px; font-size: 0.78rem; font-weight: 800; display: inline-flex; align-items: center; gap: 0.35rem; font-family: monospace; letter-spacing: 0.05em;">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="width: 14px; height: 14px;"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M16 10h.01M12 10h.01M8 10h.01M16 14h.01M12 14h.01M8 14h.01"/></svg>
                ID: {{ $student->student_id ?? 'N/A' }}
            </span>
            <span style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); color: var(--white); padding: 0.35rem 0.85rem; border-radius: 30px; font-size: 0.78rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.35rem;">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                Grade Scoping: {{ $student->grade }}
            </span>
            <span style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); color: var(--white); padding: 0.35rem 0.85rem; border-radius: 30px; font-size: 0.78rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.35rem;">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5"/></svg>
                Campus: {{ $student->tenant ? $student->tenant->name : 'Default Campus' }}
            </span>
        </div>
    </div>

    <div class="stats-row">
        <!-- Enrolled Program -->
        <div class="stat-card course">
            <div class="stat-details">
                <h3>My Course Stream</h3>
                <div class="stat-val" style="font-size: 1.35rem; padding: 0.35rem 0;">{{ $stats['course'] }}</div>
                <div class="stat-desc">Primary enrolled program</div>
            </div>
            <div class="stat-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
            </div>
        </div>

        <!-- Quizzes Attempted -->
        <div class="stat-card quizzes">
            <div class="stat-details">
                <h3>Quizzes Completed</h3>
                <div class="stat-val">{{ $stats['quizzes_taken'] }}</div>
                <div class="stat-desc">Total submitted papers</div>
            </div>
            <div class="stat-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <!-- Average Score -->
        <div class="stat-card score">
            <div class="stat-details">
                <h3>Average Score</h3>
                <div class="stat-val">
                    {{ $stats['average_score'] }}<span style="font-size: 1.2rem; font-weight: 600;">%</span>
                </div>
                <div class="stat-desc">Cumulative grade average</div>
            </div>
            <div class="stat-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
            </div>
        </div>
    </div>

    <div class="grid-container">
        <!-- Available Quizzes -->
        <div>
            <h2 class="section-title">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                Educational MCQ Assessments
            </h2>

            @if($quizzes->isEmpty())
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h4>No Assessments Yet</h4>
                    <p>There are currently no quizzes registered in your student curriculum.</p>
                </div>
            @else
                <div class="list-wrapper">
                    @foreach($quizzes as $quiz)
                        @php
                            $sub = $submissions->get($quiz->id);
                            $isCompleted = $sub !== null;
                            $questionsCount = 0;
                            if (!empty($quiz->manual_content)) {
                                $decoded = json_decode($quiz->manual_content, true);
                                $questionsCount = is_array($decoded) ? count($decoded) : 0;
                            }
                        @endphp
                        <div class="quiz-item-card">
                            <div class="quiz-header">
                                <div class="quiz-title-area">
                                    <h4>{{ $quiz->title }}</h4>
                                    <div class="quiz-meta">
                                        <span class="meta-tag" title="Assigned Subject">
                                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                                            {{ $quiz->subject->name }} ({{ $quiz->subject->code }})
                                        </span>
                                        <span class="meta-tag" title="Assigned Teacher">
                                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            Lec. {{ $quiz->teacher->first_name }} {{ $quiz->teacher->last_name }}
                                        </span>
                                        <span class="meta-tag" title="Questions Count">
                                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            {{ $questionsCount }} MCQs
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    @if($isCompleted)
                                        <span class="status-badge completed">
                                            ✅ Passed — Score: {{ $sub->score }}%
                                        </span>
                                    @else
                                        <span class="status-badge pending">
                                            ⏳ Ready to Start
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="quiz-action-bar">
                                <span style="font-size: 0.75rem; color: var(--g400);">
                                    Published: {{ $quiz->created_at->format('d M Y') }}
                                </span>

                                @if($isCompleted)
                                    <div style="display: flex; gap: 0.5rem;">
                                        <a href="{{ route('student.quiz.result', $sub->id) }}" class="btn-action outline">
                                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            Review Responses
                                        </a>
                                        <a href="{{ route('student.quiz.take', $quiz->id) }}" class="btn-action outline" style="padding: 0.55rem 0.75rem;" title="Retake Quiz">
                                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H18.24"/></svg>
                                        </a>
                                    </div>
                                @else
                                    <a href="{{ route('student.quiz.take', $quiz->id) }}" class="btn-action primary">
                                        Take MCQ Assessment
                                        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Subjects Overview -->
        <div>
            <h2 class="section-title">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                Academic Subjects
            </h2>

            <div class="subject-pill-list">
                @forelse($subjects as $subj)
                    <div class="subject-pill-card">
                        <div class="subj-header">
                            <span>{{ $subj->name }}</span>
                            <span class="subj-code">{{ $subj->code }}</span>
                        </div>
                        <div class="subj-desc">{{ $subj->description }}</div>
                        <div class="subj-footer">
                            <span>Credits weight: <strong>{{ $subj->credits }} units</strong></span>
                        </div>
                    </div>
                @empty
                    <div class="subject-pill-card" style="text-align: center; color: var(--g400); font-size: 0.8rem; padding: 1.5rem 1rem;">
                        No subjects assigned to your course stream yet.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
