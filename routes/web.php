<?php

use App\Http\Controllers\EmailMessageController;
use Illuminate\Support\Facades\Route;

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/', [EmailMessageController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('email-messages/{id}.html', [EmailMessageController::class, 'viewHTML'])
    ->middleware(['auth', 'verified'])
    ->name('email_messages_view_html');

Route::get('email-messages/{id}', [EmailMessageController::class, 'view'])
    ->middleware(['auth', 'verified'])
    ->name('email_messages_view');

require __DIR__.'/auth.php';
