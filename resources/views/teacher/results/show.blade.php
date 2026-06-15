@extends('teacher.layouts.layout')

@section('title', 'Review Submission: ' . $submission->student->full_name . ' — CodeXpress')
@section('page-title', 'Detailed Submission Review')

@section('content')
<div style="display: flex; flex-direction: column; gap: 2rem; max-width: 800px; margin: 0 auto;">

    <!-- Back Navigation -->
    <div>
        <a href="{{ route('teacher.results.index') }}" class="btn btn-secondary" style="font-size: 0.8rem; padding: 0.4rem 0.8rem;">
            ← Back to Submissions
        </a>
    </div>

    <!-- Header Details Card -->
    <div class="card" style="display: flex; flex-direction: column; gap: 1.5rem; position: relative; overflow: hidden;">
        
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
            <div>
                <span class="badge badge-outline" style="margin-bottom: 0.5rem;">Grade {{ $submission->quiz->grade ?? '—' }}</span>
                <h2 style="font-size: 1.5rem; font-weight: 800; letter-spacing: -0.02em; margin-bottom: 0.25rem; color: var(--black);">
                    {{ $submission->quiz->title }}
                </h2>
                <p style="font-size: 0.85rem; color: var(--g500);">
                    Subject: {{ $submission->quiz->subject->name ?? '—' }}
                </p>
            </div>
            
            <div style="text-align: right; min-width: 120px;">
                @php
                    $passed = $submission->score >= 50;
                    $scoreColor = $passed ? 'color: #047857;' : 'color: #b91c1c;';
                @endphp
                <div style="font-size: 3rem; font-weight: 900; letter-spacing: -0.03em; line-height: 1; {{ $scoreColor }}">
                    {{ $submission->score }}%
                </div>
                <div style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--g500); margin-top: 0.25rem;">
                    Final Score
                </div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; border-top: 1px solid var(--g200); padding-top: 1.25rem;">
            <div>
                <span class="label">Student Name</span>
                <div style="font-size: 0.95rem; font-weight: 700; color: var(--black); margin-top: 0.15rem;">
                    {{ $submission->student->full_name }}
                </div>
            </div>
            <div>
                <span class="label">Email Address</span>
                <div style="font-size: 0.85rem; color: var(--g600); margin-top: 0.15rem;">
                    {{ $submission->student->email }}
                </div>
            </div>
            <div>
                <span class="label">Accuracy Stats</span>
                <div style="font-size: 0.95rem; font-weight: 700; color: var(--black); margin-top: 0.15rem;">
                    {{ $submission->correct_answers }} / {{ $submission->total_questions }} MCQs Correct
                </div>
            </div>
            <div>
                <span class="label">Submission Date</span>
                <div style="font-size: 0.85rem; color: var(--g600); margin-top: 0.15rem;">
                    {{ $submission->created_at->format('M d, Y — h:i A') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Question Matrix Title -->
    <div>
        <h3 style="font-size: 1.1rem; font-weight: 800; letter-spacing: -0.01em; margin-bottom: 0.5rem; color: var(--black);">
            Response Matrix Breakdown
        </h3>
        <p style="font-size: 0.8rem; color: var(--g500);">
            Review each question, the student's selected choice, and the correct option.
        </p>
    </div>

    <!-- Questions Loop -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        @php
            $detailedAnswers = $submission->answers;
        @endphp

        @foreach($questions as $index => $q)
            @php
                $detail = $detailedAnswers[$index] ?? null;
                $studentOption = $detail['selected_option'] ?? null;
                $correctOption = $detail['correct_option'] ?? (int)$q['correct_option'];
                $isCorrect = $studentOption !== null && $studentOption === $correctOption;
                
                $itemBorderColor = $isCorrect ? 'border-color: #a7f3d0;' : 'border-color: #fca5a5;';
                $itemBg = $isCorrect ? 'background: #f0fdf4;' : 'background: #fdf2f2;';
                $badgeStyle = $isCorrect 
                    ? 'background: #10b981; color: var(--white); font-weight: 700;' 
                    : 'background: #ef4444; color: var(--white); font-weight: 700;';
            @endphp

            <div class="card" style="padding: 1.5rem; {{ $itemBorderColor }} {{ $itemBg }} display: flex; flex-direction: column; gap: 1.25rem;">
                
                <!-- Question Header -->
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px dashed rgba(0, 0, 0, 0.08); padding-bottom: 0.75rem;">
                    <span style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; color: var(--g500); letter-spacing: 0.05em;">
                        MCQ #{{ $index + 1 }}
                    </span>
                    <span class="badge" style="{{ $badgeStyle }}">
                        @if($isCorrect)
                            ✓ Correct Response
                        @else
                            ✗ Incorrect Response
                        @endif
                    </span>
                </div>

                <!-- Question Text -->
                <div style="font-size: 0.95rem; font-weight: 700; line-height: 1.5; color: var(--black);">
                    {{ $q['question'] }}
                </div>

                <!-- Options -->
                <div style="display: grid; grid-template-columns: 1fr; gap: 0.75rem;">
                    @foreach($q['options'] as $optIndex => $opt)
                        @php
                            $optBorder = 'border: 1px solid var(--g200);';
                            $optBg = 'background: var(--white);';
                            $optColor = 'color: var(--g700);';
                            $optIcon = '';
                            $badgeCircleBg = 'background: var(--g100); color: var(--g500);';
                            
                            // Correct Option Highlight (Green)
                            if ($optIndex === $correctOption) {
                                $optBorder = 'border: 1.5px solid #10b981;';
                                $optBg = 'background: #ecfdf5;';
                                $optColor = 'color: #065f46; font-weight: 600;';
                                $optIcon = '✓';
                                $badgeCircleBg = 'background: #10b981; color: var(--white);';
                            }
                            
                            // Student Incorrect Option Highlight (Red)
                            if ($optIndex === $studentOption && !$isCorrect) {
                                $optBorder = 'border: 1.5px solid #ef4444;';
                                $optBg = 'background: #fef2f2;';
                                $optColor = 'color: #991b1b; font-weight: 600;';
                                $optIcon = '✗';
                                $badgeCircleBg = 'background: #ef4444; color: var(--white);';
                            }
                        @endphp

                        <div style="padding: 0.75rem 1rem; border-radius: var(--radius-sm); font-size: 0.85rem; display: flex; align-items: center; justify-content: space-between; {{ $optBorder }} {{ $optBg }} {{ $optColor }}">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <span style="display: flex; align-items: center; justify-content: center; width: 22px; height: 22px; border-radius: 6px; font-size: 0.7rem; font-weight: 700; {{ $badgeCircleBg }}">
                                    {{ chr(65 + $optIndex) }}
                                </span>
                                <span>{{ $opt }}</span>
                            </div>
                            @if($optIcon !== '')
                                <span style="font-weight: 800; font-size: 0.95rem; margin-left: 0.5rem;">
                                    {{ $optIcon }}
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>

            </div>
        @endforeach
    </div>

</div>
@endsection
