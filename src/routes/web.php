<?php

/*
|--------------------------------------------------------------------------
| Laravel Blocker Web Routes
|--------------------------------------------------------------------------
|
*/

Route::group([
        'middleware'    => ['web'],
        'prefix'        => 'blocker',
        'as'            => 'laravelblocker::',
        'namespace'     => 'jeremykenedy\LaravelBlocker\App\Http\Controllers'
    ], function () {

        // Dashboards
        Route::get('/', 'LaravelBlockerController@index')->name('blocker');

});

