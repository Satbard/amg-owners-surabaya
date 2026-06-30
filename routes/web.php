<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\HomepageContentController;
use App\Http\Controllers\Admin\RegistrationController as AdminRegistrationController;
use App\Http\Controllers\Admin\ScanController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);

Route::get('/register', [
    RegistrationController::class,
    'create',
]);

Route::post('/register', [
    RegistrationController::class,
    'store',
]);

Route::prefix('admin')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login']);

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware([
        'auth',
        'role:admin,super_admin',
    ])->group(function () {

        Route::get('/', [DashboardController::class, 'index']);

        // Registrations
        Route::get('/registrations', [
            AdminRegistrationController::class,
            'index',
        ]);

        Route::get('/registrations/{registration}', [
            AdminRegistrationController::class,
            'show',
        ]);

        Route::get('/registrations/{registration}/edit', [
            AdminRegistrationController::class,
            'edit',
        ]);

        Route::put('/registrations/{registration}', [
            AdminRegistrationController::class,
            'update',
        ]);

        Route::delete('/registrations/{registration}', [
            AdminRegistrationController::class, 'destroy',
        ]);

        Route::get('/registrations-trash', [
            AdminRegistrationController::class, 'trash',
        ]);

        Route::post('/registrations-trash/{id}/restore', [
            AdminRegistrationController::class, 'restore',
        ]);

        Route::delete('/registrations-trash/{id}/force-delete', [
            AdminRegistrationController::class, 'forceDelete',
        ]);

        Route::get('/registrations-export', [
            AdminRegistrationController::class, 'export',
        ]);

        // Events
        Route::get('/events', [EventController::class, 'index']);
        Route::get('/events/create', [EventController::class, 'create']);
        Route::post('/events', [EventController::class, 'store']);
        Route::get('/events/{event}', [EventController::class, 'show']);
        Route::get('/events/{event}/edit', [EventController::class, 'edit']);
        Route::put('/events/{event}', [EventController::class, 'update']);
        Route::delete('/events/{event}', [EventController::class, 'destroy']);

        // Attendance
        Route::put('/events/{event}/attendance/{attendance}', [
            AttendanceController::class, 'update',
        ]);
        Route::post('/events/{event}/attendance/scan', [
            AttendanceController::class, 'scan',
        ]);

        // Scan (global)
        Route::get('/scan', [ScanController::class, 'index']);
        Route::post('/scan/lookup', [ScanController::class, 'lookup']);
        Route::post('/scan/confirm', [ScanController::class, 'confirm']);

        // Content
        Route::get('/content', [
            HomepageContentController::class, 'edit',
        ]);

        Route::put('/content', [
            HomepageContentController::class, 'update',
        ]);

        // Activity Logs
        Route::get('/activity-logs', [
            ActivityLogController::class, 'index',
        ]);
    });
});
