<?php

use Illuminate\Support\Facades\Route;
use sashaheg07\SmsPasswordReset\Http\Controllers\SmsPasswordResetController;

Route::post('/password/forgot', [SmsPasswordResetController::class, 'forgot']);
Route::post('/password/verify', [SmsPasswordResetController::class, 'verify']);
Route::post('/password/reset', [SmsPasswordResetController::class, 'reset']);
