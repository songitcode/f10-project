<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\User\RepairBillController as UserRepairBillController;
use App\Http\Controllers\Admin\RepairBillController as AdminRepairBillController;
use App\Http\Controllers\EmployeeBillController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\WorkScheduleController;
use App\Http\Middleware\CheckRole;

Route::get('/', function () {
    return view('auth.login');
});
Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/', [LoginController::class, 'login'])->name('login.post');
});
// Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/home', function () {
        return view('pages.home');
    })->name('home');
    Route::get('/bills', function () {
        return view('pages.bills');
    })->name('bills.index');

    Route::get('/profile', function () {
        return view('pages.profile');
    })->name('profile');

    Route::get('/history', function () {
        return view('pages.history');
    })->name('history');

    // Route profile function
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');

    // Repair Bill Routes
    Route::get('/bills', [UserRepairBillController::class, 'index'])->name('bills.index');
    Route::post('/bills', [UserRepairBillController::class, 'store'])->name('bills.store');
    Route::get('/repair-bills/{id}', [UserRepairBillController::class, 'showBill'])->name('employees.bills.detail');

    // work-schedule
    Route::get('/work-schedule', [WorkScheduleController::class, 'hienThiLichLamViecTuan'])->name('work-schedule');
});

// Route quản lý
Route::middleware(['auth', CheckRole::class])->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Employee Routes
    Route::resource('employees', EmployeeController::class);
    Route::post('employees/{employee}/activate', [EmployeeController::class, 'activate'])->name('employees.activate');
    Route::delete('employees/{id}/delete', [EmployeeController::class, 'destroyPermanent'])->name('employees.destroyPermanent');

    // Position Routes
    Route::resource('positions', PositionController::class);

    // Repair Bill Routes
    // Route::resource('repair-bills', AdminRepairBillController::class);
    // Route::post('repair-bills/{repairBill}/update-status', [AdminRepairBillController::class, 'updateStatus'])
    //     ->name('repair-bills.update-status');
    // Route::get('/repair-bills', [RepairBillController::class, 'index'])->name('repair-bills.index');
    // Route::get('/repair-bills/{id}', [RepairBillController::class, 'show'])->name('repair-bills.show');
    // Route::patch('/repair-bills/{id}/status', [RepairBillController::class, 'updateStatus'])->name('repair-bills.update-status');
    Route::get('/repair-bills', [AdminRepairBillController::class, 'index'])->name('repair-bills.index');
    Route::get('/repair-bills/{id}', [AdminRepairBillController::class, 'show'])->name('repair-bills.show');
    Route::patch('/repair-bills/{id}/status', [AdminRepairBillController::class, 'updateStatus'])->name('repair-bills.update-status');

    // Employee
    Route::get('/employees-bills', [EmployeeBillController::class, 'index'])->name('employees.bills.index');
    Route::get('/employees/{id}/bills', [EmployeeBillController::class, 'getBills'])->name('employees.bills.list');
    Route::get('/repair-bills/{id}', [EmployeeBillController::class, 'showBill'])->name('employees.bills.detail');
    Route::patch('/repair-bills/{id}', [EmployeeBillController::class, 'updateBill'])->name('repair-bills.update');

    // Schedule
    Route::resource('hr-lich-lam-viec', WorkScheduleController::class);

    // Role
    Route::patch('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');

});