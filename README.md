# LaravelBlocker

[![Latest Stable Version](https://poser.pugx.org/jeremykenedy/laravelBlocker/v/stable)](https://packagist.org/packages/jeremykenedy/laravelBlocker)
[![Total Downloads](https://poser.pugx.org/jeremykenedy/laravelBlocker/downloads)](https://packagist.org/packages/jeremykenedy/laravelBlocker)
[![Latest Unstable Version](https://poser.pugx.org/jeremykenedy/laravelBlocker/v/unstable)](https://packagist.org/packages/jeremykenedy/laravelBlocker)
[![License](https://poser.pugx.org/jeremykenedy/laravelBlocker/license)](https://packagist.org/packages/jeremykenedy/laravelBlocker)

- [About](#about)
- [Features](#features)
- [Requirements](#requirements)
- [Installation Instructions](#installation-instructions)
    - [Publish All Assets](#publish-all-assets)
    - [Publish Specific Assets](#publish-specific-assets)
- [Configuration](#configuration)
    - [Environment File](#environment-file)
- [Usage](#usage)
- [Screenshots](#screenshots)
- [File Tree](#file-tree)
- [License](#license)

### About

### Features
| LaravelBlocker Features  |
| :------------ |
||
|Uses localized language files|

### Requirements
* [Laravel 5.1, 5.2, 5.3, 5.4, or 5.5+](https://laravel.com/docs/installation)

### Installation Instructions
1. From your projects root folder in terminal run:
```bash
    composer require jeremykenedy/laravelBlocker
```

2. Register the package

* Laravel 5.5 and up
Uses package auto discovery feature, no need to edit the `config/app.php` file.

* Laravel 5.4 and below
Register the package with laravel in `config/app.php` under `providers` with the following:

```php
    'providers' => [
        jeremykenedy\LaravelBlocker\LaravelBlockerServiceProvider::class,,
    ];
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
```

### Configuration


##### Environment File

### Usage

### Screenshots
![NAME](url)

### File Tree

```bash
├── .gitignore
├── LICENSE
├── README.md
├── composer.json
└── src
```

* Tree command can be installed using brew: `brew install tree`
* File tree generated using command `tree -a -I '.git|node_modules|vendor|storage|tests'`

### License
LaravelBlocker is licensed under the MIT license. Enjoy!
