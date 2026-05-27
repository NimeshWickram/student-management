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

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
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
        Route::post('logout', [App\Http\Controllers\Student\AuthController::class, 'logout'])->name('logout');
    });
});
