<?php

use App\Http\Controllers\EmailMessageController;
use Illuminate\Support\Facades\Route;

Route::post('email-messages', [EmailMessageController::class, 'apiUpload'])
    ->name('api_email_message_upload');
