@extends('layouts.layout')

@section('title', 'Edit MCQ Quiz — CodeXpress')
@section('page-title', 'Edit MCQ Quiz')
@section('breadcrumb')<a href="{{ route('dashboard') }}">Home</a> / <a href="{{ route('quizzes.index') }}">Quizzes</a> / Edit Quiz @endsection

@section('styles')
<script src="https://cdn.tailwindcss.com"></script>
<!-- Load Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    black: '#0a0a0a',
                    white: '#ffffff',
                    gray: {
                        50: '#fafafa',
                        100: '#f5f5f5',
                        200: '#e5e5e5',
                        300: '#d4d4d4',
                        400: '#a3a3a3',
                        500: '#737373',
                        600: '#525252',
                        700: '#404040',
                        800: '#262626',
                        900: '#171717',
                    }
                }
            }
        }
    }
</script>
<style>
    /* Prevent Tailwind from interfering with standard layout sidebar */
    .sidebar, .main-wrapper, .top-bar {
        font-family: 'Inter', system-ui, sans-serif !important;
    }
</style>
@endsection

@section('content')
<div class="max-w-4xl mx-auto" x-data="{ 
    questions: {!! $quiz->manual_content ?: '[{ question: \'\', options: [\'\', \'\', \'\', \'\'], correct_option: 0 }]' !!},
    addQuestion() {
        this.questions.push({ question: '', options: ['', '', '', ''], correct_option: 0 });
    },
    removeQuestion(index) {
        if (this.questions.length > 1) {
            this.questions.splice(index, 1);
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Minimum Limit Reached',
                text: 'An interactive quiz must contain at least one question.',
                confirmButtonColor: '#0a0a0a'
            });
        }
    }
}">
    <!-- Header -->
    <div class="flex items-center justify-between pb-4 mb-6 border-b border-gray-200">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-black">Edit MCQ Quiz</h1>
            <p class="text-sm text-gray-500 mt-1">Modify the details, questions, and options of this MCQ quiz.</p>
        </div>
        <a href="{{ route('quizzes.index') }}" class="inline-flex items-center justify-center px-4 py-2 class-btn text-xs font-bold uppercase tracking-wider text-black bg-white border-2 border-black rounded hover:bg-black hover:text-white transition duration-200 ease-in-out">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Cancel
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white border-2 border-black rounded shadow-lg overflow-hidden">
        <form action="{{ route('quizzes.update', $quiz) }}" method="POST" class="p-8 flex flex-col gap-6" id="edit-quiz-form">
            @csrf
            @method('PUT')
            
            <!-- Hidden field to serialize dynamic questions array -->
            <input type="hidden" name="manual_content" :value="JSON.stringify(questions)">

            <!-- Teacher, Subject, and Grade Dropdowns -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Teacher -->
                <div class="flex flex-col gap-2">
                    <label for="teacher_id" class="text-xs font-bold uppercase tracking-wider text-black">Assign Teacher <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="teacher_id" id="teacher_id" required class="w-full px-3.5 py-3 text-sm border-2 border-gray-200 rounded focus:border-black focus:outline-none transition duration-200 bg-white appearance-none font-medium text-gray-800 font-bold uppercase tracking-wider">
                            <option value="" disabled>Select a teacher...</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ $quiz->teacher_id == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->first_name }} {{ $teacher->last_name }} ({{ $teacher->subject }})
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none border-l-2 border-gray-200">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>
                    </div>
                </div>

                <!-- Subject -->
                <div class="flex flex-col gap-2">
                    <label for="subject_id" class="text-xs font-bold uppercase tracking-wider text-black">Assign Subject <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="subject_id" id="subject_id" required class="w-full px-3.5 py-3 text-sm border-2 border-gray-200 rounded focus:border-black focus:outline-none transition duration-200 bg-white appearance-none font-medium text-gray-800 font-bold uppercase tracking-wider">
                            <option value="" disabled>Select a subject...</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ $quiz->subject_id == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }} ({{ $subject->code }})
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none border-l-2 border-gray-200">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>
                    </div>
                </div>

                <!-- Grade Scope -->
                <div class="flex flex-col gap-2">
                    <label for="grade" class="text-xs font-bold uppercase tracking-wider text-black">Target Grade <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="grade" id="grade" required class="w-full px-3.5 py-3 text-sm border-2 border-gray-200 rounded focus:border-black focus:outline-none transition duration-200 bg-white appearance-none font-medium text-gray-800 font-bold uppercase tracking-wider">
                            <option value="" disabled>Select a grade...</option>
                            @for($i = 1; $i <= 11; $i++)
                                <option value="Grade {{ $i }}" {{ $quiz->grade == "Grade $i" ? 'selected' : '' }}>Grade {{ $i }}</option>
                            @endfor
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none border-l-2 border-gray-200">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Title -->
            <div class="flex flex-col gap-2">
                <label for="title" class="text-xs font-bold uppercase tracking-wider text-black">Quiz Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title', $quiz->title) }}" placeholder="e.g. Physics MCQ Assessment I" required class="w-full px-4 py-3 text-sm border-2 border-gray-200 rounded focus:border-black focus:outline-none transition duration-200 bg-white font-medium text-gray-800">
            </div>

            <!-- Dynamic MCQ Builder -->
            <div class="border-t-2 border-black pt-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-black">Questions & Options</h2>
                    <button type="button" @click="addQuestion()" class="inline-flex items-center px-4 py-2 text-xs font-bold uppercase border-2 border-black bg-black text-white hover:bg-white hover:text-black transition duration-150">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Add Question
                    </button>
                </div>

                <div class="flex flex-col gap-6">
                    <!-- Loop over questions array -->
                    <template x-for="(q, index) in questions" :key="index">
                        <div class="border-2 border-black p-6 rounded bg-gray-50 flex flex-col gap-4 relative">
                            <!-- Header / Action Button -->
                            <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                                <span class="text-xs font-black uppercase tracking-wider text-black bg-gray-200 px-2.5 py-1 rounded" x-text="`Question #${index + 1}`"></span>
                                <button type="button" @click="removeQuestion(index)" class="inline-flex items-center px-2.5 py-1 text-xs font-bold uppercase text-red-600 border border-red-200 bg-red-50 hover:bg-red-600 hover:text-white transition duration-150 rounded">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    Remove
                                </button>
                            </div>

                            <!-- Question Text -->
                            <div class="flex flex-col gap-1.5">
                                <label :for="`q-${index}`" class="text-xs font-bold uppercase tracking-wider text-gray-700">Question Content <span class="text-red-500">*</span></label>
                                <input type="text" :id="`q-${index}`" x-model="q.question" placeholder="e.g. Which of the following is a unit of power?" required class="w-full px-4 py-2.5 text-sm border-2 border-gray-200 rounded focus:border-black focus:outline-none transition duration-200 bg-white">
                            </div>

                            <!-- Options Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <template x-for="(opt, optIndex) in q.options" :key="optIndex">
                                    <div class="flex flex-col gap-1.5">
                                        <label :for="`q-${index}-opt-${optIndex}`" class="text-[10px] font-bold uppercase tracking-wider text-gray-500" x-text="`Option ${String.fromCharCode(65 + optIndex)} *`"></label>
                                        <div class="relative">
                                            <input type="text" :id="`q-${index}-opt-${optIndex}`" x-model="q.options[optIndex]" :placeholder="`Enter Option ${String.fromCharCode(65 + optIndex)}`" required class="w-full px-4 py-2.5 text-sm border-2 border-gray-200 rounded focus:border-black focus:outline-none transition duration-200 bg-white">
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Correct Option Dropdown -->
                            <div class="flex flex-col gap-1.5 max-w-xs mt-2">
                                <label :for="`q-${index}-correct`" class="text-xs font-bold uppercase tracking-wider text-black">Select Correct Answer <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <select :id="`q-${index}-correct`" x-model.number="q.correct_option" class="w-full px-3.5 py-2.5 text-sm border-2 border-black rounded focus:outline-none transition duration-200 bg-white appearance-none font-bold uppercase tracking-wider text-black">
                                        <template x-for="(opt, optIndex) in q.options" :key="optIndex">
                                            <option :value="optIndex" x-text="`Option ${String.fromCharCode(65 + optIndex)}`"></option>
                                        </template>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none border-l-2 border-black">
                                        <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Submit Button Area -->
            <div class="pt-6 border-t-2 border-black flex justify-end gap-3">
                <a href="{{ route('quizzes.index') }}" class="px-6 py-3 border-2 border-gray-200 text-xs font-bold uppercase tracking-wider text-gray-600 rounded hover:border-gray-400 hover:text-black transition duration-150 flex items-center justify-center">
                    Cancel
                </a>
                <button type="submit" class="px-8 py-3 border-2 border-black text-xs font-bold uppercase tracking-wider bg-black text-white hover:bg-white hover:text-black transition duration-150">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Submit confirmations
    document.getElementById('edit-quiz-form').addEventListener('submit', function(e) {
        e.preventDefault();
        var f = this;
        Swal.fire({
            title: 'Save MCQ Quiz changes?',
            text: 'Are you sure you want to apply these updates to this quiz?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0a0a0a',
            confirmButtonText: 'Yes, save!'
        }).then(function(r) {
            if (r.isConfirmed) {
                Swal.fire({
                    title: 'Saving updates...',
                    text: 'Please wait.',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: function() {
                        Swal.showLoading();
                    }
                });
                f.submit();
            }
        });
    });
</script>
@endsection
