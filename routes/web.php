<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\AcademicStaffController;
use App\Http\Controllers\AcademicHeadController;
use App\Http\Controllers\Admin\UserController;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated routes
Route::middleware('auth')->group(function () {

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Shared document upload route
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');

    // Notifications
    Route::post('/notifications/mark-all-read', function () {
        Auth::user()->unreadNotifications->markAsRead();    
        return response()->json(['status' => 'ok']);
    })->name('notifications.markAllRead');
});

// Admin routes
Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);
});

// Academic Staff routes
Route::prefix('academic-staff')->middleware(['auth'])->name('academic-staff.')->group(function () {
    // Tasks
    Route::get('/tasks', [AcademicStaffController::class, 'viewTasks'])->name('tasks.index');
    Route::get('/tasks/{task}', [AcademicStaffController::class, 'show'])->name('tasks.show');
    Route::get('/tasks/{task}/edit', [AcademicStaffController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [AcademicStaffController::class, 'update'])->name('tasks.update');

    // Upload document (GET + POST)
    Route::get('/tasks/{task}/upload', [AcademicStaffController::class, 'showUploadForm'])->name('tasks.upload.view');
    Route::post('/tasks/{task}/upload', [AcademicStaffController::class, 'uploadDocument'])->name('tasks.upload');

    // Groups
    Route::get('/groups', [AcademicStaffController::class, 'viewGroups'])->name('groups.index');
    Route::get('/groups/{id}', [AcademicStaffController::class, 'showGroup'])->name('groups.show');

    // Comments
    Route::resource('comments', CommentController::class)
        ->only(['store', 'edit', 'update', 'destroy'])
        ->names('comments');
});

// Academic Head routes
Route::prefix('academic-head')->middleware(['auth'])->name('academic-head.')->group(function () {

    // Tasks
    Route::get('/tasks', [AcademicHeadController::class, 'viewTasks'])->name('tasks.index');
    Route::get('/tasks/create', [AcademicHeadController::class, 'createTask'])->name('tasks.create');
    Route::post('/tasks', [AcademicHeadController::class, 'storeTask'])->name('tasks.store');
    Route::get('/tasks/{task}', [AcademicHeadController::class, 'show'])->name('tasks.show');
    Route::get('/tasks/{task}/edit', [AcademicHeadController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [AcademicHeadController::class, 'updateTask'])->name('tasks.update');
    Route::delete('/tasks/{task}', [AcademicHeadController::class, 'destroy'])->name('tasks.destroy');
    Route::get('/tasks/{task}/download', [AcademicHeadController::class, 'download'])->name('tasks.download');

    // Groups
    Route::get('/groups', [AcademicHeadController::class, 'viewGroups'])->name('groups.index');
    Route::get('/groups/create', [AcademicHeadController::class, 'createGroup'])->name('groups.create');
    Route::post('/groups', [AcademicHeadController::class, 'storeGroup'])->name('groups.store');
    Route::get('/groups/{id}', [AcademicHeadController::class, 'showGroup'])->name('groups.show');
    Route::get('/groups/{id}/edit', [AcademicHeadController::class, 'editGroup'])->name('groups.edit');
    Route::put('/groups/{id}', [AcademicHeadController::class, 'updateGroup'])->name('groups.update');
    Route::delete('/groups/{id}', [AcademicHeadController::class, 'destroyGroup'])->name('groups.destroy');

    // Comments
    Route::resource('comments', CommentController::class)
        ->only(['store', 'edit', 'update', 'destroy'])
        ->names('comments');
});

require __DIR__.'/auth.php';
