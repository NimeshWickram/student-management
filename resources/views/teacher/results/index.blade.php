@extends('teacher.layouts.layout')

@section('title', 'Quiz Submissions — CodeXpress')
@section('page-title', 'Quiz Submissions & Results')

@section('content')
<div style="display: flex; flex-direction: column; gap: 1.5rem;">

    <!-- Filter Card (Matching Student Style Card) -->
    <div class="card" style="padding: 1.25rem 1.5rem;">
        <form action="{{ route('teacher.results.index') }}" method="GET" style="display: flex; align-items: flex-end; gap: 1rem; flex-wrap: wrap; width: 100%;">
            
            <!-- Search field -->
            <div style="flex: 1; min-width: 200px;">
                <label class="label" for="search">Search Student</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" class="input" placeholder="Enter student name..." style="margin-top: 0.4rem;">
            </div>

            <!-- Quiz selection dropdown -->
            <div style="min-width: 200px;">
                <label class="label" for="quiz_id">Quiz</label>
                <select name="quiz_id" id="quiz_id" class="input" style="margin-top: 0.4rem; cursor: pointer;">
                    <option value="">All Quizzes</option>
                    @foreach($quizzes as $quiz)
                        <option value="{{ $quiz->id }}" {{ request('quiz_id') == $quiz->id ? 'selected' : '' }}>
                            {{ $quiz->title }} (Grade {{ $quiz->grade ?? '—' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Grade filter dropdown -->
            <div style="min-width: 150px;">
                <label class="label" for="grade">Grade</label>
                <select name="grade" id="grade" class="input" style="margin-top: 0.4rem; cursor: pointer;">
                    <option value="">All Grades</option>
                    @foreach($grades as $grade)
                        <option value="{{ $grade }}" {{ request('grade') === (string)$grade ? 'selected' : '' }}>
                            Grade {{ $grade }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Score range filter dropdown -->
            <div style="min-width: 150px;">
                <label class="label" for="score_range">Marks / Score</label>
                <select name="score_range" id="score_range" class="input" style="margin-top: 0.4rem; cursor: pointer;">
                    <option value="">All Marks</option>
                    <option value="below_35" {{ request('score_range') === 'below_35' ? 'selected' : '' }}>Below 35%</option>
                    <option value="above_35" {{ request('score_range') === 'above_35' ? 'selected' : '' }}>&ge; 35%</option>
                    <option value="above_50" {{ request('score_range') === 'above_50' ? 'selected' : '' }}>&ge; 50%</option>
                    <option value="above_65" {{ request('score_range') === 'above_65' ? 'selected' : '' }}>&ge; 65%</option>
                    <option value="above_75" {{ request('score_range') === 'above_75' ? 'selected' : '' }}>&ge; 75%</option>
                </select>
            </div>

            <!-- Action buttons -->
            <div style="display: flex; gap: 0.5rem; flex-shrink: 0;">
                <button type="submit" class="btn btn-primary" style="padding: 0.65rem 1rem;">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'quiz_id', 'grade', 'score_range']))
                    <a href="{{ route('teacher.results.index') }}" class="btn btn-secondary" style="padding: 0.65rem 1rem;">
                        Reset
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- Export Action Toolbar -->
    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.5rem; margin-bottom: 0.25rem;">
        <a href="{{ route('teacher.results.export.excel', request()->query()) }}" class="btn" style="font-size: 0.75rem; padding: 0.5rem 0.9rem; background: #ecfdf5; color: #059669; border: 1.5px solid #a7f3d0; text-decoration: none;" id="btn-export-excel">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px; vertical-align: middle; margin-right: 4px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="16" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            Export Excel
        </a>
        <a href="{{ route('teacher.results.export.pdf', request()->query()) }}" class="btn" style="font-size: 0.75rem; padding: 0.5rem 0.9rem; background: #fef2f2; color: #dc2626; border: 1.5px solid #fecaca; text-decoration: none;" id="btn-export-pdf">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px; vertical-align: middle; margin-right: 4px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="16" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            Export PDF
        </a>
    </div>

    <!-- Table Card -->
    <div class="card" style="padding: 1.5rem;">
        @if($submissions->isEmpty())
            <div style="padding: 4rem; text-align: center; border: 1px dashed var(--g300); border-radius: var(--radius-sm); color: var(--g500);">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width: 48px; height: 48px; color: var(--g400); margin-bottom: 0.75rem;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                <h4 style="font-size: 1rem; font-weight: 700; color: var(--black); margin-bottom: 0.25rem;">No Submissions Found</h4>
                <p style="font-size: 0.85rem;">Try adjusting your filters or search query.</p>
            </div>
        @else
            <div class="table-container table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Quiz Title</th>
                            <th>Grade</th>
                            <th>Completed At</th>
                            <th>Score</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($submissions as $sub)
                            <tr>
                                <td data-label="Student" style="font-weight: 700; color: var(--black);">
                                    {{ $sub->student->full_name ?? 'Student' }}
                                </td>
                                <td data-label="Quiz Title">{{ $sub->quiz->title ?? '—' }}</td>
                                <td data-label="Grade">
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
                                            Review Details →
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Custom Pagination -->
            @if($submissions->hasPages())
                <div style="margin-top: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                    <div style="font-size: 0.75rem; color: var(--g500);">
                        Showing {{ $submissions->firstItem() }} to {{ $submissions->lastItem() }} of {{ $submissions->total() }} submissions
                    </div>
                    <div style="display: flex; gap: 0.25rem;">
                        {{-- Previous Page Link --}}
                        @if($submissions->onFirstPage())
                            <span class="btn btn-secondary" style="opacity: 0.3; cursor: not-allowed; padding: 0.4rem 0.8rem; font-size: 0.75rem;">Previous</span>
                        @else
                            <a href="{{ $submissions->previousPageUrl() }}" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.75rem;">Previous</a>
                        @endif

                        {{-- Next Page Link --}}
                        @if($submissions->hasMorePages())
                            <a href="{{ $submissions->nextPageUrl() }}" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.75rem;">Next</a>
                        @else
                            <span class="btn btn-secondary" style="opacity: 0.3; cursor: not-allowed; padding: 0.4rem 0.8rem; font-size: 0.75rem;">Next</span>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
