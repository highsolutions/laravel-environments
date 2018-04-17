<?php

namespace HighSolutions\LaravelEnvironments\Test;

use Illuminate\Support\Facades\File;

class SetCommandTest extends TestCase
{
    protected function executeCreate($params)
    {
        return $this->artisan('env:create', $params);
    }

    protected function executeSet($params)
    {
        return $this->artisan('env:set', $params);
    }

    /** @test */
    public function set_empty_environment()
    {
        $this->executeCreate([
            'name' => 'local',
        ]);

        $this->assertDirectoryExists('local');

        $this->executeSet([
            'name' => 'local',
        ]);
    }

    /** @test **/
    public function set_environment_with_one_file()
    {
        config([
            'laravel-environments.files' => ['.env'],
        ]);

        File::put($this->getBaseDirectory('.env'), 'env content');

        $this->executeCreate([
            'name' => 'local',
        ]);

        File::put($this->getTempDirectory('local').'.env', 'env new content');

        $this->executeSet([
            'name' => 'local',
        ]);

        tap($this->getBaseDirectory('.env'), function ($file) {
            $this->assertTrue(File::exists($file));
            $this->assertEquals('env new content', File::get($file));
        });
    }

    /** @test **/
    public function set_environment_with_multiple_files()
    {
        config([
            'laravel-environments.files' => [
                '.env',
                'phpunit.xml',
                'public/.htaccess',
            ],
        ]);

        File::put($this->getBaseDirectory('.env'), 'env content');
        File::put($this->getBaseDirectory('phpunit.xml'), 'phpunit content');
        File::put($this->getBaseDirectory('public/.htaccess'), 'htaccess content');

        $this->executeCreate([
            'name' => 'local',
        ]);

        File::put($this->getTempDirectory('local').'.env', 'env new content');
        File::put($this->getTempDirectory('local').'phpunit.xml', 'phpunit new content');
        File::put($this->getTempDirectory('local').'public/.htaccess', 'htaccess new content');

        $this->executeSet([
            'name' => 'local',
        ]);

        tap($this->getBaseDirectory('.env'), function ($file) {
            $this->assertTrue(File::exists($file));
            $this->assertEquals('env new content', File::get($file));
        });

        tap($this->getBaseDirectory('phpunit.xml'), function ($file) {
            $this->assertTrue(File::exists($file));
            $this->assertEquals('phpunit new content', File::get($file));
        });

        tap($this->getBaseDirectory('public/.htaccess'), function ($file) {
            $this->assertTrue(File::exists($file));
            $this->assertEquals('htaccess new content', File::get($file));
        });
    }

    /** @test **/
    public function sequentional_environment_activation()
    {
        config([
            'laravel-environments.files' => ['.env'],
        ]);

        File::put($this->getBaseDirectory('.env'), 'env content');

        $this->executeCreate([
            'name' => 'local',
        ]);

        File::put($this->getTempDirectory('local').'.env', 'env local content');

        $this->executeCreate([
            'name' => 'staging',
        ]);

        File::put($this->getTempDirectory('staging').'.env', 'env staging content');

        $this->executeSet([
            'name' => 'local',
        ]);

        tap($this->getBaseDirectory('.env'), function ($file) {
            $this->assertTrue(File::exists($file));
            $this->assertEquals('env local content', File::get($file));
        });

        $this->executeSet([
            'name' => 'staging',
        ]);

        tap($this->getBaseDirectory('.env'), function ($file) {
            $this->assertTrue(File::exists($file));
            $this->assertEquals('env staging content', File::get($file));
        });
    }
}
