@extends('layouts.layout')

@section('title', 'Quizzes — CodeXpress')
@section('page-title', 'Quizzes')
@section('breadcrumb')<a href="{{ route('dashboard') }}">Home</a> / Quizzes @endsection

@section('styles')
<script src="https://cdn.tailwindcss.com"></script>
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
<div class="flex flex-col gap-6">
    <!-- Header Area -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-black">Quizzes <span class="ml-2 inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-200 text-gray-700">{{ $quizzes->total() }}</span></h1>
            <p class="text-sm text-gray-500 mt-1">Manage and create MCQ quizzes using the online builder or PDF uploads.</p>
        </div>
        <a href="{{ route('quizzes.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 class-btn text-xs font-bold uppercase tracking-wider text-white bg-black border-2 border-black rounded hover:bg-white hover:text-black transition duration-200 ease-in-out">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add New MCQ Quiz
        </a>
    </div>

    <!-- Search / Filter -->
    <form action="{{ route('quizzes.index') }}" method="GET" class="relative">
        <div class="relative w-full">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search quizzes by title, teacher, or subject..." class="w-full pl-11 pr-24 py-3 text-sm border-2 border-gray-200 rounded focus:border-black focus:outline-none transition duration-200 bg-white">
            @if(request('search'))
                <a href="{{ route('quizzes.index') }}" class="absolute right-20 top-1/2 -translate-y-1/2 px-2.5 py-1 text-xs bg-gray-100 border border-gray-200 rounded hover:bg-gray-200 text-gray-700">Clear</a>
            @endif
            <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 px-4 py-1.5 text-xs font-bold uppercase bg-black text-white hover:bg-gray-800 rounded">Search</button>
        </div>
    </form>

    <!-- Quiz Table -->
    <div class="bg-white border-2 border-black rounded shadow-md overflow-hidden">
        @if($quizzes->count())
        <div class="table-responsive overflow-x-auto">
            <table class="min-w-full divide-y-2 divide-black">
                <thead class="bg-black text-white">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">#</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Quiz Title</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Teacher</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Subject</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Grade</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Quiz Type</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Total Questions</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Created Date</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($quizzes as $index => $quiz)
                    @php
                        $isManualMcq = in_array($quiz->quiz_type, ['manual_mcq', 'manual', 'mcq']);
                    @endphp
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td data-label="#" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">
                            {{ $quizzes->firstItem() + $index }}
                        </td>
                        <td data-label="Quiz Title" class="px-6 py-4 whitespace-nowrap text-sm font-bold text-black">
                            {{ $quiz->title }}
                        </td>
                        <td data-label="Teacher" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">
                            {{ $quiz->teacher ? $quiz->teacher->first_name . ' ' . $quiz->teacher->last_name : 'N/A' }}
                        </td>
                        <td data-label="Subject" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-gray-100 border border-gray-300 text-gray-800">
                                {{ $quiz->subject ? $quiz->subject->name : 'N/A' }}
                            </span>
                        </td>
                        <td data-label="Grade" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-black text-white">
                                {{ $quiz->grade ?? 'Grade 9' }}
                            </span>
                        </td>
                        <td data-label="Quiz Type" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            @if($isManualMcq)
                                @if($quiz->pdf_file_path)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-gray-200 text-black border border-gray-400">
                                        MCQ Builder (PDF)
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-gray-200 text-black border border-gray-400">
                                        MCQ Builder
                                    </span>
                                @endif
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-black text-white">
                                    MCQ PDF
                                </span>
                            @endif
                        </td>
                        <td data-label="Total Questions" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-bold">
                            @if($isManualMcq)
                                @php
                                    $questions = json_decode($quiz->manual_content, true);
                                    $totalQs = is_array($questions) ? count($questions) : 0;
                                @endphp
                                <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-black bg-black text-white">
                                    {{ $totalQs }} {{ $totalQs === 1 ? 'Question' : 'Questions' }}
                                </span>
                            @else
                                <span class="text-xs font-bold text-gray-500 uppercase italic">PDF Attachment</span>
                            @endif
                        </td>
                        <td data-label="Created Date" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">
                            {{ $quiz->created_at->format('d M Y') }}
                        </td>
                        <td data-label="Actions" class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex flex-wrap items-center gap-2 actions">
                                @if($isManualMcq)
                                    <button type="button" onclick="showQuizContent({{ json_encode($quiz->title) }}, {{ json_encode($quiz->manual_content) }})" class="inline-flex items-center px-3 py-1 text-xs font-bold uppercase border-2 border-black bg-white text-black hover:bg-black hover:text-white transition duration-150">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        View
                                    </button>
                                    @if($quiz->pdf_file_path)
                                        <a href="{{ Storage::url($quiz->pdf_file_path) }}" target="_blank" class="inline-flex items-center px-3 py-1 text-xs font-bold uppercase border-2 border-gray-600 bg-white text-gray-700 hover:bg-gray-600 hover:text-white transition duration-150">
                                            PDF
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ Storage::url($quiz->pdf_file_path) }}" target="_blank" class="inline-flex items-center px-3 py-1 text-xs font-bold uppercase border-2 border-gray-600 bg-white text-gray-700 hover:bg-gray-600 hover:text-white transition duration-150">
                                        Open PDF
                                    </a>
                                @endif

                                <a href="{{ route('quizzes.edit', $quiz) }}" class="inline-flex items-center px-3 py-1 text-xs font-bold uppercase border-2 border-amber-500 bg-white text-amber-700 hover:bg-amber-500 hover:text-white transition duration-150">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    Edit
                                </a>

                                <form action="{{ route('quizzes.destroy', $quiz) }}" method="POST" class="delete-quiz-form inline-block">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1 text-xs font-bold uppercase border-2 border-red-500 bg-white text-red-600 hover:bg-red-500 hover:text-white transition duration-150">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="flex flex-col items-center justify-center py-16 px-4 text-center">
            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <h3 class="text-lg font-bold text-gray-900">No quizzes found</h3>
            <p class="text-sm text-gray-500 max-w-sm mt-1">
                {{ request('search') ? 'No results matched your search term.' : 'Get started by creating your first teacher quiz.' }}
            </p>
        </div>
        @endif
    </div>

    @if($quizzes->hasPages())
    <div class="mt-4">
        {{ $quizzes->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<!-- Modal for Viewing MCQ Quiz Content -->
<div id="view-content-modal" class="fixed inset-0 bg-black bg-opacity-65 backdrop-blur-sm z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-all duration-300">
    <div class="w-full max-w-3xl bg-white border-2 border-black rounded shadow-2xl overflow-hidden transform translate-y-8 transition-all duration-300" id="modal-container">
        <!-- Header -->
        <div class="bg-black text-white px-6 py-4 flex items-center justify-between">
            <h3 id="modal-title" class="text-base font-bold uppercase tracking-wider">Quiz Content</h3>
            <button onclick="closeQuizModal()" class="text-gray-400 hover:text-white transition duration-150">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <!-- Body -->
        <div class="p-6 max-h-[70vh] overflow-y-auto bg-gray-50 border-b-2 border-black">
            <div id="modal-content" class="bg-white border border-gray-300 rounded p-6 shadow-inner">
                <!-- MCQ dynamic content injected here -->
            </div>
        </div>
        <!-- Footer -->
        <div class="px-6 py-4 bg-white flex justify-end">
            <button onclick="closeQuizModal()" class="px-6 py-2.5 text-xs font-bold uppercase border-2 border-black bg-black text-white hover:bg-white hover:text-black transition duration-150">
                Close Quiz Sheet
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function showQuizContent(title, contentJson) {
        document.getElementById('modal-title').textContent = title;
        
        const modalContentDiv = document.getElementById('modal-content');
        modalContentDiv.innerHTML = '';
        
        try {
            const questions = JSON.parse(contentJson);
            
            if (!Array.isArray(questions) || questions.length === 0) {
                modalContentDiv.innerHTML = '<div class="text-red-500 font-bold p-2 text-center">No questions found or invalid format.</div>';
            } else {
                let html = '<div class="flex flex-col gap-6">';
                questions.forEach((q, qIndex) => {
                    html += `
                        <div class="border-b border-gray-200 pb-5 last:border-0 last:pb-0">
                            <h4 class="font-bold text-black mb-3 text-sm flex gap-2">
                                <span class="bg-black text-white w-5 h-5 flex items-center justify-center rounded text-[10px] flex-shrink-0 mt-0.5">${qIndex + 1}</span>
                                <span class="flex-1 leading-relaxed">${escapeHtml(q.question)}</span>
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pl-7">
                    `;
                    
                    q.options.forEach((opt, optIndex) => {
                        const isCorrect = optIndex === parseInt(q.correct_option);
                        const optionLabel = String.fromCharCode(65 + optIndex);
                        
                        if (isCorrect) {
                            html += `
                                <div class="flex items-center gap-3 p-3 bg-gray-50 border-2 border-black rounded text-xs font-bold text-black shadow-sm">
                                    <span class="inline-flex items-center justify-center w-5 h-5 bg-black text-white rounded text-[10px] font-black flex-shrink-0">${optionLabel}</span>
                                    <span class="flex-1">${escapeHtml(opt)}</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-black text-[9px] font-black text-white uppercase tracking-wider">Correct</span>
                                </div>
                            `;
                        } else {
                            html += `
                                <div class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded text-xs text-gray-600">
                                    <span class="inline-flex items-center justify-center w-5 h-5 bg-gray-100 text-gray-500 rounded text-[10px] font-black flex-shrink-0">${optionLabel}</span>
                                    <span class="flex-1">${escapeHtml(opt)}</span>
                                </div>
                            `;
                        }
                    });
                    
                    html += `
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                modalContentDiv.innerHTML = html;
            }
        } catch (e) {
            modalContentDiv.innerHTML = `<div class="text-red-500 font-bold p-2 text-center">Failed to parse quiz content.</div><pre class="text-xs text-gray-500 mt-2 bg-gray-100 p-2 overflow-x-auto">${escapeHtml(contentJson)}</pre>`;
        }

        const modal = document.getElementById('view-content-modal');
        const container = document.getElementById('modal-container');
        
        modal.classList.remove('opacity-0', 'pointer-events-none');
        container.classList.remove('translate-y-8');
    }

    function escapeHtml(text) {
        if (!text) return '';
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function closeQuizModal() {
        const modal = document.getElementById('view-content-modal');
        const container = document.getElementById('modal-container');
        
        modal.classList.add('opacity-0', 'pointer-events-none');
        container.classList.add('translate-y-8');
    }

    // Close modal on ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeQuizModal();
        }
    });

    // Close modal on clicking outside container
    document.getElementById('view-content-modal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeQuizModal();
        }
    });

    // Delete confirmation handler
    document.querySelectorAll('.delete-quiz-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            var f = this;
            Swal.fire({
                title: 'Delete this MCQ Quiz?',
                text: 'Are you sure you want to permanently delete this quiz?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#737373',
                confirmButtonText: 'Yes, delete it!'
            }).then(function(r) {
                if (r.isConfirmed) {
                    f.submit();
                }
            });
        });
    });
</script>
@endsection
