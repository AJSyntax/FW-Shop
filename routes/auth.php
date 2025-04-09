<?php

use App\Http\Controllers\Auth\SecurityQuestionController;
use Illuminate\Support\Facades\Route;

// Security Questions for Password Reset
Route::get('/forgot-password/security-questions', [SecurityQuestionController::class, 'showVerificationForm'])
    ->middleware('guest')
    ->name('password.security-questions');

Route::post('/forgot-password/security-questions', [SecurityQuestionController::class, 'verifyAnswers'])
    ->middleware('guest')
    ->name('password.verify-security-questions');
