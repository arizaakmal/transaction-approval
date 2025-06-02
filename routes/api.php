<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApproverController;
use App\Http\Controllers\ApprovalStageController;
use App\Http\Controllers\ExpenseController;

Route::post('/approvers', [ApproverController::class, 'store']);
Route::post('/approval-stages', [ApprovalStageController::class, 'store']);
Route::put('/approval-stages/{id}', [ApprovalStageController::class, 'update']);
Route::post('/expense', [ExpenseController::class, 'store']);
Route::patch('/expense/{id}/approve', [ExpenseController::class, 'approve']);
Route::get('/expense/{id}', [ExpenseController::class, 'show']);
