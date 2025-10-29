<?php
// routes/web.php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FacultyController;
use App\Http\Controllers\Admin\QuestionnaireController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\ResponseController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\User\SurveyController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Survey Routes (No Authentication Required)
|--------------------------------------------------------------------------
*/

Route::get('/', [SurveyController::class, 'dashboard'])->name('survey.dashboard');
Route::get('/survey/faculties', [SurveyController::class, 'faculties'])->name('survey.faculties');
Route::get('/survey/questionnaires/{faculty}', [SurveyController::class, 'questionnaires'])->name('survey.questionnaires');
Route::get('/survey/participant-info/{questionnaire}', [SurveyController::class, 'participantInfo'])->name('survey.participant-info');
Route::post('/survey/participant-info/{questionnaire}', [SurveyController::class, 'saveParticipantInfo'])->name('survey.save-participant-info');
Route::get('/survey/start/{questionnaire}', [SurveyController::class, 'start'])->name('survey.start');
Route::post('/survey/submit-answer', [SurveyController::class, 'submitAnswer'])->name('survey.submit-answer');
Route::post('/survey/submit', [SurveyController::class, 'submit'])->name('survey.submit');
Route::get('/survey/thank-you', [SurveyController::class, 'thankYou'])->name('survey.thank-you');
Route::get('/survey/progress', [SurveyController::class, 'getProgress'])->name('survey.progress');

/*
|--------------------------------------------------------------------------
| Complaints Routes
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\User\ComplaintController;

Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
Route::get('/complaints/create', [ComplaintController::class, 'create'])->name('complaints.create');
Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
Route::get('/complaints/thank-you', [ComplaintController::class, 'thankYou'])->name('complaints.thank-you');

/*
|--------------------------------------------------------------------------
| Admin Routes (Authentication Required)
|--------------------------------------------------------------------------
*/

// routes/web.php
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Response Management - Export route must be before resource to avoid conflict
    Route::get('responses/export', [ResponseController::class, 'export'])->name('responses.export');
    Route::resource('responses', ResponseController::class);
    Route::post('responses/bulk-delete', [ResponseController::class, 'bulkDelete'])->name('responses.bulk-delete');

    Route::resource('faculties', FacultyController::class);
    Route::resource('questionnaires', QuestionnaireController::class);

    // Faculty Management
    Route::resource('faculties', FacultyController::class);

    // Questionnaire Management
    Route::resource('questionnaires', QuestionnaireController::class);
    Route::post('questionnaires/{questionnaire}/toggle-status', [QuestionnaireController::class, 'toggleStatus'])
        ->name('questionnaires.toggle-status');
    Route::get('questionnaires/{questionnaire}/preview', [QuestionnaireController::class, 'preview'])
        ->name('questionnaires.preview');

    // Question Management
    Route::resource('questions', QuestionController::class);
    Route::post('questions/bulk-import', [QuestionController::class, 'bulkImport'])
        ->name('questions.bulk-import');
    Route::post('questions/reorder', [QuestionController::class, 'reorder'])
        ->name('questions.reorder');
    
    // Analytics & Reports
    Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('analytics/faculty/{faculty}', [AnalyticsController::class, 'faculty'])->name('analytics.faculty');
    Route::get('analytics/questionnaire/{questionnaire}', [AnalyticsController::class, 'questionnaire'])->name('analytics.questionnaire');
    Route::get('analytics/export', [AnalyticsController::class, 'export'])->name('analytics.export');
    Route::post('analytics/export-pdf', [AnalyticsController::class, 'exportPdf'])->name('analytics.export-pdf');
    Route::post('analytics/export-excel', [AnalyticsController::class, 'exportExcel'])->name('analytics.export-excel');

    // User Management
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

    // Complaints Management
    Route::resource('complaints', \App\Http\Controllers\Admin\ComplaintController::class)->except(['create', 'store']);

    // Notification Management
    Route::get('notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('admin.notifications.index');
    Route::post('notifications/{notification}/read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('admin.notifications.mark-read');
    Route::post('notifications/mark-all-read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])->name('admin.notifications.mark-all-read');
});

/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';