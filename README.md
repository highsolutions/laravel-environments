Laravel Environments ![CircleCI](https://circleci.com/gh/highsolutions/laravel-environments.svg?style=svg) ![StyleCI](https://styleci.io/repos/118597081/shield?branch=master)
================

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
        "HighSolutions/laravel-environments": "1.*"
    }
```

Optionally, publish the configuration file if you want to change any defaults:

```bash
php artisan vendor:publish --provider="HighSolutions\LaravelEnvironments\EnvironmentServiceProvider"
```

Run `composer update` to install the package.

Configuration
------------

| Name                             | Description                                                                                | Default                                              |
|----------------------------------|--------------------------------------------------------------------------------------------|------------------------------------------------------|
| path                             | Path where environments will be stored                                                     | environments/                                        |
| files                            | Files that will be stored for each environment                                             | [   '.env',   'phpunit.xml',   'public/.htaccess', ] |
| clear_directory_when_overwriting | If set to true, overwriting environment will be cleared out before putting new files there | false                                                |


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

1.6.0
* Laravel 5.6 support

1.5.0
* Create, Copy, Remove, Set, List commands
* Unit tests
* Laravel 5.5 Support

Credits
-------

This package is developed by [HighSolutions](https://highsolutions.org), software house from Poland in love in Laravel.
