<?php

/*
|--------------------------------------------------------------------------
| Laravel Blocker Web Routes
|--------------------------------------------------------------------------
|
*/

// Route::get('blocker', function () {
//     dd('blocker is here');
// });


Route::group([
        'middleware'    => ['web'],
        'prefix'        => 'blocker',
        'as'            => 'laravelblocker::',
        'namespace'     => 'jeremykenedy\LaravelBlocker\App\Http\Controllers'
    ], function () {

        // Dashboards
        Route::get('/', 'LaravelBlockerController@index')->name('blocker');

        // Route::resource('users', 'UsersManagementController', [
        //     'names' => [
        //         'index'   => 'users',
        //         'destroy' => 'user.destroy',
        //     ],
        // ]);

        // Route::get('/', function () {
        //     dd('blocker is here');
        // });

});

// Route::group(['prefix' => 'blocker','namespace' => 'jeremykenedy\laravelBlocker\App\Http\Controllers','middleware' => []], function() {

//     // Dashboards
//     Route::get('/', 'LaravelBlockerController@index')->name('blocker');


// //     // Route::get('/cleared', ['uses' => 'LaravelLoggerController@showClearedActivityLog'])->name('cleared');

// //     // // Drill Downs
// //     // Route::get('/log/{id}', 'LaravelLoggerController@showAccessLogEntry');
// //     // Route::get('/cleared/log/{id}', 'LaravelLoggerController@showClearedAccessLogEntry');

// //     // // Forms
// //     // Route::delete('/clear-activity', ['uses' => 'LaravelLoggerController@clearActivityLog'])->name('clear-activity');
// //     // Route::delete('/destroy-activity', ['uses' => 'LaravelLoggerController@destroyActivityLog'])->name('destroy-activity');
// //     // Route::post('/restore-log', ['uses' => 'LaravelLoggerController@restoreClearedActivityLog'])->name('restore-activity');

// });
