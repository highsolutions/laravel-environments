<?php

namespace HighSolutions\LaravelEnvironments\Test;

use HighSolutions\LaravelEnvironments\EnvironmentServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Illuminate\Support\Facades\File;

abstract class TestCase extends OrchestraTestCase
{

    protected function getPackageProviders($app)
    {
        return [
            EnvironmentServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('laravel-environments.path', $this->getTempDirectory());
    }

    public function getTempDirectory($anotherDirectory = null)
    {        
        return __DIR__ . DIRECTORY_SEPARATOR .  'temp' . DIRECTORY_SEPARATOR . $anotherDirectory . DIRECTORY_SEPARATOR;
    }

    public function tearDown()
    {
        File::cleanDirectory(config('laravel-environments.path'));
    }

    public static function assertDirectoryExists($directoryName, $message = '')
    {
        $temp = (new static)->getTempDirectory($directoryName);
        static::assertTrue(File::isDirectory($temp), $message);
    }
}
