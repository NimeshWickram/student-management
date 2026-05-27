@extends('layouts.layout')

@section('title', 'Add MCQ Quiz — EduManager')
@section('page-title', 'Add MCQ Quiz')
@section('breadcrumb')<a href="{{ route('dashboard') }}">Home</a> / <a href="{{ route('quizzes.index') }}">Quizzes</a> / Add MCQ @endsection

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
    input[type="file"]::file-selector-button {
        background-color: #0a0a0a;
        color: #ffffff;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        border: 2px solid #0a0a0a;
        padding: 0.5rem 1rem;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        border-radius: 4px;
        margin-right: 1rem;
    }
    input[type="file"]::file-selector-button:hover {
        background-color: #ffffff;
        color: #0a0a0a;
    }
</style>
@endsection

@section('content')
<div class="max-w-4xl mx-auto" id="quiz-app" x-data="{ 
    quizType: '{{ old('quiz_type', 'manual_mcq') }}',
    questions: [
        { question: '', options: ['', '', '', ''], correct_option: 0 }
    ],
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
}" @parse-completed.window="questions = $event.detail.questions; quizType = 'manual_mcq'">
    <!-- Header -->
    <div class="flex items-center justify-between pb-4 mb-6 border-b border-gray-200">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-black">Create MCQ Quiz</h1>
            <p class="text-sm text-gray-500 mt-1">Configure an interactive MCQ assessment or upload a question sheet PDF.</p>
        </div>
        <a href="{{ route('quizzes.index') }}" class="inline-flex items-center justify-center px-4 py-2 class-btn text-xs font-bold uppercase tracking-wider text-black bg-white border-2 border-black rounded hover:bg-black hover:text-white transition duration-200 ease-in-out">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Back to List
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white border-2 border-black rounded shadow-lg overflow-hidden">
        <form action="{{ route('quizzes.store') }}" method="POST" enctype="multipart/form-data" class="p-8 flex flex-col gap-6" id="create-quiz-form">
            @csrf
            
            <!-- Hidden field to serialize dynamic questions array -->
            <input type="hidden" name="manual_content" :value="JSON.stringify(questions)">
            <input type="hidden" name="quiz_type" :value="quizType">

            <!-- Teacher, Subject, and Grade Dropdowns -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Teacher -->
                <div class="flex flex-col gap-2">
                    <label for="teacher_id" class="text-xs font-bold uppercase tracking-wider text-black">Assign Teacher <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="teacher_id" id="teacher_id" required class="w-full px-3.5 py-3 text-sm border-2 border-gray-200 rounded focus:border-black focus:outline-none transition duration-200 bg-white appearance-none font-medium text-gray-800">
                            <option value="" disabled selected>Select a teacher...</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
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
                        <select name="subject_id" id="subject_id" required class="w-full px-3.5 py-3 text-sm border-2 border-gray-200 rounded focus:border-black focus:outline-none transition duration-200 bg-white appearance-none font-medium text-gray-800">
                            <option value="" disabled selected>Select a subject...</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
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
                        <select name="grade" id="grade" required class="w-full px-3.5 py-3 text-sm border-2 border-gray-200 rounded focus:border-black focus:outline-none transition duration-200 bg-white appearance-none font-medium text-gray-800">
                             <option value="" disabled selected>Select a grade...</option>
                             @for($i = 1; $i <= 11; $i++)
                                 <option value="Grade {{ $i }}" {{ old('grade') == "Grade $i" ? 'selected' : '' }}>Grade {{ $i }}</option>
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
                <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="e.g. Physics MCQ Assessment I" required class="w-full px-4 py-3 text-sm border-2 border-gray-200 rounded focus:border-black focus:outline-none transition duration-200 bg-white font-medium text-gray-800">
            </div>

            <!-- Quiz Type Selection (Toggle Buttons) -->
            <div class="flex flex-col gap-2">
                <label class="text-xs font-bold uppercase tracking-wider text-black mb-1">Creation Method <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 gap-2 p-1.5 border-2 border-black rounded bg-gray-50">
                    <button type="button" 
                            @click="quizType = 'manual_mcq'" 
                            :class="quizType === 'manual_mcq' ? 'bg-black text-white' : 'bg-transparent text-black hover:bg-gray-200'"
                            class="py-3 text-xs font-bold uppercase tracking-wider rounded transition duration-150 ease-in-out">
                        Option 1: MCQ Builder
                    </button>
                    <button type="button" 
                            @click="quizType = 'pdf_mcq'" 
                            :class="quizType === 'pdf_mcq' ? 'bg-black text-white' : 'bg-transparent text-black hover:bg-gray-200'"
                            class="py-3 text-xs font-bold uppercase tracking-wider rounded transition duration-150 ease-in-out">
                        Option 2: Upload MCQ PDF
                    </button>
                </div>
            </div>

            <!-- Option 1: Dynamic MCQ Builder -->
            <div class="border-t-2 border-black pt-6" x-show="quizType === 'manual_mcq'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
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
                                <input type="text" :id="`q-${index}`" x-model="q.question" placeholder="e.g. Which of the following is a unit of power?" :required="quizType === 'manual_mcq'" class="w-full px-4 py-2.5 text-sm border-2 border-gray-200 rounded focus:border-black focus:outline-none transition duration-200 bg-white">
                            </div>

                            <!-- Options Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <template x-for="(opt, optIndex) in q.options" :key="optIndex">
                                    <div class="flex flex-col gap-1.5">
                                        <label :for="`q-${index}-opt-${optIndex}`" class="text-[10px] font-bold uppercase tracking-wider text-gray-500" x-text="`Option ${String.fromCharCode(65 + optIndex)} *`"></label>
                                        <div class="relative">
                                            <input type="text" :id="`q-${index}-opt-${optIndex}`" x-model="q.options[optIndex]" :placeholder="`Enter Option ${String.fromCharCode(65 + optIndex)}`" :required="quizType === 'manual_mcq'" class="w-full px-4 py-2.5 text-sm border-2 border-gray-200 rounded focus:border-black focus:outline-none transition duration-200 bg-white">
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

            <!-- Option 2: PDF file upload -->
            <div class="flex flex-col gap-2 border-t-2 border-black pt-6" x-show="quizType === 'pdf_mcq'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
                <label for="pdf_file" class="text-xs font-bold uppercase tracking-wider text-black">Upload MCQ PDF File <span class="text-red-500">*</span></label>
                <div class="border-2 border-dashed border-gray-300 rounded p-6 bg-gray-50 flex flex-col items-center justify-center text-center">
                    <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <input type="file" name="pdf_file" id="pdf_file" accept=".pdf" class="hidden" :required="quizType === 'pdf_mcq'" onchange="updateFileName(this)">
                    <button type="button" onclick="document.getElementById('pdf_file').click()" class="inline-flex items-center justify-center px-4 py-2 border-2 border-black text-xs font-bold uppercase tracking-wider bg-black text-white hover:bg-white hover:text-black transition duration-150 mb-2">
                        Select PDF
                    </button>
                    <p id="file-name-display" class="text-xs text-gray-500 mt-2 font-medium">Accepts only .PDF documents (max 10MB)</p>
                </div>
            </div>

            <!-- Submit Button Area -->
            <div class="pt-6 border-t-2 border-black flex justify-end gap-3">
                <button type="reset" class="px-6 py-3 border-2 border-gray-200 text-xs font-bold uppercase tracking-wider text-gray-600 rounded hover:border-gray-400 hover:text-black transition duration-150">
                    Reset Form
                </button>
                <button type="submit" class="px-8 py-3 border-2 border-black text-xs font-bold uppercase tracking-wider bg-black text-white hover:bg-white hover:text-black transition duration-150">
                    Publish MCQ Quiz
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function updateFileName(input) {
        const display = document.getElementById('file-name-display');
        if (input.files && input.files[0]) {
            const file = input.files[0];
            if (file.type !== 'application/pdf') {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid File Format',
                    text: 'Strictly accepts only PDF files.',
                    confirmButtonColor: '#0a0a0a'
                });
                input.value = '';
                display.innerHTML = 'Accepts only .PDF documents (max 10MB)';
                display.className = 'text-xs text-gray-500 mt-2 font-medium';
                return;
            }
            if (file.size > 10 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    text: 'The selected PDF exceeds the 10MB limit.',
                    confirmButtonColor: '#0a0a0a'
                });
                input.value = '';
                display.innerHTML = 'Accepts only .PDF documents (max 10MB)';
                display.className = 'text-xs text-gray-500 mt-2 font-medium';
                return;
            }

            display.innerHTML = `Selected File: <strong class="text-black font-bold font-mono">${file.name}</strong> (${(file.size / (1024 * 1024)).toFixed(2)} MB)`;
            display.className = 'text-xs text-green-700 mt-2 font-medium bg-green-50 border border-green-200 px-3 py-1 rounded';

            // Automatic MCQ Extraction & Preview
            Swal.fire({
                title: 'Parsing MCQ PDF...',
                text: 'Extracting questions and options. Please wait.',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: function() {
                    Swal.showLoading();
                }
            });

            const formData = new FormData();
            formData.append('pdf_file', file);
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route("quizzes.parse-pdf") }}', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw new Error(err.error || 'Failed to parse PDF.'); });
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.questions) {
                    // Dispatch custom window event to Alpine container to populate questions
                    const event = new CustomEvent('parse-completed', {
                        detail: { questions: data.questions }
                    });
                    window.dispatchEvent(event);

                    Swal.fire({
                        icon: 'success',
                        title: 'Extraction Complete!',
                        text: 'Successfully extracted ' + data.questions.length + ' questions from the PDF. You can now preview and edit them below.',
                        confirmButtonColor: '#0a0a0a'
                    });
                } else {
                    throw new Error(data.error || 'Failed to parse PDF.');
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Parsing Failed',
                    text: error.message || 'Make sure the PDF contains Q1. [Question] style format.',
                    confirmButtonColor: '#0a0a0a'
                });
                input.value = '';
                display.innerHTML = 'Accepts only .PDF documents (max 10MB)';
                display.className = 'text-xs text-gray-500 mt-2 font-medium';
            });
        } else {
            display.innerHTML = 'Accepts only .PDF documents (max 10MB)';
            display.className = 'text-xs text-gray-500 mt-2 font-medium';
        }
    }

    // Submit confirmations
    document.getElementById('create-quiz-form').addEventListener('submit', function(e) {
        e.preventDefault();
        var f = this;
        Swal.fire({
            title: 'Publish MCQ Quiz?',
            text: 'Are you sure you want to add this MCQ quiz to the system?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0a0a0a',
            confirmButtonText: 'Yes, publish!'
        }).then(function(r) {
            if (r.isConfirmed) {
                Swal.fire({
                    title: 'Publishing...',
                    text: 'Please wait while the MCQ quiz is being saved.',
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
