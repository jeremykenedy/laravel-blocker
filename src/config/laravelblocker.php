<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel Blocker Database Settings
    |--------------------------------------------------------------------------
    */
    'blockerDatabaseConnection'     => env('LARAVEL_BLOCKER_DATABASE_CONNECTION', 'mysql'),
    'blockerDatabaseTable'          => env('LARAVEL_BLOCKER_DATABASE_TABLE', 'laravel_blocker'),
    'blockerTypeDatabaseTable'      => env('LARAVEL_BLOCKER_TYPE_DATABASE_TABLE', 'laravel_blocker_types'),

    /*
    |--------------------------------------------------------------------------
    | Laravel Blocker Front End Settings
    |--------------------------------------------------------------------------
    */
    // The parent blade file
    'laravelBlockerBladeExtended'   => env('LARAVEL_BLOCKER_BLADE_EXTENDED', 'layouts.app'),

    // Switch Between bootstrap 3 `panel` and bootstrap 4 `card` classes
    'blockerBootstapVersion'        => env('LARAVEL_BLOCKER_BOOTSTRAP_VERSION', '4'),

    // Additional Card classes for styling -
    // See: https://getbootstrap.com/docs/4.0/components/card/#background-and-color
    // Example classes: 'text-white bg-primary mb-3'
    'blockerBootstrapCardClasses'   => env('LARAVEL_BLOCKER_CARD_CLASSES', ''),

    // Blade Extension Placement
    'blockerBladePlacement'         => env('LARAVEL_BLOCKER_BLADE_PLACEMENT', 'yield'),
    'blockerBladePlacementCss'      => env('LARAVEL_BLOCKER_BLADE_PLACEMENT_CSS', 'template_linked_css'),
    'blockerBladePlacementJs'       => env('LARAVEL_BLOCKER_BLADE_PLACEMENT_JS', 'footer_scripts'),

    // jQuery
    'enablejQueryCDN'               => env('LARAVEL_BLOCKER_JQUERY_CDN_ENABLED', true),
    'JQueryCDN'                     => env('LARAVEL_BLOCKER_JQUERY_CDN_URL', 'https://code.jquery.com/jquery-3.2.1.slim.min.js'),

    // Font Awesome
    'blockerEnableFontAwesomeCDN'   => env('LARAVEL_BLOCKER_FONT_AWESOME_CDN_ENABLED', true),
    'blockerFontAwesomeCDN'         => env('LARAVEL_BLOCKER_FONT_AWESOME_CDN_URL', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'),

    /*
    |--------------------------------------------------------------------------
    | Laravel Blocker Auth & Roles Settings
    |--------------------------------------------------------------------------
    */
    // Enable `auth` middleware
    'authEnabled'                   => env('LARAVEL_BLOCKER_AUTH_ENABLED', true),

    // Enable Optional Roles Middleware
    'rolesEnabled'                  => env('LARAVEL_BLOCKER_ROLES_ENABLED', false),

    // Optional Roles Middleware
    'rolesMiddlware'                => env('LARAVEL_BLOCKER_ROLES_MIDDLWARE', 'role:admin'),

    /*
    |--------------------------------------------------------------------------
    | Laravel Blocker Pagination Settings
    |--------------------------------------------------------------------------
    */
    'blockerPaginationEnabled'       => env('LARAVEL_BLOCKER_PAGINATION_ENABLED', true),
    'blockerPaginationPerPage'       => env('LARAVEL_BLOCKER_PAGINATION_PER_PAGE', 25),

    // Enable Search Blocked - Uses jQuery Ajax
    'enableSearchBlocked'            => env('LARAVEL_BLOCKER_SEARCH_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Laravel Blocker Databales Settings - Not recommended with pagination.
    |--------------------------------------------------------------------------
    */
    'blockerDatatables'             => env('LARAVEL_BLOCKER_DATATABLES_ENABLED', false),
    'enabledDatatablesJs'           => env('LARAVEL_BLOCKER_DATATABLES_JS_ENABLED', false),
    'datatablesJsStartCount'        => 25,
    'datatablesCssCDN'              => 'https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css',
    'datatablesJsCDN'               => 'https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js',
    'datatablesJsPresetCDN'         => 'https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js',
];
