@extends('teacher.layouts.layout')

@section('title', 'Student Performance: ' . $student->full_name . ' — CodeXpress')
@section('page-title', 'Student Performance Timeline')

@section('content')
<div style="display: flex; flex-direction: column; gap: 2rem;">

    <!-- Navigation Header -->
    <div>
        <a href="{{ route('teacher.students.index') }}" class="btn btn-secondary" style="font-size: 0.8rem; padding: 0.4rem 0.8rem;">
            ← Back to Directory
        </a>
    </div>

    <!-- Profile Header Card (Student Themed Style Card) -->
    <div class="card" style="padding: 2rem; display: flex; flex-direction: column; gap: 1.5rem; background: linear-gradient(135deg, rgba(79, 70, 229, 0.05) 0%, rgba(79, 70, 229, 0.01) 100%);">
        <div style="display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap;">
            
            <!-- Avatar -->
            <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--accent); color: var(--white); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 800; flex-shrink: 0; box-shadow: 0 4px 15px rgba(79, 70, 229, 0.2);">
                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
            </div>

            <!-- Profile Title -->
            <div style="flex: 1; min-width: 200px;">
                <span class="badge badge-white" style="margin-bottom: 0.5rem;">Grade {{ $student->grade ?? '—' }} Student</span>
                <h2 style="font-size: 1.6rem; font-weight: 800; letter-spacing: -0.02em; margin-bottom: 0.25rem; color: var(--black);">
                    {{ $student->full_name }}
                </h2>
                <p style="font-size: 0.85rem; color: var(--g600);">
                    Registered Campus: {{ $student->tenant->name ?? '—' }}
                </p>
            </div>
        </div>

        <!-- Details Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; border-top: 1px solid var(--g200); padding-top: 1.25rem;">
            <div>
                <span class="label">Email Address</span>
                <div style="font-size: 0.9rem; color: var(--black); margin-top: 0.15rem; font-weight: 600;">{{ $student->email }}</div>
            </div>
            <div>
                <span class="label">Phone Number</span>
                <div style="font-size: 0.9rem; color: var(--black); margin-top: 0.15rem; font-weight: 600;">{{ $student->phone_number ?? '—' }}</div>
            </div>
            <div>
                <span class="label">Course Stream</span>
                <div style="font-size: 0.9rem; color: var(--black); margin-top: 0.15rem; font-weight: 600;">{{ $student->course ?? '—' }}</div>
            </div>
            <div>
                <span class="label">Enrolled Date</span>
                <div style="font-size: 0.9rem; color: var(--black); margin-top: 0.15rem; font-weight: 600;">{{ $student->created_at->format('M d, Y') }}</div>
            </div>
        </div>
    </div>

    <!-- Stats Summary Row -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
        <!-- Stat: Quizzes Taken -->
        <div class="card" style="display: flex; align-items: center; gap: 1.25rem; padding: 1.25rem 1.5rem;">
            <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(79, 70, 229, 0.1); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; color: var(--accent);"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <div>
                <span class="label">Assessments Taken</span>
                <div style="font-size: 1.35rem; font-weight: 800; margin-top: 0.15rem; color: var(--black);">{{ $totalQuizzes }}</div>
            </div>
        </div>

        <!-- Stat: Average Score -->
        <div class="card" style="display: flex; align-items: center; gap: 1.25rem; padding: 1.25rem 1.5rem;">
            <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(245, 158, 11, 0.1); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; color: #f59e0b;"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/></svg>
            </div>
            <div>
                <span class="label">Average Grade</span>
                <div style="font-size: 1.35rem; font-weight: 800; margin-top: 0.15rem; display: flex; align-items: baseline; color: var(--black);">
                    {{ $averageScore }}<span style="font-size: 0.85rem; color: var(--g500); font-weight: 600; margin-left: 0.1rem;">%</span>
                </div>
            </div>
        </div>

        <!-- Stat: Highest Score -->
        <div class="card" style="display: flex; align-items: center; gap: 1.25rem; padding: 1.25rem 1.5rem;">
            <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(16, 185, 129, 0.1); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; color: #10b981;"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            </div>
            <div>
                <span class="label">Top Score</span>
                <div style="font-size: 1.35rem; font-weight: 800; margin-top: 0.15rem; display: flex; align-items: baseline; color: var(--black);">
                    {{ $highestScore }}<span style="font-size: 0.85rem; color: var(--g500); font-weight: 600; margin-left: 0.1rem;">%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline of quiz submissions -->
    <div class="card" style="padding: 1.5rem;">
        <h3 style="font-size: 1.1rem; font-weight: 800; letter-spacing: -0.01em; margin-bottom: 1.25rem; color: var(--black);">Assessment Performance Timeline</h3>
        
        @if($submissions->isEmpty())
            <div style="padding: 3rem; text-align: center; border: 1px dashed var(--g300); border-radius: var(--radius-sm); color: var(--g500);">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width: 36px; height: 36px; color: var(--g400); margin-bottom: 0.75rem;"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                <p style="font-size: 0.85rem;">This student hasn't attempted any quizzes yet.</p>
            </div>
        @else
            <div class="table-container table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Quiz Title</th>
                            <th>Subject</th>
                            <th>Grade/Class</th>
                            <th>Completed At</th>
                            <th>Score achieved</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($submissions as $sub)
                            <tr>
                                <td data-label="Quiz Title" style="font-weight: 700; color: var(--black);">{{ $sub->quiz->title ?? '—' }}</td>
                                <td data-label="Subject">{{ $sub->quiz->subject->name ?? '—' }}</td>
                                <td data-label="Grade/Class">
                                    <span class="badge badge-outline">Grade {{ $sub->quiz->grade ?? '—' }}</span>
                                </td>
                                <td data-label="Completed At">{{ $sub->created_at->format('M d, Y — h:i A') }}</td>
                                <td data-label="Score">
                                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.5rem; width: 100%;">
                                        <span style="font-weight: 800; font-size: 0.95rem; color: var(--black);">{{ $sub->score }}%</span>
                                        <div style="width: 45px; height: 5px; background: var(--g100); border-radius: 3px; overflow: hidden; border: 1px solid var(--g200);">
                                            <div style="width: {{ $sub->score }}%; height: 100%; background: var(--accent);"></div>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Action" style="text-align: right;">
                                    <div class="actions" style="display: flex; justify-content: flex-end;">
                                        <a href="{{ route('teacher.results.show', $sub->id) }}" class="btn btn-secondary" style="padding: 0.35rem 0.75rem; font-size: 0.75rem;">
                                            Review Sheet →
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection
