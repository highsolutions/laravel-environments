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
            'name' => 'local1',
        ]);

        $this->assertDirectoryExists('local1');

        $this->executeSet([
            'name' => 'local1',
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
            'name' => 'local2',
        ]);

        File::put($this->getTempDirectory('local2').'.env', 'env new content');

        $this->executeSet([
            'name' => 'local2',
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
            'name' => 'local3',
        ]);

        File::put($this->getTempDirectory('local3').'.env', 'env new content');
        File::put($this->getTempDirectory('local3').'phpunit.xml', 'phpunit new content');
        File::put($this->getTempDirectory('local3').'public/.htaccess', 'htaccess new content');

        $this->executeSet([
            'name' => 'local3',
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
            'name' => 'local4',
        ]);

        File::put($this->getTempDirectory('local4').'.env', 'env local content');

        $this->executeCreate([
            'name' => 'staging',
        ]);

        File::put($this->getTempDirectory('staging').'.env', 'env staging content');

        $this->executeSet([
            'name' => 'local4',
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

    /** @test */
    public function set_env_without_one_of_files_and_delete_this_missing_file()
    {
        config([
            'laravel-environments.files' => [
                '.env',
                'missing.php',
            ],
        ]);

        File::put($this->getBaseDirectory('.env'), 'env content');
        File::put($this->getBaseDirectory('missing.php'), 'missing content');

        tap($this->getBaseDirectory('missing.php'), function ($file) {
            $this->assertTrue(File::exists($file));
        });

        $this->executeCreate([
            'name' => 'local5',
        ]);

        tap($this->getTempDirectory('local5').'missing.php', function ($file) {
            File::delete($file);
            $this->assertFalse(File::exists($file));
        });

        $this->executeSet([
            'name' => 'local5',
        ]);

        tap($this->getBaseDirectory('missing.php'), function ($file) {
            $this->assertFalse(File::exists($file));
        });

        tap($this->getBaseDirectory('.env'), function ($file) {
            $this->assertTrue(File::exists($file));
            $this->assertEquals('env content', File::get($file));
        });
    }

    /** @test */
    public function set_env_without_one_of_files_and_not_delete_this_missing_file_because_of_config()
    {
        config([
            'laravel-environments.files' => [
                '.env',
                'missing.php',
            ],
        ]);

        config([
            'laravel-environments.keep_existing_file_when_missing' => true,
        ]);

        File::put($this->getBaseDirectory('.env'), 'env content');
        File::put($this->getBaseDirectory('missing.php'), 'missing content');

        tap($this->getBaseDirectory('missing.php'), function ($file) {
            $this->assertTrue(File::exists($file));
        });

        $this->executeCreate([
            'name' => 'local6',
        ]);

        tap($this->getTempDirectory('local6').'missing.php', function ($file) {
            File::delete($file);
            $this->assertFalse(File::exists($file));
        });

        $this->executeSet([
            'name' => 'local6',
        ]);

        tap($this->getBaseDirectory('missing.php'), function ($file) {
            $this->assertTrue(File::exists($file));
            $this->assertEquals('missing content', File::get($file));
        });
    }
}
