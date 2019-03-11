<?php

/*
|--------------------------------------------------------------------------
| Laravel Blocker Web Routes
|--------------------------------------------------------------------------
|
*/
Route::group([
        'middleware'    => ['web', 'checkblocked'],
        'as'            => 'laravelblocker::',
        'namespace'     => 'jeremykenedy\LaravelBlocker\App\Http\Controllers',
    ], function () {

    // Blocker
        Route::resource('blocker', 'LaravelBlockerController');

        // Blocker Soft Deleted
        Route::get('blocker-deleted', 'LaravelBlockerDeletedController@index')->name('blocker-deleted');
        Route::get('blocker-deleted/{id}', 'LaravelBlockerDeletedController@show')->name('blocker-item-show-deleted');
        Route::put('blocker-deleted/{id}', 'LaravelBlockerDeletedController@restoreBlockedItem')->name('blocker-item-restore');
        Route::post('blocker-deleted-restore-all', 'LaravelBlockerDeletedController@restoreAllBlockedItems')->name('blocker-deleted-restore-all');
        Route::delete('blocker-deleted/{id}', 'LaravelBlockerDeletedController@destroy')->name('blocker-item-destroy');
        Route::delete('blocker-deleted-destroy-all', 'LaravelBlockerDeletedController@destroyAllItems')->name('destroy-all-blocked');

        // Blocker Search
        Route::post('search-blocked', 'LaravelBlockerController@search')->name('search-blocked');
        Route::post('search-blocked-deleted', 'LaravelBlockerDeletedController@search')->name('search-blocked-deleted');
    });
