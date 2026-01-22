<?php

use App\Http\Controllers\ShortUrlController;
use App\Http\Controllers\InvitationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'company'])->group(function () {
    Route::resource('short-urls', ShortUrlController::class);

    Route::get('/r/{code}', [ShortUrlController::class, 'redirect'])
        ->name('short-url.redirect');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/invitations/create', [InvitationController::class, 'create'])
        ->name('invitations.create');

    Route::post('/invitations', [InvitationController::class, 'store'])
        ->name('invitations.store');

});

// Remove the default Breeze dashboard route
// Route::get('/dashboard', [ProfileController::class, 'edit'])->name('dashboard');

        Route::get('/export/sheet', [ShortUrlController::class, 'export'])
    ->name('short-urls.export');

require __DIR__.'/auth.php';
