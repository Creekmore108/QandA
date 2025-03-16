<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Livewire\QuestAnswerSystem;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    Route::get('/questions', function () {
        return view('questions');
    })->name('questions');
    Route::get('/my-answers', function () {
        return view('my-answers');
    })->name('my-answers');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/questions', function () {
        return view('admin.questions');
    })->name('admin.questions');

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/categories', function () {
        return view('admin.categories');
    })->name('admin.categories');
});

require __DIR__.'/auth.php';
