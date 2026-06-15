@extends('teacher.layouts.layout')

@section('title', 'My Students — CodeXpress')
@section('page-title', 'My Students Directory')

@section('content')
<div style="display: flex; flex-direction: column; gap: 1.5rem;">

    <!-- Filter Card -->
    <div class="card" style="padding: 1.25rem 1.5rem;">
        <form action="{{ route('teacher.students.index') }}" method="GET" style="display: flex; align-items: flex-end; gap: 1rem; flex-wrap: wrap; width: 100%;">
            
            <!-- Search field -->
            <div style="flex: 1; min-width: 200px;">
                <label class="label" for="search">Search Student</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" class="input" placeholder="Search by name or email..." style="margin-top: 0.4rem;">
            </div>

            <!-- Grade filter dropdown -->
            <div style="min-width: 150px;">
                <label class="label" for="grade">Grade/Class</label>
                <select name="grade" id="grade" class="input" style="margin-top: 0.4rem; cursor: pointer;">
                    <option value="">All Grades</option>
                    @foreach($grades as $grade)
                        <option value="{{ $grade }}" {{ request('grade') === (string)$grade ? 'selected' : '' }}>
                            Grade {{ $grade }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Action buttons -->
            <div style="display: flex; gap: 0.5rem; flex-shrink: 0;">
                <button type="submit" class="btn btn-primary" style="padding: 0.65rem 1rem;">
                    Filter Directory
                </button>
                @if(request()->anyFilled(['search', 'grade']))
                    <a href="{{ route('teacher.students.index') }}" class="btn btn-secondary" style="padding: 0.65rem 1rem;">
                        Reset
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- Table Card -->
    <div class="card" style="padding: 1.5rem;">
        @if($students->isEmpty())
            <div style="padding: 4rem; text-align: center; border: 1px dashed var(--g300); border-radius: var(--radius-sm); color: var(--g500);">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width: 48px; height: 48px; color: var(--g400); margin-bottom: 0.75rem;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                <h4 style="font-size: 1rem; font-weight: 700; color: var(--black); margin-bottom: 0.25rem;">No Students Found</h4>
                <p style="font-size: 0.85rem;">Try adjusting your filters or search query.</p>
            </div>
        @else
            <div class="table-container table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Email Address</th>
                            <th>Grade</th>
                            <th>Quizzes Taken</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            <tr>
                                <td data-label="Student Name" style="font-weight: 700; color: var(--black);">
                                    {{ $student->full_name }}
                                </td>
                                <td data-label="Email Address">{{ $student->email }}</td>
                                <td data-label="Grade">
                                    <span class="badge badge-outline">Grade {{ $student->grade ?? '—' }}</span>
                                </td>
                                <td data-label="Quizzes Taken" style="font-weight: 700; font-size: 0.95rem;">
                                    {{ $student->submissions_count }}
                                </td>
                                <td data-label="Action" style="text-align: right;">
                                    <div class="actions" style="display: flex; justify-content: flex-end;">
                                        <a href="{{ route('teacher.students.show', $student->id) }}" class="btn btn-secondary" style="padding: 0.35rem 0.75rem; font-size: 0.75rem;">
                                            View Performance Timeline →
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Custom Pagination -->
            @if($students->hasPages())
                <div style="margin-top: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                    <div style="font-size: 0.75rem; color: var(--g500);">
                        Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }} students
                    </div>
                    <div style="display: flex; gap: 0.25rem;">
                        {{-- Previous Page Link --}}
                        @if($students->onFirstPage())
                            <span class="btn btn-secondary" style="opacity: 0.3; cursor: not-allowed; padding: 0.4rem 0.8rem; font-size: 0.75rem;">Previous</span>
                        @else
                            <a href="{{ $students->previousPageUrl() }}" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.75rem;">Previous</a>
                        @endif

                        {{-- Next Page Link --}}
                        @if($students->hasMorePages())
                            <a href="{{ $students->nextPageUrl() }}" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.75rem;">Next</a>
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
