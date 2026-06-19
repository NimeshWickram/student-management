@extends('teacher.layouts.layout')

@section('title', 'Teacher Dashboard — CodeXpress')
@section('page-title', 'Overview Dashboard')

@section('content')
<div style="display: flex; flex-direction: column; gap: 2rem;">
    <!-- Welcome section banner (Student Portal Style Gradient) -->
    <div class="card" style="padding: 2.25rem 2rem; position: relative; overflow: hidden; background: linear-gradient(135deg, #4f46e5 0%, #312e81 100%); border: none; color: var(--white); box-shadow: 0 10px 25px rgba(79, 70, 229, 0.15);">
        <div style="position: relative; z-index: 2;">
            <h2 style="font-size: 1.85rem; font-weight: 800; letter-spacing: -0.02em; margin-bottom: 0.5rem; color: var(--white);">Welcome back, {{ $teacher->salutation ? $teacher->salutation . ' ' : '' }}{{ $teacher->last_name }}! 👋</h2>
            <p style="color: rgba(255, 255, 255, 0.9); font-size: 0.95rem; max-width: 650px; line-height: 1.6; margin-bottom: 1rem;">
                Here is your academic workspace overview for <strong style="color: var(--white);">{{ $teacher->tenant->name }}</strong>. Use the panel resources to track curriculum progress, review live student results, and analyze MCQ evaluations.
            </p>
            <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                <span style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.35); color: var(--white); padding: 0.35rem 0.85rem; border-radius: 30px; font-size: 0.78rem; font-weight: 800; display: inline-flex; align-items: center; gap: 0.35rem; font-family: monospace; letter-spacing: 0.05em;">
                    <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="width: 14px; height: 14px;"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M16 10h.01M12 10h.01M8 10h.01M16 14h.01M12 14h.01M8 14h.01"/></svg>
                    ID: {{ $teacher->teacher_id ?? 'N/A' }}
                </span>
                <span style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); color: var(--white); padding: 0.35rem 0.85rem; border-radius: 30px; font-size: 0.78rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.35rem;">
                    <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="width: 14px; height: 14px;"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    {{ $teacher->subject }}
                </span>
            </div>
        </div>
        <div style="position: absolute; right: 2rem; bottom: -20px; font-size: 8rem; font-weight: 900; color: rgba(255,255,255,0.04); pointer-events: none; user-select: none;">
            EDU
        </div>
    </div>

    <!-- Stats Grid (Matching Student Style) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem;">
        <!-- Card 1: Active Quizzes -->
        <div class="card" style="display: flex; align-items: center; gap: 1.25rem;">
            <div style="width: 50px; height: 50px; border-radius: 12px; background: rgba(79, 70, 229, 0.1); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 24px; height: 24px; color: var(--accent);"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
            </div>
            <div>
                <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--g500); margin-bottom: 0.25rem;">Active Quizzes</div>
                <div style="font-size: 1.85rem; font-weight: 800; letter-spacing: -0.02em; color: var(--black);">{{ $stats['total_quizzes'] }}</div>
            </div>
        </div>

        <!-- Card 2: Total Submissions -->
        <div class="card" style="display: flex; align-items: center; gap: 1.25rem;">
            <div style="width: 50px; height: 50px; border-radius: 12px; background: rgba(16, 185, 129, 0.1); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 24px; height: 24px; color: #10b981;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <div>
                <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--g500); margin-bottom: 0.25rem;">Total Submissions</div>
                <div style="font-size: 1.85rem; font-weight: 800; letter-spacing: -0.02em; color: var(--black);">{{ $stats['total_submissions'] }}</div>
            </div>
        </div>

        <!-- Card 3: Average Score -->
        <div class="card" style="display: flex; align-items: center; gap: 1.25rem;">
            <div style="width: 50px; height: 50px; border-radius: 12px; background: rgba(245, 158, 11, 0.1); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 24px; height: 24px; color: #f59e0b;"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/></svg>
            </div>
            <div>
                <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--g500); margin-bottom: 0.25rem;">Average Score</div>
                <div style="font-size: 1.85rem; font-weight: 800; letter-spacing: -0.02em; display: flex; align-items: baseline; gap: 0.1rem; color: var(--black);">
                    {{ $stats['average_score'] }}<span style="font-size: 1.1rem; color: var(--g500); font-weight: 600;">%</span>
                </div>
            </div>
        </div>

        <!-- Card 4: Unique Students -->
        <div class="card" style="display: flex; align-items: center; gap: 1.25rem;">
            <div style="width: 50px; height: 50px; border-radius: 12px; background: rgba(139, 92, 246, 0.1); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 24px; height: 24px; color: #8b5cf6;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div>
                <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--g500); margin-bottom: 0.25rem;">Students Tested</div>
                <div style="font-size: 1.85rem; font-weight: 800; letter-spacing: -0.02em; color: var(--black);">{{ $stats['unique_students'] }}</div>
            </div>
        </div>
    </div>

    <!-- Tables grid layout -->
    <div style="display: grid; grid-template-columns: 1fr; gap: 2rem;">
        
        <!-- Section: Recent Quiz Analytics -->
        <div class="card" style="padding: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.5rem;">
                <h3 style="font-size: 1.1rem; font-weight: 800; letter-spacing: -0.01em; color: var(--black);">My Quizzes</h3>
                <span style="font-size: 0.75rem; color: var(--g500);">Showing latest 5 quizzes</span>
            </div>

            @if($recentQuizzes->isEmpty())
                <div style="padding: 3rem; text-align: center; border: 1px dashed var(--g300); border-radius: var(--radius-sm); color: var(--g500);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width: 36px; height: 36px; color: var(--g400); margin-bottom: 0.75rem;"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                    <p style="font-size: 0.85rem;">You haven't created any quizzes yet.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Quiz Title</th>
                                <th>Subject</th>
                                <th>Grade/Class</th>
                                <th>Submissions</th>
                                <th style="text-align: right;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentQuizzes as $quiz)
                                <tr>
                                    <td data-label="Quiz Title" style="font-weight: 700; color: var(--black);">{{ $quiz->title }}</td>
                                    <td data-label="Subject">{{ $quiz->subject->name ?? '—' }}</td>
                                    <td data-label="Grade">
                                        <span class="badge badge-outline">Grade {{ $quiz->grade ?? '—' }}</span>
                                    </td>
                                    <td data-label="Submissions" style="font-weight: 700;">{{ $quiz->submissions_count }}</td>
                                    <td data-label="Action" style="text-align: right;">
                                        <a href="{{ route('teacher.results.quiz', $quiz->id) }}" class="btn btn-secondary" style="padding: 0.35rem 0.75rem; font-size: 0.75rem;">
                                            Analytics →
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Section: Recent Submissions -->
        <div class="card" style="padding: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.5rem;">
                <h3 style="font-size: 1.1rem; font-weight: 800; letter-spacing: -0.01em; color: var(--black);">Recent Student Submissions</h3>
                <a href="{{ route('teacher.results.index') }}" class="btn btn-secondary" style="padding: 0.35rem 0.75rem; font-size: 0.75rem;">
                    View All Submissions
                </a>
            </div>

            @if($recentSubmissions->isEmpty())
                <div style="padding: 3rem; text-align: center; border: 1px dashed var(--g300); border-radius: var(--radius-sm); color: var(--g500);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width: 36px; height: 36px; color: var(--g400); margin-bottom: 0.75rem;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    <p style="font-size: 0.85rem;">No student submissions found yet.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Quiz</th>
                                <th>Date/Time</th>
                                <th>Score</th>
                                <th style="text-align: right;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentSubmissions as $sub)
                                <tr>
                                    <td data-label="Student" style="font-weight: 700; color: var(--black);">{{ $sub->student->full_name ?? 'Student' }}</td>
                                    <td data-label="Quiz">{{ $sub->quiz->title ?? '—' }}</td>
                                    <td data-label="Date/Time">{{ $sub->created_at->format('M d, Y — h:i A') }}</td>
                                    <td data-label="Score">
                                        <div style="display: flex; align-items: center; gap: 0.5rem; justify-content: flex-end;">
                                            <div style="font-weight: 800; font-size: 0.95rem; color: var(--black);">{{ $sub->score }}%</div>
                                            <div style="width: 45px; height: 5px; background: var(--g100); border-radius: 3px; overflow: hidden; border: 1px solid var(--g200);">
                                                <div style="width: {{ $sub->score }}%; height: 100%; background: var(--accent);"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Action" style="text-align: right;">
                                        <a href="{{ route('teacher.results.show', $sub->id) }}" class="btn btn-secondary" style="padding: 0.35rem 0.75rem; font-size: 0.75rem;">
                                            Details →
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
