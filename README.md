Laravel Environments
================

[![License: MIT](https://img.shields.io/badge/License-MIT-brightgreen.svg?style=flat-square)](https://opensource.org/licenses/MIT)

Easy management of different environments in Laravel projects.

![Laravel-Environments by HighSolutions](https://raw.githubusercontent.com/highsolutions/laravel-environments/master/intro.jpg)

Installation
------------

This package can be installed through Composer:

```bash
composer require highsolutions/laravel-environments
```

Or by adding the following line to the `require` section of your Laravel webapp's `composer.json` file:

```javascript
    "require": {
        "HighSolutions/laravel-environments": "3.*"
    }
```

And run `composer update` to install the package.

Then, if you are using Laravel <= 5.4, update `config/app.php` by adding an entry for the service provider:

```php
'providers' => [
    // ...
    HighSolutions\LaravelEnvironments\EnvironmentServiceProvider::class,
];
```

Optionally, publish the configuration file if you want to change any defaults:

```bash
php artisan vendor:publish --provider="HighSolutions\LaravelEnvironments\EnvironmentServiceProvider"
```

This will create new file `config/environments.php` with few configuration options for package.

Configuration
------------

| Name                             | Description                                                                                | Default                                              |
|----------------------------------|--------------------------------------------------------------------------------------------|------------------------------------------------------|
| path                             | Path where environments will be stored                                                     | environments/                                        |
| files                            | Files that will be stored for each environment                                             | [   '.env',   'phpunit.xml',   'public/.htaccess', ] |
| clear_directory_when_overwriting | If set to true, overwriting environment will be cleared out before putting new files there | false                                                |
| keep_existing_file_when_missing  | If set to true, existing file in base directory will be not deleted when this file is missing in environment set to active | false                                                |


Usage
------------

Create a new environment
========================

To create a new environment, just use `create` method:

```bash
    php artisan env:create NAME_OF_ENVIRONMENT
```

In case that another environment exists with the same name, you can force to overwrite it with `--overwrite` option:

```bash
    php artisan env:create local --overwrite
```

You can use also predefined `make:env` method to be more consistent with other Laravel commands (`--overwrite` option is enabled here):

```bash
    php artisan make:env NAME_OF_ENVIRONMENT
```

Set environment as active
========================

To copy files to main codebase, just use `set` method:

```bash
    php artisan env:set NAME_OF_ENVIRONMENT
```

Copy an environment
========================

To make a duplicate of existing environment, just use `copy` method:

```bash
    php artisan env:copy NAME_OF_EXISTING_ENVIRONMENT NAME_OF_NEW_ENVIRONMENT
```

In case that another environment exists with the same name, you can force to overwrite it with `--overwrite` option:

```bash
    php artisan env:copy old new --overwrite
```

Remove an environment
========================

To remove an environment, just use `remove` method:

```bash
    php artisan env:remove NAME_OF_ENVIRONMENT
```

List all environments
========================

To see a list of all environments, just use `list` method:

```bash
    php artisan env:list
```

Testing
---------

Run the tests with:

``` bash
vendor/bin/phpunit
```

Changelog
---------

3.4.0
* Laravel 10.0 support

3.3.0
* Laravel 9.0 support

3.2.0
* Laravel 8.0 support

3.1.0
* Laravel 7.0 support

3.0.0
* Laravel 5.8 and 6.0 support

2.2.0
* Change name of config file from `config/laravel-environments.php` to `config/environments.php`

2.1.0
* Removing files that are exist in base folder but not exist in environment being set to active

2.0.0
* Support for all Laravel 5.* versions (to-date)

1.6.0
* Laravel 5.6 support

1.5.0
* Create, Copy, Remove, Set, List commands
* Unit tests
* Laravel 5.5 Support

Credits
-------

This package is developed by [HighSolutions](https://highsolutions.org), software house from Poland in love in Laravel.
