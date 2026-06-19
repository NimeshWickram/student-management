<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Admin Auth Routes
Route::middleware('guest:web')->group(function () {
    Route::get('login', [App\Http\Controllers\Auth\AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [App\Http\Controllers\Auth\AdminAuthController::class, 'login']);
});

Route::middleware('auth:web')->group(function () {
    Route::post('logout', [App\Http\Controllers\Auth\AdminAuthController::class, 'logout'])->name('admin.logout');
    Route::get('admin/login', function () { return redirect()->route('admin.login'); }); // alias if needed

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('profile', [App\Http\Controllers\AdminProfileController::class, 'show'])->name('admin.profile');
    Route::post('profile/password', [App\Http\Controllers\AdminProfileController::class, 'updatePassword'])->name('admin.profile.password');
    Route::post('change-tenant', [DashboardController::class, 'changeTenant'])->name('change.tenant');
    Route::post('/bot/chat', [App\Http\Controllers\BotController::class, 'chat'])->name('bot.chat');

    // Students
    Route::get('students/search', [StudentController::class, 'search'])->name('students.search');
    Route::get('students/export-excel', [StudentController::class, 'exportExcel'])->name('students.export.excel');
    Route::get('students/export-pdf', [StudentController::class, 'exportPdf'])->name('students.export.pdf');
    Route::post('students/import', [StudentController::class, 'import'])->name('students.import');
    Route::resource('students', StudentController::class)->except(['create', 'show', 'edit']);

    // Teachers
    Route::get('teachers/search', [TeacherController::class, 'search'])->name('teachers.search');
    Route::get('teachers/export-excel', [TeacherController::class, 'exportExcel'])->name('teachers.export.excel');
    Route::get('teachers/export-pdf', [TeacherController::class, 'exportPdf'])->name('teachers.export.pdf');
    Route::post('teachers/import', [TeacherController::class, 'import'])->name('teachers.import');
    Route::resource('teachers', TeacherController::class)->except(['create', 'show', 'edit']);

    // Subjects
    Route::get('subjects/search', [SubjectController::class, 'search'])->name('subjects.search');
    Route::get('subjects/export-excel', [SubjectController::class, 'exportExcel'])->name('subjects.export.excel');
    Route::get('subjects/export-pdf', [SubjectController::class, 'exportPdf'])->name('subjects.export.pdf');
    Route::post('subjects/import', [SubjectController::class, 'import'])->name('subjects.import');
    Route::resource('subjects', SubjectController::class)->except(['create', 'show', 'edit']);

    // Quizzes
    Route::post('quizzes/parse-pdf', [App\Http\Controllers\QuizController::class, 'parsePdf'])->name('quizzes.parse-pdf');
    Route::resource('quizzes', App\Http\Controllers\QuizController::class);
});

// Student Routes
Route::prefix('student')->name('student.')->group(function () {
    // Guest Routes
    Route::middleware('guest:student')->group(function () {
        Route::get('login', [App\Http\Controllers\Student\AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [App\Http\Controllers\Student\AuthController::class, 'login']);
    });

    // Authenticated Routes
    Route::middleware('auth:student')->group(function () {
        Route::get('dashboard', [App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');
        Route::get('quiz/{id}', [App\Http\Controllers\Student\DashboardController::class, 'takeQuiz'])->name('quiz.take');
        Route::post('quiz/{id}/submit', [App\Http\Controllers\Student\DashboardController::class, 'submitQuiz'])->name('quiz.submit');
        Route::get('quiz/result/{id}', [App\Http\Controllers\Student\DashboardController::class, 'viewResult'])->name('quiz.result');
        Route::get('profile', [App\Http\Controllers\Student\ProfileController::class, 'show'])->name('profile');
        Route::post('profile/password', [App\Http\Controllers\Student\ProfileController::class, 'updatePassword'])->name('profile.password');
        Route::post('logout', [App\Http\Controllers\Student\AuthController::class, 'logout'])->name('logout');
    });
});

// Teacher Routes
Route::prefix('teacher')->name('teacher.')->group(function () {
    // Guest Routes
    Route::middleware('guest:teacher')->group(function () {
        Route::get('login', [App\Http\Controllers\Teacher\AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [App\Http\Controllers\Teacher\AuthController::class, 'login']);
    });

    // Authenticated Routes
    Route::middleware('auth:teacher')->group(function () {
        Route::get('dashboard', [App\Http\Controllers\Teacher\DashboardController::class, 'index'])->name('dashboard');
        Route::get('results', [App\Http\Controllers\Teacher\ResultController::class, 'index'])->name('results.index');
        Route::get('results/export-excel', [App\Http\Controllers\Teacher\ResultController::class, 'exportExcel'])->name('results.export.excel');
        Route::get('results/export-pdf', [App\Http\Controllers\Teacher\ResultController::class, 'exportPdf'])->name('results.export.pdf');
        Route::get('results/{submission}', [App\Http\Controllers\Teacher\ResultController::class, 'show'])->name('results.show');
        Route::get('results/quiz/{quiz}', [App\Http\Controllers\Teacher\ResultController::class, 'quizResults'])->name('results.quiz');
        Route::get('students', [App\Http\Controllers\Teacher\StudentController::class, 'index'])->name('students.index');
        Route::get('students/{student}', [App\Http\Controllers\Teacher\StudentController::class, 'show'])->name('students.show');
        Route::get('profile', [App\Http\Controllers\Teacher\ProfileController::class, 'show'])->name('profile');
        Route::post('profile/password', [App\Http\Controllers\Teacher\ProfileController::class, 'updatePassword'])->name('profile.password');
        Route::post('logout', [App\Http\Controllers\Teacher\AuthController::class, 'logout'])->name('logout');
    });
});

