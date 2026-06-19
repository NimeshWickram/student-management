@extends('teacher.layouts.layout')

@section('title', 'Quiz Analytics — ' . $quiz->title . ' — CodeXpress')
@section('page-title', 'Quiz Performance Analytics')

@section('content')
<div style="display: flex; flex-direction: column; gap: 2rem;">

    <!-- Navigation Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary" style="font-size: 0.8rem; padding: 0.4rem 0.8rem;">
            ← Back to Dashboard
        </a>
        <span style="font-size: 0.8rem; color: var(--g500);">
            Class: <strong style="color: var(--black);">Grade {{ $quiz->grade ?? '—' }}</strong> | Subject: <strong style="color: var(--black);">{{ $quiz->subject->name ?? '—' }}</strong>
        </span>
    </div>

    <!-- Quiz Title Card -->
    <div class="card" style="padding: 2rem;">
        <span class="badge badge-white" style="margin-bottom: 0.75rem;">Quiz Performance Report</span>
        <h2 style="font-size: 1.6rem; font-weight: 800; letter-spacing: -0.02em; margin-bottom: 0.5rem; color: var(--black);">
            {{ $quiz->title }}
        </h2>
        <p style="font-size: 0.85rem; color: var(--g500);">
            Detailed analytics for all student submissions. Created by you on {{ $quiz->created_at->format('M d, Y') }}.
        </p>
    </div>

    <!-- Stats Grid (Indigo Accent Theme) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem;">
        <!-- Stat: Total Submissions -->
        <div class="card" style="display: flex; align-items: center; gap: 1.25rem;">
            <div style="width: 46px; height: 46px; border-radius: 10px; background: rgba(79, 70, 229, 0.1); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 20px; height: 20px; color: var(--accent);"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div>
                <span class="label">Total Attempts</span>
                <div style="font-size: 1.5rem; font-weight: 800; margin-top: 0.15rem; color: var(--black);">{{ $totalSubmissions }}</div>
            </div>
        </div>

        <!-- Stat: Average Score -->
        <div class="card" style="display: flex; align-items: center; gap: 1.25rem;">
            <div style="width: 46px; height: 46px; border-radius: 10px; background: rgba(16, 185, 129, 0.1); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 20px; height: 20px; color: #10b981;"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/></svg>
            </div>
            <div>
                <span class="label">Average Score</span>
                <div style="font-size: 1.5rem; font-weight: 800; margin-top: 0.15rem; display: flex; align-items: baseline; color: var(--black);">
                    {{ $averageScore }}<span style="font-size: 0.85rem; color: var(--g500); font-weight: 600; margin-left: 0.1rem;">%</span>
                </div>
            </div>
        </div>

        <!-- Stat: Highest Score -->
        <div class="card" style="display: flex; align-items: center; gap: 1.25rem;">
            <div style="width: 46px; height: 46px; border-radius: 10px; background: rgba(245, 158, 11, 0.1); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 20px; height: 20px; color: #f59e0b;"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            </div>
            <div>
                <span class="label">Highest Score</span>
                <div style="font-size: 1.5rem; font-weight: 800; margin-top: 0.15rem; display: flex; align-items: baseline; color: var(--black);">
                    {{ $highestScore }}<span style="font-size: 0.85rem; color: var(--g500); font-weight: 600; margin-left: 0.1rem;">%</span>
                </div>
            </div>
        </div>

        <!-- Stat: Lowest Score -->
        <div class="card" style="display: flex; align-items: center; gap: 1.25rem;">
            <div style="width: 46px; height: 46px; border-radius: 10px; background: rgba(139, 92, 246, 0.1); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 20px; height: 20px; color: #8b5cf6;"><line x1="18" y1="15" x2="12" y2="15"/><line x1="6" y1="15" x2="6" y2="15"/></svg>
            </div>
            <div>
                <span class="label">Lowest Score</span>
                <div style="font-size: 1.5rem; font-weight: 800; margin-top: 0.15rem; display: flex; align-items: baseline; color: var(--black);">
                    {{ $lowestScore }}<span style="font-size: 0.85rem; color: var(--g500); font-weight: 600; margin-left: 0.1rem;">%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Charts -->
    <div style="display: grid; grid-template-columns: 1fr; gap: 2rem;">
        
        <!-- Score Distribution Indigo Chart -->
        <div class="card" style="padding: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 800; letter-spacing: -0.01em; margin-bottom: 1.5rem; color: var(--black);">Score Distribution</h3>
            
            <div style="display: flex; flex-direction: column; gap: 1rem; width: 100%;">
                @foreach($scoreDistribution as $range => $count)
                    @php
                        $percentage = $totalSubmissions > 0 ? round(($count / $totalSubmissions) * 100) : 0;
                    @endphp
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <!-- Range Label -->
                        <span style="font-size: 0.8rem; font-weight: 700; width: 60px; color: var(--g500);">{{ $range }}%</span>
                        
                        <!-- Progress bar -->
                        <div style="flex: 1; height: 18px; background: var(--g100); border: 1px solid var(--g200); border-radius: 4px; overflow: hidden; position: relative;">
                            <div style="width: {{ $percentage }}%; height: 100%; background: var(--accent); transition: width 0.6s ease;"></div>
                        </div>

                        <!-- Count and Percentage -->
                        <span style="font-size: 0.8rem; font-weight: 700; width: 70px; text-align: right; color: var(--black);">
                            {{ $count }} ({{ $percentage }}%)
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Question Performance Accuracy -->
        <div class="card" style="padding: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 800; letter-spacing: -0.01em; margin-bottom: 1.25rem; color: var(--black);">Question Accuracy Breakdown</h3>
            
            <div class="table-container table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Question #</th>
                            <th>Question Text</th>
                            <th style="text-align: center;">Correct Responses</th>
                            <th>Accuracy</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($questionStats as $qIdx => $stat)
                            <tr>
                                <td data-label="Question #" style="font-weight: 700; width: 80px; text-align: center; color: var(--black);">#{{ $qIdx + 1 }}</td>
                                <td data-label="Question Text" style="max-width: 400px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-weight: 600; color: var(--black);">
                                    {{ $stat['question'] }}
                                </td>
                                <td data-label="Correct Responses" style="text-align: center; font-weight: 700; color: var(--black);">
                                    {{ $stat['correct'] }} / {{ $stat['attempts'] }}
                                </td>
                                <td data-label="Accuracy">
                                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.5rem; width: 100%;">
                                        <span style="font-weight: 700; font-size: 0.8rem; width: 40px; color: var(--black);">{{ $stat['accuracy'] }}%</span>
                                        <div style="flex: 1; max-width: 100px; height: 6px; background: var(--g100); border-radius: 3px; overflow: hidden; border: 1px solid var(--g200);">
                                            <div style="width: {{ $stat['accuracy'] }}%; height: 100%; background: var(--accent);"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Student Leaderboard / Scores List -->
        <div class="card" style="padding: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.25rem;">
                <h3 style="font-size: 1.1rem; font-weight: 800; letter-spacing: -0.01em; color: var(--black); margin: 0;">Student Submissions Rank</h3>
                
                <form action="{{ route('teacher.results.quiz', $quiz->id) }}" method="GET" style="display: flex; gap: 0.5rem; flex-wrap: wrap; align-items: center;">
                    <input type="text" name="search" value="{{ request('search') }}" class="input" placeholder="Search student name..." style="width: 200px; padding: 0.45rem 0.75rem; font-size: 0.8rem;">
                    
                    <select name="score_range" class="input" style="width: 150px; padding: 0.45rem 0.75rem; font-size: 0.8rem; cursor: pointer;">
                        <option value="">All Marks</option>
                        <option value="below_35" {{ request('score_range') === 'below_35' ? 'selected' : '' }}>Below 35%</option>
                        <option value="above_35" {{ request('score_range') === 'above_35' ? 'selected' : '' }}>&ge; 35%</option>
                        <option value="above_50" {{ request('score_range') === 'above_50' ? 'selected' : '' }}>&ge; 50%</option>
                        <option value="above_65" {{ request('score_range') === 'above_65' ? 'selected' : '' }}>&ge; 65%</option>
                        <option value="above_75" {{ request('score_range') === 'above_75' ? 'selected' : '' }}>&ge; 75%</option>
                    </select>

                    <button type="submit" class="btn btn-primary" style="padding: 0.45rem 0.85rem; font-size: 0.8rem;">
                        Filter
                    </button>
                    @if(request()->anyFilled(['search', 'score_range']))
                        <a href="{{ route('teacher.results.quiz', $quiz->id) }}" class="btn btn-secondary" style="padding: 0.45rem 0.85rem; font-size: 0.8rem;">
                            Reset
                        </a>
                    @endif
                </form>
            </div>
            
            @if($submissions->isEmpty())
                <p style="font-size: 0.85rem; color: var(--g500); text-align: center; padding: 2rem;">
                    {{ request()->anyFilled(['search', 'score_range']) ? 'No submissions match your filters.' : 'No student attempts yet.' }}
                </p>
            @else
                <div class="table-container table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 60px;">Rank</th>
                                <th>Student Name</th>
                                <th>Correct MCQs</th>
                                <th>Score</th>
                                <th style="text-align: right;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($submissions as $rankIdx => $sub)
                                <tr>
                                    <td data-label="Rank" style="font-weight: 700; color: var(--g500); font-size: 0.95rem;">#{{ $rankIdx + 1 }}</td>
                                    <td data-label="Student Name" style="font-weight: 700; color: var(--black);">{{ $sub->student->full_name }}</td>
                                    <td data-label="Correct MCQs">{{ $sub->correct_answers }} / {{ $sub->total_questions }}</td>
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
                                                Review Responses →
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

</div>
@endsection
