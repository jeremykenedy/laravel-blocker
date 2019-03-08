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

    Route::resources([
        'blocker' => 'LaravelBlockerController',
    ]);

    Route::post('search-blocked', 'LaravelBlockerController@search')->name('search-blocked');

});
