<?php

namespace HighSolutions\LaravelEnvironments\Test;

use HighSolutions\LaravelEnvironments\Commands\CreateEnvironmentCommand;
use HighSolutions\LaravelEnvironments\Test\TestCase;
use Illuminate\Support\Facades\File;

class CreateCommandTest extends TestCase
{
    protected function executeCreate($params)
    {
        return $this->artisan('env:create', $params);
    }

    /** @test */
    public function create_new_environment()
    {
        $code = $this->executeCreate([
            'name' => 'local',
        ]);

        $this->assertEquals(0, $code);
        $this->assertDirectoryExists('local');
    }

    /** @test */
    public function create_two_environments()
    {
        $this->executeCreate([
            'name' => 'local',
        ]);

        $this->executeCreate([
            'name' => 'staging',
        ]);

        $this->assertDirectoryExists('local');
        $this->assertDirectoryExists('staging');
    }

    /** @test */
    public function not_overwrite_existing_environment()
    {
        $this->executeCreate([
            'name' => 'local',
        ]);

        $testFile = $this->getTempDirectory('local') . 'testfile.php';
        File::put($testFile, 'test');

        $this->assertDirectoryExists('local');

        $code = $this->executeCreate([
            'name' => 'local',
            '--overwrite' => false,
        ]);

        $this->assertTrue(File::exists($testFile));
    }

    /** @test */
    public function overwrite_existing_environment_when_intend_to()
    {
        config([
            'laravel-environments.clear_directory_when_overwriting' => true,
        ]);

        $this->executeCreate([
            'name' => 'local',
        ]);

        $testFile = $this->getTempDirectory('local') . 'testfile.php';
        File::put($testFile, 'test');

        $this->assertDirectoryExists('local');

        $code = $this->executeCreate([
            'name' => 'local',
            '--overwrite' => true,
        ]);

        $this->assertFalse(File::exists($testFile));
    }

    /** @test */
    public function overwrite_existing_environment_when_intend_to_but_not_delete_stored_filed_before()
    {
        config([
            'laravel-environments.clear_directory_when_overwriting' => false,
        ]);

        $this->executeCreate([
            'name' => 'local',
        ]);

        $testFile = $this->getTempDirectory('local') . 'testfile.php';
        File::put($testFile, 'test');

        $this->assertDirectoryExists('local');

        $code = $this->executeCreate([
            'name' => 'local',
            '--overwrite' => true,
        ]);

        $this->assertTrue(File::exists($testFile));
    }

    /** @test **/
    public function created_environment_contains_copied_file()
    {
        config([
            'laravel-environments.files' => ['.env']
        ]);

        File::put($this->getBaseDirectory('.env'), 'env content');

        $this->executeCreate([
            'name' => 'local',
        ]);

        tap($this->getTempDirectory('local') . '.env', function ($file) {
            $this->assertTrue(File::exists($file));
            $this->assertEquals('env content', File::get($file));
        });
    }

    /** @test **/
    public function created_environment_without_overwriting_contains_old_version_of_file()
    {
        config([
            'laravel-environments.files' => ['.env']
        ]);

        File::put($this->getBaseDirectory('.env'), 'env content');

        $this->executeCreate([
            'name' => 'local',
        ]);

        tap($this->getTempDirectory('local') . '.env', function ($file) {
            $this->assertTrue(File::exists($file));
            $this->assertEquals('env content', File::get($file));
        });

        File::put($this->getBaseDirectory('.env'), 'env new content');

        $this->executeCreate([
            'name' => 'local',
            '--overwrite' => false,
        ]);

        tap($this->getTempDirectory('local') . '.env', function ($file) {
            $this->assertTrue(File::exists($file));
            $this->assertEquals('env content', File::get($file));
        });
    }

    /** @test **/
    public function created_environment_with_overwriting_contains_new_version_of_file()
    {
        config([
            'laravel-environments.files' => ['.env']
        ]);

        File::put($this->getBaseDirectory('.env'), 'env content');

        $this->executeCreate([
            'name' => 'local',
        ]);

        tap($this->getTempDirectory('local') . '.env', function ($file) {
            $this->assertTrue(File::exists($file));
            $this->assertEquals('env content', File::get($file));
        });

        File::put($this->getBaseDirectory('.env'), 'env new content');

        $this->executeCreate([
            'name' => 'local',
            '--overwrite' => true,
        ]);

        tap($this->getTempDirectory('local') . '.env', function ($file) {
            $this->assertTrue(File::exists($file));
            $this->assertEquals('env new content', File::get($file));
        });
    }

    /** @test **/
    public function created_environment_contains_copied_files()
    {
        config([
            'laravel-environments.files' => [
                '.env',
                'phpunit.xml',
                'public/.htaccess',
            ]
        ]);

        File::put($this->getBaseDirectory('.env'), 'env content');
        File::put($this->getBaseDirectory('phpunit.xml'), 'phpunit content');
        File::put($this->getBaseDirectory('public/.htaccess'), 'htaccess content');

        $this->executeCreate([
            'name' => 'local',
        ]);

        tap($this->getTempDirectory('local') . '.env', function ($file) {
            $this->assertTrue(File::exists($file));
            $this->assertEquals('env content', File::get($file));
        });

        tap($this->getTempDirectory('local') . 'phpunit.xml', function ($file) {
            $this->assertTrue(File::exists($file));
            $this->assertEquals('phpunit content', File::get($file));
        });

        tap($this->getTempDirectory('local') . 'public/.htaccess', function ($file) {
            $this->assertTrue(File::exists($file));
            $this->assertEquals('htaccess content', File::get($file));
        });
    }
}
