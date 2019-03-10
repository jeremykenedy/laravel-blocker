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
        'namespace'     => 'jeremykenedy\LaravelBlocker\App\Http\Controllers'
    ], function () {

    Route::resource('blocker', 'LaravelBlockerController');
    Route::resource('blocker-deleted', 'LaravelBlockerDeletedController');

    Route::post('search-blocked', 'LaravelBlockerController@search')->name('search-blocked');
    Route::post('search-blocked-deleted', 'LaravelBlockerDeletedController@search')->name('search-blocked-deleted');
});
