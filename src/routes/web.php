<?php

use Illuminate\Support\Facades\Route;
use jeremykenedy\LaravelBlocker\App\Http\Controllers\LaravelBlockerController;
use jeremykenedy\LaravelBlocker\App\Http\Controllers\LaravelBlockerDeletedController;

/*
|--------------------------------------------------------------------------
| Laravel Blocker Web Routes
|--------------------------------------------------------------------------
|
*/
Route::group([
    'middleware'    => ['web', 'checkblocked'],
    'as'            => 'laravelblocker::',
], function () {
    // Blocker
    Route::resource('blocker', LaravelBlockerController::class);

    // Blocker Soft Deleted
    Route::get('blocker-deleted', [LaravelBlockerDeletedController::class, 'index'])->name('blocker-deleted');
    Route::get('blocker-deleted/{id}', [LaravelBlockerDeletedController::class, 'show'])->name('blocker-item-show-deleted');
    Route::put('blocker-deleted/{id}', [LaravelBlockerDeletedController::class, 'restoreBlockedItem'])->name('blocker-item-restore');
    Route::post('blocker-deleted-restore-all', [LaravelBlockerDeletedController::class, 'restoreAllBlockedItems'])->name('blocker-deleted-restore-all');
    Route::delete('blocker-deleted/{id}', [LaravelBlockerDeletedController::class, 'destroy'])->name('blocker-item-destroy');
    Route::delete('blocker-deleted-destroy-all', [LaravelBlockerDeletedController::class, 'destroyAllItems'])->name('destroy-all-blocked');

    // Blocker Search
    Route::post('search-blocked', [LaravelBlockerController::class, 'search'])->name('search-blocked');
    Route::post('search-blocked-deleted', [LaravelBlockerDeletedController::class, 'search'])->name('search-blocked-deleted');
});
