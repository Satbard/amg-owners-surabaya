<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\Admin\RegistrationController as AdminRegistrationController;
use App\Http\Controllers\Admin\HomepageContentController;
use App\Http\Controllers\Admin\ActivityLogController;

Route::get('/', [HomeController::class, 'index']);

Route::get('/register', [
    RegistrationController::class,
    'create'
]);

Route::post('/register', [
    RegistrationController::class,
    'store'
]);

Route::prefix('admin')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login']);

    Route::post('/logout', [AuthController::class, 'logout']);

        Route::middleware([
            'auth',
            'role:admin,super_admin'
        ])->group(function () {

            Route::get('/', [DashboardController::class, 'index']);

            Route::get('/registrations', [
                AdminRegistrationController::class,
                'index'
            ]);

            Route::get('/registrations/{registration}', [
                AdminRegistrationController::class,
                'show'
            ]);

            Route::get('/registrations/{registration}/edit', [
                AdminRegistrationController::class,
                'edit'
            ]);
    
            Route::put('/registrations/{registration}', [
                AdminRegistrationController::class,
                'update'
            ]);

            Route::delete('/registrations/{registration}', [
                AdminRegistrationController::class, 'destroy'
            ]);

            Route::get('/registrations-trash', [
                AdminRegistrationController::class, 'trash'
            ]);

            Route::post('/registrations-trash/{id}/restore', [
                AdminRegistrationController::class, 'restore'
            ]);

            Route::delete('/registrations-trash/{id}/force-delete', [
                AdminRegistrationController::class, 'forceDelete'
            ]);

            Route::get('/registrations-export', [
                AdminRegistrationController::class, 'export'
            ]);

            Route::get('/content', [
                HomepageContentController::class, 'edit'
            ]);

            Route::put('/content', [
                HomepageContentController::class, 'update'
            ]);

            Route::get('/activity-logs', [
                ActivityLogController::class, 'index'
            ]);
        });
});