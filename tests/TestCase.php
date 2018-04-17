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

        $app->setBasePath($this->basePath());
    }

    public function getTempDirectory($anotherDirectory = null)
    {        
        return str_finish(__DIR__ . DIRECTORY_SEPARATOR .  'temp' . DIRECTORY_SEPARATOR . $anotherDirectory, DIRECTORY_SEPARATOR);
    }

    public function getBaseDirectory($file = '')
    {
        $path = $this->basePath();
        $file = str_replace('/', '\\', $file);
        $filename = substr($file, strpos($file, '\\'));
        $dirPath = str_before($file, $filename);
        if(!File::exists($path . $dirPath))
            File::makeDirectory($path . $dirPath, 0755, true, true);
        return $path . $dirPath . $filename;
    }

    protected function basePath()
    {
        return $this->getTempDirectory('__base__');
    }

    public function setUp()
    {
        parent::setUp();
        
        File::cleanDirectory(config('laravel-environments.path'));
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
