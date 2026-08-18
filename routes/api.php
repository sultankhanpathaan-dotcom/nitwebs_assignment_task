<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LeaveRequestController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('leave_request', [LeaveRequestController::class, 'leave_request']);
Route::post('approve_reject_leave', [LeaveRequestController::class, 'approve_reject_leave']);
Route::get('list_employee_leave_request', [LeaveRequestController::class, 'list_employee_leave_request']);
