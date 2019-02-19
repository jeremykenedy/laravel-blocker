<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel Blocker Settings
    |--------------------------------------------------------------------------
    */

    // The parent blade file
    'laravelBlockerBladeExtended'   => 'layouts.app',

    // Enable `auth` middleware
    'authEnabled'                   => true,

    // Enable Optional Roles Middleware
    'rolesEnabled'                  => false,

    // Optional Roles Middleware
    'rolesMiddlware'                => 'role:admin',

    // Switch Between bootstrap 3 `panel` and bootstrap 4 `card` classes
    'bootstapVersion'               => '4',

    // Additional Card classes for styling -
    // See: https://getbootstrap.com/docs/4.0/components/card/#background-and-color
    // Example classes: 'text-white bg-primary mb-3'
    'bootstrapCardClasses'          => '',

];
