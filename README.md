# LaravelBlocker

[![Latest Stable Version](https://poser.pugx.org/jeremykenedy/laravel-blocker/v/stable)](https://packagist.org/packages/jeremykenedy/laravel-blocker)
[![Total Downloads](https://poser.pugx.org/jeremykenedy/laravel-blocker/downloads)](https://packagist.org/packages/jeremykenedy/laravel-blocker)
[![Latest Unstable Version](https://poser.pugx.org/jeremykenedy/laravel-blocker/v/unstable)](https://packagist.org/packages/jeremykenedy/laravel-blocker)
[![License](https://poser.pugx.org/jeremykenedy/laravel-blocker/license)](https://packagist.org/packages/jeremykenedy/laravel-blocker)

- [About](#about)
- [Features](#features)
- [Requirements](#requirements)
    - [Required Packages](#required-packages)
- [Installation Instructions](#installation-instructions)
    - [Publish All Assets](#publish-all-assets)
    - [Publish Specific Assets](#publish-specific-assets)
- [Usage](#usage)
- [Configuration](#configuration)
    - [Environment File](#environment-file)
- [Routes](#routes)
- [Screenshots](#screenshots)
- [File Tree](#file-tree)
- [License](#license)

### About
Larave Blocker (LaravelBlocker) is a middleware interface to block users, emails, ip addresses, domain names, cities, states, countries, continents, and regions from using your application, logging in, or registering. The types of items to be blocked can be extended to what you think via a seed. The items you are blocking have a CRUD interface along with a softdeletes interface.

Can work out the box with or without the following roles packages:
* [jeremykenedy/laravel-roles](https://github.com/jeremykenedy/laravel-roles)
* [spatie/laravel-permission](https://github.com/spatie/laravel-permission)
* [Zizaco/entrust](https://github.com/Zizaco/entrust)
* [romanbican/roles](https://github.com/romanbican/roles)
* [ultraware/roles](https://github.com/ultraware/roles)

### Features
| LaravelBlocker Features  |
| :------------ |
|Easy to use middlware that can be applied directly to controller and/or routes|
|Full CRUD (Create, Read, Update, Delete) interface for adding blocked items|
|Lots of easily customizable options through .env file variables|
|Seeded blocked types with ability to add own published seeds|
|Seeded blocked items with ability to add own published seeds|
|Softdeletes with easy to use restore and destroy interface|
|Uses [laravelcollective/html](https://github.com/LaravelCollective/html) package for secure HTML forms|
|Uses [eklundkristoffer/seedster](https://github.com/eklundkristoffer/seedster) for optional default seeds|
|Makes use of proper custom request classes structure|
|Can use pagination if desired for dashboards|
|Front end Bootstrap version can be changed|
|Ajax search for blocked items|
|Uses [localization](https://laravel.com/docs/5.8/localization) language files|

### Requirements
* [Laravel 5.1, 5.2, 5.3, 5.4, 5.5, 5.6, 5.7, or 5.8+](https://laravel.com/docs/installation)

#### Required Packages
(included in this package)
* [laravelcollective/html](https://packagist.org/packages/laravelcollective/html)
* [eklundkristoffer/seedster](https://github.com/eklundkristoffer/seedster)

### Installation Instructions
1. From your projects root folder in terminal run:
```bash
    composer require jeremykenedy/laravel-blocker
```

2. Register the package

* Laravel 5.5 and up
Uses package auto discovery feature, no need to edit the `config/app.php` file.

* Laravel 5.4 and below
Register the package with laravel in `config/app.php` under `providers` with the following:

    ```php
        'providers' => [
            Collective\Html\HtmlServiceProvider::class,
            jeremykenedy\LaravelBlocker\LaravelBlockerServiceProvider::class,,
        ];
    ```

In `config/app.php` section under `aliases` with the following:

    ```
        'Form' => Collective\Html\FormFacade::class,
        'Html' => Collective\Html\HtmlFacade::class,
    ```

3. Publish the packages views, config file, assets, and language files by running the following from your projects root folder:

#### Publish All Assets
```bash
    php artisan vendor:publish --provider="jeremykenedy\LaravelBlocker\LaravelBlockerServiceProvider"
```

#### Publish Specific Assets
```bash
    php artisan vendor:publish --tag=laravelblocker-config
    php artisan vendor:publish --tag=laravelblocker-views
    php artisan vendor:publish --tag=laravelblocker-lang
    php artisan vendor:publish --tag=laravelblocker-migrations
    php artisan vendor:publish --tag=laravelblocker-seeds
```

### Usage

##### From Route File:
* You can include the `checkblocked` in a route groups or on individual routes.

###### Route Group Example:

```php
    Route::group(['middleware' => ['web', 'checkblocked']], function () {
        Route::get('/', 'WelcomeController@welcome');
    });
```

###### Individual Route Examples:

```php
    Route::get('/', 'WelcomeController@welcome')->middleware('checkblocked');
    Route::match(['post'], '/test', 'Testing\TestingController@runTest')->middleware('checkblocked');
```

##### From Controller File:
* You can include the `checkblocked` in the contructor of your controller file.

###### Controller File Example:

```php
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       $this->middleware('checkblocked');
    }
```

### Configuration
There are many configurable options which have all been extended to be able to configured via `.env` file variables. Editing the configuration file directly is not needed becuase of this.

* See config file: [laravelblocker.php](https://github.com/jeremykenedy/LaravelBlocker/blob/development/src/config/laravelblocker.php).
* See default Types Seed: [DefaultBlockedTypeTableSeeder.php](https://github.com/jeremykenedy/LaravelBlocker/blob/development/src/database/seeds/DefaultBlockedTypeTableSeeder.php)
* See default Blocked Items seed: [DefaultBlockedItemsTableSeeder.php](https://github.com/jeremykenedy/LaravelBlocker/blob/development/src/database/seeds/DefaultBlockedItemsTableSeeder.php)

##### Environment File
```
# Laravel Blocker Core Setting
LARAVEL_BLOCKER_ENABLED=true

# Laravel Blocker Database Settings
LARAVEL_BLOCKER_DATABASE_CONNECTION='mysql'
LARAVEL_BLOCKER_DATABASE_TABLE='laravel_blocker'
LARAVEL_BLOCKER_TYPE_DATABASE_TABLE='laravel_blocker_types'
LARAVEL_BLOCKER_SEED_DEFAULT_TYPES=true
LARAVEL_BLOCKER_SEED_DEFAULT_ITEMS=true
LARAVEL_BLOCKER_TYPES_SEED_PUBLISHED=true
LARAVEL_BLOCKER_ITEMS_SEED_PUBLISHED=true

# Laravel Default User Model
LARAVEL_BLOCKER_USER_MODEL='App\User'

# Laravel Blocker Front End Settings
LARAVEL_BLOCKER_BLADE_EXTENDED='layouts.app'
LARAVEL_BLOCKER_TITLE_EXTENDED='template_title'
LARAVEL_BLOCKER_BOOTSTRAP_VERSION='4'
LARAVEL_BLOCKER_CARD_CLASSES=''
LARAVEL_BLOCKER_BLADE_PLACEMENT='yield'
LARAVEL_BLOCKER_BLADE_PLACEMENT_CSS='template_linked_css'
LARAVEL_BLOCKER_BLADE_PLACEMENT_JS='footer_scripts'
LARAVEL_BLOCKER_JQUERY_CDN_ENABLED=true
LARAVEL_BLOCKER_JQUERY_CDN_URL='https://code.jquery.com/jquery-3.2.1.slim.min.js'
LARAVEL_BLOCKER_FONT_AWESOME_CDN_ENABLED=true
LARAVEL_BLOCKER_FONT_AWESOME_CDN_URL='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'
LARAVEL_BLOCKER_TOOLTIPS_ENABLED=true
LARAVEL_BLOCKER_JQUERY_IP_MASK_ENABLED=true
LARAVEL_BLOCKER_JQUERY_IP_MASK_CDN='https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js'
LARAVEL_BLOCKER_FLASH_MESSAGES_ENABLED=true
LARAVEL_BLOCKER_SEARCH_ENABLED=true

# Laravel Blocker Auth & Roles Settings
LARAVEL_BLOCKER_AUTH_ENABLED=true
LARAVEL_BLOCKER_ROLES_ENABLED=false
LARAVEL_BLOCKER_ROLES_MIDDLWARE='role:admin'

# Laravel Blocker Pagination Settings
LARAVEL_BLOCKER_PAGINATION_ENABLED=false
LARAVEL_BLOCKER_PAGINATION_PER_PAGE=25

# Laravel Blocker Databales Settings - Not recommended with pagination.
LARAVEL_BLOCKER_DATATABLES_ENABLED=false
LARAVEL_BLOCKER_DATATABLES_JS_ENABLED=false
LARAVEL_BLOCKER_DATATABLES_JS_START_COUNT=25
LARAVEL_BLOCKER_DATATABLES_CSS_CDN='https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css'
LARAVEL_BLOCKER_DATATABLES_JS_CDN='https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js'
LARAVEL_BLOCKER_DATATABLES_JS_PRESET_CDN='https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js'

# Laravel Blocker Actions Options
LARAVEL_BLOCKER_DEFAULT_ACTION='abort'
LARAVEL_BLOCKER_DEFAULT_ACTION_ABORT_TYPE='403'
LARAVEL_BLOCKER_DEFAULT_ACTION_VIEW='welcome'
LARAVEL_BLOCKER_DEFAULT_ACTION_REDIRECT='/'
```

### Routes
* ```/blocker```
* ```/blocker/{id}```
* ```/blocker/create```
* ```/blocker/{id}/edit```
* ```/blocker-deleted```
* ```/blocker-deleted/{id}```
* ```/blocker-deleted/{id}```

###### Routes In-depth
```
+--------+----------------------------------------+---------------------------------------+---------------------------------------------+---------------------------------------------------------------------------------------------------------+--------------------------------------------------------------+
| Domain | Method                                 | URI                                   | Name                                        | Action                                                                                                  | Middleware                                                   |
+--------+----------------------------------------+---------------------------------------+---------------------------------------------+---------------------------------------------------------------------------------------------------------+--------------------------------------------------------------+
|        | GET|HEAD                               | blocker                               | laravelblocker::blocker.index               | jeremykenedy\LaravelBlocker\App\Http\Controllers\LaravelBlockerController@index                         | web,checkblocked,auth                                        |
|        | POST                                   | blocker                               | laravelblocker::blocker.store               | jeremykenedy\LaravelBlocker\App\Http\Controllers\LaravelBlockerController@store                         | web,checkblocked,auth                                        |
|        | GET|HEAD                               | blocker-deleted                       | laravelblocker::blocker-deleted             | jeremykenedy\LaravelBlocker\App\Http\Controllers\LaravelBlockerDeletedController@index                  | web,checkblocked,auth                                        |
|        | DELETE                                 | blocker-deleted-destroy-all           | laravelblocker::destroy-all-blocked         | jeremykenedy\LaravelBlocker\App\Http\Controllers\LaravelBlockerDeletedController@destroyAllItems        | web,checkblocked,auth                                        |
|        | POST                                   | blocker-deleted-restore-all           | laravelblocker::blocker-deleted-restore-all | jeremykenedy\LaravelBlocker\App\Http\Controllers\LaravelBlockerDeletedController@restoreAllBlockedItems | web,checkblocked,auth                                        |
|        | DELETE                                 | blocker-deleted/{id}                  | laravelblocker::blocker-item-destroy        | jeremykenedy\LaravelBlocker\App\Http\Controllers\LaravelBlockerDeletedController@destroy                | web,checkblocked,auth                                        |
|        | PUT                                    | blocker-deleted/{id}                  | laravelblocker::blocker-item-restore        | jeremykenedy\LaravelBlocker\App\Http\Controllers\LaravelBlockerDeletedController@restoreBlockedItem     | web,checkblocked,auth                                        |
|        | GET|HEAD                               | blocker-deleted/{id}                  | laravelblocker::blocker-item-show-deleted   | jeremykenedy\LaravelBlocker\App\Http\Controllers\LaravelBlockerDeletedController@show                   | web,checkblocked,auth                                        |
|        | GET|HEAD                               | blocker/create                        | laravelblocker::blocker.create              | jeremykenedy\LaravelBlocker\App\Http\Controllers\LaravelBlockerController@create                        | web,checkblocked,auth                                        |
|        | DELETE                                 | blocker/{blocker}                     | laravelblocker::blocker.destroy             | jeremykenedy\LaravelBlocker\App\Http\Controllers\LaravelBlockerController@destroy                       | web,checkblocked,auth                                        |
|        | PUT|PATCH                              | blocker/{blocker}                     | laravelblocker::blocker.update              | jeremykenedy\LaravelBlocker\App\Http\Controllers\LaravelBlockerController@update                        | web,checkblocked,auth                                        |
|        | GET|HEAD                               | blocker/{blocker}                     | laravelblocker::blocker.show                | jeremykenedy\LaravelBlocker\App\Http\Controllers\LaravelBlockerController@show                          | web,checkblocked,auth                                        |
|        | GET|HEAD                               | blocker/{blocker}/edit                | laravelblocker::blocker.edit                | jeremykenedy\LaravelBlocker\App\Http\Controllers\LaravelBlockerController@edit                          | web,checkblocked,auth                                        |
|        | POST                                   | search-blocked                        | laravelblocker::search-blocked              | jeremykenedy\LaravelBlocker\App\Http\Controllers\LaravelBlockerController@search                        | web,checkblocked,auth                                        |
|        | POST                                   | search-blocked-deleted                | laravelblocker::search-blocked-deleted      | jeremykenedy\LaravelBlocker\App\Http\Controllers\LaravelBlockerDeletedController@search                 | web,checkblocked,auth                                        |
+--------+----------------------------------------+---------------------------------------+---------------------------------------------+---------------------------------------------------------------------------------------------------------+--------------------------------------------------------------+
```

### Screenshots
![NAME](url)

### File Tree
```bash
├── .gitignore
├── LICENSE
├── README.md
├── composer.json
└── src
    ├── App
    │   ├── Http
    │   │   ├── Controllers
    │   │   │   ├── LaravelBlockerController.php
    │   │   │   └── LaravelBlockerDeletedController.php
    │   │   ├── Middleware
    │   │   │   └── LaravelBlocker.php
    │   │   └── Requests
    │   │       ├── SearchBlockerRequest.php
    │   │       ├── StoreBlockerRequest.php
    │   │       └── UpdateBlockerRequest.php
    │   ├── Models
    │   │   ├── BlockedItem.php
    │   │   └── BlockedType.php
    │   ├── Rules
    │   │   └── UniqueBlockerItemValueEmail.php
    │   └── Traits
    │       ├── IpAddressDetails.php
    │       └── LaravelCheckBlockedTrait.php
    ├── LaravelBlockerFacade.php
    ├── LaravelBlockerServiceProvider.php
    ├── config
    │   └── laravelblocker.php
    ├── database
    │   ├── migrations
    │   │   ├── 2019_02_19_032636_create_laravel_blocker_types_table.php
    │   │   └── 2019_02_19_045158_create_laravel_blocker_table.php
    │   └── seeds
    │       ├── DefaultBlockedItemsTableSeeder.php
    │       ├── DefaultBlockedTypeTableSeeder.php
    │       └── publish
    │           ├── BlockedItemsTableSeeder.php
    │           └── BlockedTypeTableSeeder.php
    ├── resources
    │   ├── lang
    │   │   └── en
    │   │       └── laravelblocker.php
    │   └── views
    │       ├── forms
    │       │   ├── create-new.blade.php
    │       │   ├── delete-full.blade.php
    │       │   ├── delete-item.blade.php
    │       │   ├── delete-sm.blade.php
    │       │   ├── destroy-all.blade.php
    │       │   ├── destroy-full.blade.php
    │       │   ├── destroy-sm.blade.php
    │       │   ├── edit-form.blade.php
    │       │   ├── partials
    │       │   │   ├── item-blocked-user-select.blade.php
    │       │   │   ├── item-note-input.blade.php
    │       │   │   ├── item-type-select.blade.php
    │       │   │   └── item-value-input.blade.php
    │       │   ├── restore-all.blade.php
    │       │   ├── restore-item.blade.php
    │       │   └── search-blocked.blade.php
    │       ├── laravelblocker
    │       │   ├── create.blade.php
    │       │   ├── deleted
    │       │   │   └── index.blade.php
    │       │   ├── edit.blade.php
    │       │   ├── index.blade.php
    │       │   └── show.blade.php
    │       ├── modals
    │       │   └── confirm-modal.blade.php
    │       ├── partials
    │       │   ├── blocked-items-table.blade.php
    │       │   ├── bs-visibility-css.blade.php
    │       │   ├── flash-messages.blade.php
    │       │   ├── form-status.blade.php
    │       │   └── styles.blade.php
    │       └── scripts
    │           ├── blocked-form.blade.php
    │           ├── confirm-modal.blade.php
    │           ├── datatables.blade.php
    │           ├── search-blocked.blade.php
    │           └── tooltips.blade.php
    └── routes
        └── web.php
```

* Tree command can be installed using brew: `brew install tree`
* File tree generated using command `tree -a -I '.git|node_modules|vendor|storage|tests'`

### License
LaravelBlocker is licensed under the MIT license. Enjoy!
