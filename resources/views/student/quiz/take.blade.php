@extends('student.layouts.layout')

@section('title', 'Taking Quiz: ' . $quiz->title . ' — CodeXpress')
@section('page-title', 'Assessment Session')
@section('breadcrumb')
<a href="{{ route('student.dashboard') }}">Dashboard</a> / Take Quiz
@endsection

@section('styles')
<!-- Load Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>
<!-- Load Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    black: '#09090b',
                    white: '#ffffff',
                    indigo: {
                        50: '#eef2ff',
                        100: '#e0e7ff',
                        200: '#c7d2fe',
                        300: '#a5b4fc',
                        400: '#818cf8',
                        500: '#6366f1',
                        600: '#4f46e5',
                        700: '#4338ca',
                        800: '#3730a3',
                        900: '#312e81',
                    },
                    gray: {
                        50: '#fafafa',
                        100: '#f4f4f5',
                        200: '#e4e4e7',
                        300: '#d4d4d8',
                        400: '#a1a1aa',
                        500: '#71717a',
                        600: '#52525b',
                        700: '#3f3f46',
                        800: '#27272a',
                        900: '#18181b',
                    }
                }
            }
        }
    }
</script>
<style>
    /* Reset layout wrapper properties to guarantee layout sidebar remains unaffected */
    .sidebar, .main-wrapper, .top-bar, .page-footer {
        font-family: 'Inter', system-ui, sans-serif !important;
    }
    .custom-choice-card {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
</style>
@endsection

@section('content')
<div class="max-w-3xl mx-auto" x-data="{
    currentIndex: 0,
    questions: {{ json_encode($questions) }},
    answers: {},
    elapsedSeconds: 0,
    timerInterval: null,
    
    init() {
        this.startTimer();
    },
    
    startTimer() {
        this.timerInterval = setInterval(() => {
            this.elapsedSeconds++;
        }, 1000);
    },
    
    stopTimer() {
        if (this.timerInterval) {
            clearInterval(this.timerInterval);
        }
    },
    
    get formattedTime() {
        const mins = Math.floor(this.elapsedSeconds / 60);
        const secs = this.elapsedSeconds % 60;
        return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    },
    
    selectOption(optIndex) {
        this.answers[this.currentIndex] = optIndex;
    },
    
    nextQuestion() {
        if (this.currentIndex < this.questions.length - 1) {
            this.currentIndex++;
        }
    },
    
    prevQuestion() {
        if (this.currentIndex > 0) {
            this.currentIndex--;
        }
    },
    
    get progressPercent() {
        const answeredCount = Object.keys(this.answers).length;
        return Math.round((answeredCount / this.questions.length) * 100);
    },
    
    confirmSubmission() {
        const unansweredCount = this.questions.length - Object.keys(this.answers).length;
        let warningText = 'All questions have been answered.';
        if (unansweredCount > 0) {
            warningText = `You have ${unansweredCount} unanswered question(s). Are you sure you want to proceed?`;
        }
        
        Swal.fire({
            title: 'Submit Assessment?',
            text: warningText,
            icon: unansweredCount > 0 ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#71717a',
            confirmButtonText: 'Yes, Submit Answers'
        }).then((result) => {
            if (result.isConfirmed) {
                this.stopTimer();
                Swal.fire({
                    title: 'Evaluating Quiz...',
                    text: 'Analyzing response matrices. Please wait.',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit form programmatically
                document.getElementById('quiz-submission-form').submit();
            }
        });
    }
}">

    <!-- Quiz Session Header -->
    <div class="flex items-center justify-between pb-4 mb-6 border-b border-gray-200">
        <div>
            <h1 class="text-xl font-extrabold tracking-tight text-black">{{ $quiz->title }}</h1>
            <p class="text-xs text-gray-500 mt-1">Subject: <span class="font-bold text-gray-700">{{ $quiz->subject->name }}</span> | Lec: {{ $quiz->teacher->first_name }} {{ $quiz->teacher->last_name }}</p>
        </div>
        
        <!-- Live Stopwatch Timer -->
        <div class="flex items-center gap-2 bg-indigo-50 border border-indigo-200 px-3.5 py-1.5 rounded-full text-indigo-700 font-bold font-mono text-sm shadow-sm">
            <svg class="w-4 h-4 animate-spin" style="animation-duration: 4s;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span x-text="formattedTime">00:00</span>
        </div>
    </div>

    <!-- Main Wizard Card -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden flex flex-col">
        <!-- Progress Bar Indicator -->
        <div class="bg-gray-100 h-1.5 w-full relative">
            <div class="bg-indigo-600 h-1.5 transition-all duration-300 ease-out" :style="`width: ${progressPercent}%`"></div>
        </div>

        <div class="p-8 flex flex-col min-h-[380px]">
            <!-- Question Tracker & Indicator -->
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 border border-indigo-100 px-2.5 py-1 rounded" x-text="`Question ${currentIndex + 1} of ${questions.length}`"></span>
                <span class="text-xs font-bold text-gray-400" x-text="`${progressPercent}% Answered`"></span>
            </div>

            <!-- Animated Question Container -->
            <div class="flex-1 flex flex-col justify-center">
                <!-- Question Title -->
                <h3 class="text-lg font-bold text-black leading-snug mb-6" x-text="questions[currentIndex].question"></h3>

                <!-- MCQ Options List -->
                <div class="flex flex-col gap-3">
                    <template x-for="(opt, optIndex) in questions[currentIndex].options" :key="optIndex">
                        <button type="button" 
                                @click="selectOption(optIndex)"
                                class="custom-choice-card w-full text-left p-4 rounded-xl border border-gray-200 bg-gray-50 flex items-center justify-between hover:border-indigo-300 hover:bg-indigo-50/50 cursor-pointer"
                                :class="answers[currentIndex] === optIndex ? '!border-indigo-600 !bg-indigo-50/80 ring-2 ring-indigo-500/20' : ''">
                            <div class="flex items-center gap-3">
                                <!-- Option letter circle -->
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs uppercase border"
                                     :class="answers[currentIndex] === optIndex ? 'bg-indigo-600 border-indigo-600 text-white' : 'bg-white border-gray-300 text-gray-700'"
                                     x-text="String.fromCharCode(65 + optIndex)">
                                </div>
                                <span class="text-sm font-semibold text-gray-800" x-text="opt"></span>
                            </div>
                            
                            <!-- Selected radio dot -->
                            <div class="w-5 h-5 rounded-full border flex items-center justify-center"
                                 :class="answers[currentIndex] === optIndex ? 'border-indigo-600 bg-indigo-600 text-white' : 'border-gray-300 bg-white'">
                                <svg x-show="answers[currentIndex] === optIndex" class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            </div>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        <!-- Wizard Navigation Toolbar -->
        <div class="border-t border-gray-200 px-8 py-5 bg-gray-50 flex justify-between items-center">
            <!-- Prev Button -->
            <button type="button" 
                    @click="prevQuestion()"
                    :disabled="currentIndex === 0"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold uppercase tracking-wider border border-gray-300 rounded-lg bg-white text-gray-700 hover:bg-gray-100 disabled:opacity-40 disabled:pointer-events-none transition duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                Previous
            </button>

            <!-- Next / Submit Button -->
            <div>
                <button type="button" 
                        x-show="currentIndex < questions.length - 1"
                        @click="nextQuestion()"
                        class="inline-flex items-center gap-2 px-6 py-2.5 text-xs font-bold uppercase tracking-wider rounded-lg bg-indigo-600 border border-indigo-600 text-white hover:bg-indigo-700 transition duration-150 shadow-md">
                    Next Question
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                </button>

                <button type="button" 
                        x-show="currentIndex === questions.length - 1"
                        @click="confirmSubmission()"
                        class="inline-flex items-center gap-2 px-7 py-2.5 text-xs font-bold uppercase tracking-wider rounded-lg bg-emerald-600 border border-emerald-600 text-white hover:bg-emerald-700 transition duration-150 shadow-md">
                    Submit Quiz
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Hidden form to process answers securely via POST -->
    <form id="quiz-submission-form" action="{{ route('student.quiz.submit', $quiz->id) }}" method="POST" class="hidden">
        @csrf
        <template x-for="(val, key) in answers" :key="key">
            <input type="hidden" :name="`answers[${key}]`" :value="val">
        </template>
    </form>
</div>
@endsection
