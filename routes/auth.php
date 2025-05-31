<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Company\ItemController;
use App\Http\Controllers\Company\SizeController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Company\MasterController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Company\EmployeeController;
use App\Http\Controllers\Company\DashboardController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Company\ContractorController;

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
    Route::get('/employee/create', [EmployeeController::class, 'create'])->name('employee.create');
    Route::post('/employee/store', [EmployeeController::class, 'store'])->name('employee.store');
    Route::get('/employee/{employee_id}/edit', [EmployeeController::class, 'edit'])->name('employee.edit');
    Route::put('/employee/{employee_id}', [EmployeeController::class, 'update'])->name('employee.update');
    Route::get('/employee/{employee_id}', [EmployeeController::class, 'destroy'])->name('employee.delete');

    // contractor
    Route::get('/contractor', [ContractorController::class, 'index'])->name('contractor.index');
    Route::get('/contractor/create', [ContractorController::class, 'create'])->name('contractor.create');
    Route::post('/contractor/store', [ContractorController::class, 'storeContractor'])->name('contractor.store');
    Route::get('/contractor/{employee_id}/edit', [ContractorController::class, 'edit'])->name('contractor.edit');
    Route::put('/contractor/{employee_id}', [ContractorController::class, 'update'])->name('contractor.update');
    Route::get('/contractor/{employee_id}', [ContractorController::class, 'destroy'])->name('contractor.delete');

    Route::get('/contractor/order/{id}', [ContractorController::class, 'order'])->name('contractor.order');
    Route::get('/contractor/order/{id}/create', [ContractorController::class, 'orderCreate'])->name('contractor.order.create');
    Route::post('/contractor/order/store', [ContractorController::class, 'orderStore'])->name('contractor.order.store');
    Route::get('/contractor/order/{employeeId}/{orderId}/edit', [ContractorController::class, 'orderEdit'])->name('contractor.order.edit');
    Route::post('/contractor/order/{id}', [ContractorController::class, 'orderUpdate'])->name('contractor.order.update');
    Route::get('/contractor/order/{employeeId}/{orderId}/view', [ContractorController::class, 'orderView'])->name('contractor.order.view');
    Route::post('/contractor-order-payment', [ContractorController::class, 'orderPayment'])->name('contractor.order.payment');
    Route::get('/contractor-order-singleprint/{employeeId}/{orderId}', [ContractorController::class, 'singlePrint'])->name('contractor.order.singleprint');
    Route::get('/contractor/order/received-piece-history/{employeeId}', [ContractorController::class, 'receivedPieceHistory'])->name('contractor.order.receivedPiece');
    Route::get('/contractor-order-print/{employeeId}', [ContractorController::class, 'orderPrint'])->name('contractor.order.print');
    Route::get('/contractor/order/payment-history/{id}', [ContractorController::class, 'paymentHistory'])->name('contractor.order.payment.history');
    Route::get('/contractor/order/amount/{guid}', [ContractorController::class, 'amount'])->name('contractor.order.amount');

    //master management
    Route::get('/master', [MasterController::class, 'index'])->name('master.index');
    Route::get('/master/create', [MasterController::class, 'create'])->name('master.create');
    Route::post('/master/store', [MasterController::class, 'store'])->name('master.store');
    Route::get('/master/{master_id}/edit', [MasterController::class, 'edit'])->name('master.edit');
    Route::put('/master/{master_id}', [MasterController::class, 'update'])->name('master.update');
    Route::get('/master/{master_id}', [MasterController::class, 'destroy'])->name('master.delete');
    Route::get('/master/order/{id}', [MasterController::class, 'order'])->name('master.order');
    Route::get('/master/order/{id}/create', [MasterController::class, 'orderCreate'])->name('master.order.create');
    Route::post('/master/order/store', [MasterController::class, 'orderStore'])->name('master.order.store');
    Route::get('/master/order/{employeeId}/{orderId}/edit', [MasterController::class, 'orderEdit'])->name('master.order.edit');
    Route::post('/master/order/{id}', [MasterController::class, 'orderUpdate'])->name('master.order.update');

    //items management
    Route::get('/item', [ItemController::class, 'index'])->name('item.index');
    Route::get('/item/create', [ItemController::class, 'create'])->name('item.create');
    Route::post('/item/store', [ItemController::class, 'store'])->name('item.store');
    Route::get('/item/{guid}/edit', [ItemController::class, 'edit'])->name('item.edit');
    Route::put('/item/{guid}', [ItemController::class, 'update'])->name('item.update');
    Route::get('/item/{guid}', [ItemController::class, 'destroy'])->name('item.delete');

    //size management
    Route::get('/size', [SizeController::class, 'index'])->name('size.index');
    Route::get('/size/create', [SizeController::class, 'create'])->name('size.create');
    Route::post('/size/store', [SizeController::class, 'store'])->name('size.store');
    Route::get('/size/{guid}/edit', [SizeController::class, 'edit'])->name('size.edit');
    Route::put('/size/{guid}', [SizeController::class, 'update'])->name('size.update');
    Route::get('/size/{guid}', [SizeController::class, 'destroy'])->name('size.delete');

    Route::get('/employee/order/{id}', [EmployeeController::class, 'order'])->name('employee.order');
    Route::get('/employee/order/{id}/create', [EmployeeController::class, 'orderCreate'])->name('employee.order.create');
    Route::post('/employee/order/store', [EmployeeController::class, 'orderStore'])->name('employee.order.store');
    Route::get('/employee/order/{employeeId}/{orderId}/edit', [EmployeeController::class, 'orderEdit'])->name('employee.order.edit');
    Route::post('/employee/order/{id}', [EmployeeController::class, 'orderUpdate'])->name('employee.order.update');
    Route::get('/employee/order/{employeeId}/{orderId}/view', [EmployeeController::class, 'orderView'])->name('employee.order.view');
    Route::post('/employee-order-payment', [EmployeeController::class, 'orderPayment'])->name('employee.order.payment');
    Route::get('/employee-order-singleprint/{employeeId}/{orderId}', [EmployeeController::class, 'singlePrint'])->name('employee.order.singleprint');
    Route::get('/employee/order/received-piece-history/{employeeId}', [EmployeeController::class, 'receivedPieceHistory'])->name('employee.order.receivedPiece');
    Route::get('/employee-order-print/{employeeId}', [EmployeeController::class, 'orderPrint'])->name('employee.order.print');

    Route::get('/employee/order/payment-history/{id}', [EmployeeController::class, 'paymentHistory'])->name('employee.order.payment.history');
    Route::get('/employee/order/amount/{guid}', [EmployeeController::class, 'amount'])->name('employee.order.amount');


    //items
    Route::get('/items', [EmployeeController::class, 'items'])->name('items.index');
});
