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
        "HighSolutions/eloquent-sequence": "*"
    }
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

Testing
---------

Run the tests with:

``` bash
vendor/bin/phpunit
```

Changelog
---------

@TODO

Credits
-------

This package is developed by [HighSolutions](https://highsolutions.org), software house from Poland in love in Laravel.
