@extends('student.layouts.layout')

@section('title', 'Quiz Results: ' . $submission->quiz->title . ' — EduManager')
@section('page-title', 'Assessment Results')
@section('breadcrumb')
<a href="{{ route('student.dashboard') }}">Dashboard</a> / Quiz Results
@endsection

@section('styles')
<!-- Load Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>
<!-- Canvas Confetti CDN -->
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<style>
    /* Reset layout properties to prevent Tailwind conflicts */
    .sidebar, .main-wrapper, .top-bar, .page-footer {
        font-family: 'Inter', system-ui, sans-serif !important;
    }
</style>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Results Score Banner Card -->
    @php
        $passed = $submission->score >= 50;
        $scoreColor = $passed ? 'text-emerald-600' : 'text-rose-600';
        $borderColor = $passed ? 'border-emerald-200' : 'border-rose-200';
        $bgColor = $passed ? 'bg-emerald-50/50' : 'bg-rose-50/50';
    @endphp

    <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-8 text-center mb-8 flex flex-col items-center justify-center relative overflow-hidden">
        <!-- Accent decorative ring -->
        <div class="absolute -top-12 -left-12 w-32 h-32 rounded-full opacity-10 bg-indigo-600"></div>
        <div class="absolute -bottom-12 -right-12 w-32 h-32 rounded-full opacity-10 bg-indigo-600"></div>

        <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 border border-indigo-100 px-3.5 py-1.5 rounded-full mb-3 shadow-sm">
            Assessment Completed
        </span>
        
        <h2 class="text-xl font-extrabold text-black mb-1">{{ $submission->quiz->title }}</h2>
        <p class="text-xs text-gray-500 mb-6">Subject: {{ $submission->quiz->subject->name }} | Lec: {{ $submission->quiz->teacher->first_name }} {{ $submission->quiz->teacher->last_name }}</p>

        <!-- Big Score Badge -->
        <div class="w-32 h-32 rounded-full flex flex-col items-center justify-center border-4 shadow-md bg-gray-50/50 mb-6 {{ $borderColor }}">
            <span class="text-3xl font-black {{ $scoreColor }}">{{ $submission->score }}%</span>
            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mt-0.5">Final Grade</span>
        </div>

        <div class="grid grid-cols-2 gap-4 max-w-sm w-full bg-gray-50 border border-gray-100 rounded-xl p-4 mb-6">
            <div class="text-center border-r border-gray-200">
                <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider block">Correct MCQs</span>
                <span class="text-lg font-black text-gray-800">{{ $submission->correct_answers }} / {{ $submission->total_questions }}</span>
            </div>
            <div class="text-center">
                <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider block">Status</span>
                @if($passed)
                    <span class="text-sm font-extrabold text-emerald-600 uppercase tracking-wider">Passed 🎉</span>
                @else
                    <span class="text-sm font-extrabold text-rose-600 uppercase tracking-wider">Re-attempt Needed ⏳</span>
                @endif
            </div>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('student.dashboard') }}" class="inline-flex items-center gap-2 px-6 py-2.5 text-xs font-bold uppercase tracking-wider rounded-lg bg-black border border-black text-white hover:bg-gray-800 transition duration-150 shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                Portal Dashboard
            </a>
            <a href="{{ route('student.quiz.take', $submission->quiz_id) }}" class="inline-flex items-center gap-2 px-6 py-2.5 text-xs font-bold uppercase tracking-wider border border-gray-300 rounded-lg bg-white text-gray-700 hover:bg-gray-50 transition duration-150 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                Retake Assessment
            </a>
        </div>
    </div>

    <!-- Interactive Review List -->
    <h3 class="text-base font-extrabold text-black tracking-tight mb-4 flex items-center gap-2">
        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Assessment Review Matrix
    </h3>

    <div class="flex flex-col gap-5">
        @php
            $detailedAnswers = $submission->answers;
        @endphp

        @foreach($questions as $index => $q)
            @php
                $detail = $detailedAnswers[$index] ?? null;
                $studentOption = $detail['selected_option'] ?? null;
                $correctOption = $detail['correct_option'] ?? (int)$q['correct_option'];
                $isCorrect = $studentOption !== null && $studentOption === $correctOption;
                
                $itemBorderColor = $isCorrect ? 'border-emerald-200' : 'border-rose-200';
                $itemBgColor = $isCorrect ? 'bg-emerald-50/20' : 'bg-rose-50/20';
                $itemHeaderBadge = $isCorrect 
                    ? 'text-emerald-700 bg-emerald-100 border-emerald-200' 
                    : 'text-rose-700 bg-rose-100 border-rose-200';
            @endphp

            <div class="border rounded-2xl p-6 shadow-sm bg-white {{ $itemBorderColor }} {{ $itemBgColor }} flex flex-col gap-4">
                <!-- Header -->
                <div class="flex justify-between items-center pb-2 border-b border-dashed {{ $isCorrect ? 'border-emerald-100' : 'border-rose-100' }}">
                    <span class="text-xs font-black uppercase tracking-wider text-gray-500">Question #{{ $index + 1 }}</span>
                    <span class="text-[10px] font-bold uppercase tracking-wider border rounded px-2 py-0.5 {{ $itemHeaderBadge }}">
                        @if($isCorrect)
                            ✓ Correct Response
                        @else
                            ✗ Incorrect Response
                        @endif
                    </span>
                </div>

                <!-- Question Text -->
                <h4 class="text-sm font-bold text-gray-900 leading-snug">{{ $q['question'] }}</h4>

                <!-- Options -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($q['options'] as $optIndex => $opt)
                        @php
                            $optClass = 'border-gray-200 bg-white text-gray-700';
                            $optIcon = '';
                            
                            // Highlight correct option in green
                            if ($optIndex === $correctOption) {
                                $optClass = 'border-emerald-500 bg-emerald-100 text-emerald-800 font-semibold';
                                $optIcon = '✓';
                            }
                            
                            // Highlight student's incorrect option in red
                            if ($optIndex === $studentOption && !$isCorrect) {
                                $optClass = 'border-rose-500 bg-rose-100 text-rose-800 font-semibold';
                                $optIcon = '✗';
                            }
                            
                            // Highlight student's correct option in green
                            if ($optIndex === $studentOption && $isCorrect) {
                                $optClass = 'border-emerald-600 bg-emerald-100 text-emerald-800 font-semibold ring-2 ring-emerald-500/20';
                                $optIcon = '✓';
                            }
                        @endphp
                        
                        <div class="p-3 border rounded-xl text-xs flex items-center justify-between {{ $optClass }}">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-md bg-gray-50/50 border flex items-center justify-center font-bold text-[10px]" style="min-width: 1.5rem;">
                                    {{ chr(65 + $optIndex) }}
                                </span>
                                <span>{{ $opt }}</span>
                            </div>
                            @if($optIcon !== '')
                                <span class="font-extrabold text-sm ml-2">{{ $optIcon }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Confetti burst for high achievement scores
        @if($submission->score >= 50)
            var duration = 2.5 * 1000;
            var end = Date.now() + duration;

            (function frame() {
                confetti({
                    particleCount: 4,
                    angle: 60,
                    spread: 55,
                    origin: { x: 0 },
                    colors: ['#4f46e5', '#10b981', '#f59e0b']
                });
                confetti({
                    particleCount: 4,
                    angle: 120,
                    spread: 55,
                    origin: { x: 1 },
                    colors: ['#4f46e5', '#10b981', '#f59e0b']
                });

                if (Date.now() < end) {
                    requestAnimationFrame(frame);
                }
            }());
        @endif
    });
</script>
@endsection
