<?php

namespace HighSolutions\LaravelEnvironments\Test;

use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use HighSolutions\LaravelEnvironments\EnvironmentServiceProvider;

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
        return str_finish(__DIR__.DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR.$anotherDirectory, DIRECTORY_SEPARATOR);
    }

    public function getBaseDirectory($file = '')
    {
        $path = $this->basePath();
        $file = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $file);
        $filename = substr($file, strpos($file, DIRECTORY_SEPARATOR));
        $dirPath = str_before($file, $filename);
        if (! File::exists($path.$dirPath)) {
            File::makeDirectory($path.$dirPath, 0755, true, true);
        }

        return $path.$dirPath.$filename;
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

    protected function assertNestedArrayContains($search, $array)
    {
        $found = false;

        foreach ($array as $rows) {
            if (is_array($rows)) {
                foreach ($rows as $col) {
                    if ($col == $search) {
                        $found = true;
                    }
                }
            } else {
                if ($rows == $search) {
                    $found = true;
                }
            }
        }

        if ($found) {
            $this->assertTrue(true);

            return;
        }

        $this->fail("Failed asserting that an array contains '$search'.");
    }
}
