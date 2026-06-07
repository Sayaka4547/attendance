<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CorrectionRequestController;
use App\Http\Controllers\Admin\AdminAttendanceController;
use App\Http\Controllers\Admin\AdminStaffController;
use App\Http\Controllers\Admin\AdminCorrectionRequestController;

/*
|--------------------------------------------------------------------------
| 一般ユーザー向けルート（認証必須）
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // 勤怠打刻画面
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clockIn');
    Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clockOut');
    Route::post('/attendance/break-start', [AttendanceController::class, 'breakStart'])->name('attendance.breakStart');
    Route::post('/attendance/break-end', [AttendanceController::class, 'breakEnd'])->name('attendance.breakEnd');

    // 勤怠一覧画面
    Route::get('/attendance/list', [AttendanceController::class, 'list'])->name('attendance.list');

    // 勤怠詳細・修正申請画面
    Route::get('/attendance/detail/{id}', [AttendanceController::class, 'detail'])->name('attendance.detail');
    Route::post('/attendance/detail/{id}', [AttendanceController::class, 'requestCorrection'])->name('attendance.requestCorrection');

    // 申請一覧画面
    Route::get('/stamp_correction_request/list', [CorrectionRequestController::class, 'index'])->name('correction-request.index');
});

/*
|--------------------------------------------------------------------------
| 管理者向けルート（admin ミドルウェア）
|--------------------------------------------------------------------------
*/
Route::middleware('admin')->prefix('admin')->group(function () {

    // 日次勤怠一覧画面
    Route::get('/attendance/list', [AdminAttendanceController::class, 'index'])->name('admin.attendance.index');

    // 勤怠詳細画面（管理者)
    Route::get('/attendance/{id}', [AdminAttendanceController::class, 'detail'])->name('admin.attendance.detail');
    Route::post('/attendance/{id}', [AdminAttendanceController::class, 'update'])->name('admin.attendance.update');

    // スタッフ一覧画面
    Route::get('/staff/list', [AdminStaffController::class, 'index'])->name('admin.staff.index');

    // スタッフ別月次勤怠一覧画面
    Route::get('/attendance/staff/{id}', [AdminStaffController::class, 'attendance'])->name('admin.staff.attendance');

    // 申請一覧画面（管理者）
    Route::get('/stamp_correction_request/list', [AdminCorrectionRequestController::class, 'index'])->name('admin.correction-request.index');

    // 修正申請承認画面
    Route::get('/stamp_correction_request/approve/{attendance_correct_request_id}', [AdminCorrectionRequestController::class, 'approve'])->name('admin.correction-request.approve');
    Route::post('/stamp_correction_request/approve/{attendance_correct_request_id}', [AdminCorrectionRequestController::class, 'store'])->name('admin.correction-request.store');
});

    // 管理者ログイン画面
Route::get('/admin/login', function () {
    return view('auth.admin-login');
})->name('admin.login')->middleware('guest');

Route::post('/admin/login', [App\Http\Controllers\Admin\AdminAuthController::class, 'login'])
    ->name('admin.login.post')
    ->middleware('guest');
