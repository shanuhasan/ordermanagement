<?php

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UploadImageController;
use App\Http\Controllers\Admin\Auth\PasswordController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\Auth\VerifyEmailController;
use App\Http\Controllers\Admin\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Admin\Auth\EmailVerificationNotificationController;

Route::group(['middleware' => ['guest:admin'], 'prefix' => 'admin', 'as' => 'admin.'], function () {

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

Route::group(['middleware' => ['auth:admin'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
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

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //company management
    Route::get('/company', [CompanyController::class, 'index'])->name('company.index');
    Route::get('/company/create', [CompanyController::class, 'create'])->name('company.create');
    Route::post('/company/store', [CompanyController::class, 'store'])->name('company.store');
    Route::get('/company/{guid}/edit', [CompanyController::class, 'edit'])->name('company.edit');
    Route::put('/company/{guid}', [CompanyController::class, 'update'])->name('company.update');
    Route::get('/company/{guid}', [CompanyController::class, 'destroy'])->name('company.delete');

    Route::get('/deleted-company', [CompanyController::class, 'deletedCompany'])->name('company.delete.index');
    Route::get('/restore-company/{guid}', [CompanyController::class, 'restore'])->name('company.restore');

    //user management
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
    Route::get('/user/{guid}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/user/{guid}', [UserController::class, 'update'])->name('user.update');
    Route::get('/user/{guid}', [UserController::class, 'destroy'])->name('user.delete');

    Route::get('/deleted-user', [UserController::class, 'deletedUser'])->name('user.delete.index');
    Route::get('/restore-user/{guid}', [UserController::class, 'restore'])->name('user.restore');

    //employee management
    Route::get('/employee', [EmployeeController::class, 'index'])->name('employee.index');
    Route::get('/employee/create', [EmployeeController::class, 'create'])->name('employee.create');
    Route::post('/employee/store', [EmployeeController::class, 'store'])->name('employee.store');
    Route::get('/employee/{employee_id}/edit', [EmployeeController::class, 'edit'])->name('employee.edit');
    Route::put('/employee/{employee_id}', [EmployeeController::class, 'update'])->name('employee.update');
    Route::get('/employee/{employee_id}', [EmployeeController::class, 'destroy'])->name('employee.delete');

    Route::get('/employee/order/{id}', [EmployeeController::class, 'order'])->name('employee.order');
    Route::get('/employee/order/{id}/create', [EmployeeController::class, 'orderCreate'])->name('employee.order.create');
    Route::post('/employee/order/store', [EmployeeController::class, 'orderStore'])->name('employee.order.store');
    Route::get('/employee/order/{employeeId}/{orderId}/edit', [EmployeeController::class, 'orderEdit'])->name('employee.order.edit');
    Route::post('/employee/order/{id}', [EmployeeController::class, 'orderUpdate'])->name('employee.order.update');
    Route::post('/employee-order-payment', [EmployeeController::class, 'orderPayment'])->name('employee.order.payment');
    Route::get('/employee-order-singleprint/{employeeId}/{orderId}', [EmployeeController::class, 'singlePrint'])->name('employee.order.singleprint');
    Route::get('/employee-order-print/{employeeId}', [EmployeeController::class, 'orderPrint'])->name('employee.order.print');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/upload-image', [UploadImageController::class, 'create'])->name('media.create');

    Route::get('/getSlug', function (Request $request) {
        $slug = '';
        if (!empty($request->title)) {
            $slug = Str::slug($request->title);
        }
        return response()->json([
            'status' => true,
            'slug' => $slug,
        ]);
    })->name('getSlug');
});
