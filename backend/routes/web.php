<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

require __DIR__.'/auth.php';

use App\Http\Controllers\DocumentController;

// Serve files stored under storage/app/public when frontend links point to
// either `/storage/app/public/{path}` or `/storage/{path}`. Normalization is
// handled in the controller so existing frontend links will work even if the
// `public/storage` symlink is missing on Windows.
Route::get('storage/app/public/{path}', [DocumentController::class, 'show'])->where('path', '.*');
Route::get('storage/{path}', [DocumentController::class, 'show'])->where('path', '.*');
