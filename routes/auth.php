<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Company\EmployeeController;
use App\Http\Controllers\Company\DashboardController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;

Route::middleware('guest')->group(function () {
    // Route::get('register', [RegisteredUserController::class, 'create'])
    //             ->name('register');

    // Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');

    Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('profile.changePassword');
    Route::post('/change-password', [ProfileController::class, 'changePasswordProcess'])->name('profile.changePasswordProcess');

    Route::get('/dashboard', [DashboardController::class, 'index'])
                ->name('dashboard');

    //employee management
    Route::get('/employee', [EmployeeController::class, 'index'])->name('employee.index');
    Route::get('/employee/create',[EmployeeController::class,'create'])->name('employee.create');
    Route::post('/employee/store',[EmployeeController::class,'store'])->name('employee.store');
    Route::get('/employee/{employee_id}/edit',[EmployeeController::class,'edit'])->name('employee.edit');
    Route::put('/employee/{employee_id}',[EmployeeController::class,'update'])->name('employee.update');
    Route::get('/employee/{employee_id}',[EmployeeController::class,'destroy'])->name('employee.delete');

    Route::get('/employee/order/{id}', [EmployeeController::class, 'order'])->name('employee.order');
    Route::get('/employee/order/{id}/create',[EmployeeController::class,'orderCreate'])->name('employee.order.create');
    Route::post('/employee/order/store',[EmployeeController::class,'orderStore'])->name('employee.order.store');
    Route::get('/employee/order/{employeeId}/{orderId}/edit',[EmployeeController::class,'orderEdit'])->name('employee.order.edit');
    Route::post('/employee/order/{id}',[EmployeeController::class,'orderUpdate'])->name('employee.order.update');
    Route::post('/employee-order-payment',[EmployeeController::class,'orderPayment'])->name('employee.order.payment');
    Route::get('/employee-order-singleprint/{employeeId}/{orderId}',[EmployeeController::class,'singlePrint'])->name('employee.order.singleprint');
    Route::get('/employee-order-print/{employeeId}',[EmployeeController::class,'orderPrint'])->name('employee.order.print');


    //items
    Route::get('/items',[EmployeeController::class,'items'])->name('items.index');
});
