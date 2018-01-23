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

    public function getTempDirectory(): string
    {
        return __DIR__.'/temp';
    }

    public function tearDown()
    {
        File::cleanDirectory(config('laravel-environments.path'));
    }
}
