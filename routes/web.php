<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskReportController;


// Welcome page (before login)
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Dashboard & auth routes
Route::middleware(['auth'])->group(function () {

    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // TASK MANAGEMENT
    Route::get('/manage-task',[TaskController::class,'index'])->name('manage.task');
    Route::get('/task/create',[TaskController::class,'create'])->name('task.create');
    Route::post('/task/store',[TaskController::class,'store'])->name('task.store');
    Route::get('/task/edit/{id}',[TaskController::class,'edit'])->name('task.edit');
    Route::put('/task/update/{id}',[TaskController::class,'update'])->name('task.update');
    Route::delete('/task/delete/{id}',[TaskController::class,'destroy'])->name('task.delete');

    // TASK REPORT
    Route::get('/task-report', [TaskReportController::class, 'index'])->name('task.report');
Route::get('/task-report/pdf', [TaskReportController::class, 'exportPDF'])->name('task.report.pdf');

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

require __DIR__.'/auth.php';