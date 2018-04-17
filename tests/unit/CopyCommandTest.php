<?php

namespace HighSolutions\LaravelEnvironments\Test;

use Illuminate\Support\Facades\File;

class CopyCommandTest extends TestCase
{
    protected function executeCreate($params)
    {
        return $this->artisan('env:create', $params);
    }

    protected function executeCopy($params)
    {
        return $this->artisan('env:copy', $params);
    }

    /** @test */
    public function copy_existing_environment()
    {
        $this->executeCreate([
            'name' => 'local',
        ]);

        $this->assertDirectoryExists('local');

        $this->executeCopy([
            'old' => 'local',
            'new' => 'production',
        ]);

        $this->assertDirectoryExists('production');
    }

    /** @test */
    public function not_copy_not_existing_environment()
    {
        $this->assertDirectoryNotExists('local');

        $this->executeCopy([
            'old' => 'local',
            'new' => 'production',
        ]);

        $this->assertDirectoryNotExists('production');
    }

    /** @test */
    public function not_copy_when_has_to_overwrite_not_intended()
    {
        $this->executeCreate([
            'name' => 'local',
        ]);

        $this->executeCreate([
            'name' => 'staging',
        ]);

        $testFile = $this->getTempDirectory('staging').'testfile.php';
        File::put($testFile, 'test');

        $this->executeCopy([
            'old' => 'local',
            'new' => 'staging',
            '--overwrite' => false,
        ]);

        $this->assertDirectoryExists('staging');

        $this->assertTrue(File::exists($testFile));
    }

    /** @test */
    public function copy_when_has_to_overwrite_when_intended()
    {
        $this->executeCreate([
            'name' => 'local',
        ]);

        $this->executeCreate([
            'name' => 'staging',
        ]);

        $testFile = $this->getTempDirectory('staging').'testfile.php';
        File::put($testFile, 'test');

        $this->executeCopy([
            'old' => 'local',
            'new' => 'staging',
            '--overwrite' => true,
        ]);

        $this->assertDirectoryExists('staging');

        $this->assertFalse(File::exists($testFile));
    }

    /** @test **/
    public function copy_environment_with_nested_files()
    {
        $this->executeCreate([
            'name' => 'local',
        ]);

        File::put($this->getTempDirectory('local').'.env', 'env content');
        File::put($this->getTempDirectory('local').'phpunit.xml', 'phpunit content');
        File::put($this->getTempDirectory('local').'public/.htaccess', 'htaccess content');

        $this->executeCopy([
            'old' => 'local',
            'new' => 'staging',
        ]);

        tap($this->getTempDirectory('staging').'.env', function ($file) {
            $this->assertTrue(File::exists($file));
            $this->assertEquals('env content', File::get($file));
        });

        tap($this->getTempDirectory('staging').'phpunit.xml', function ($file) {
            $this->assertTrue(File::exists($file));
            $this->assertEquals('phpunit content', File::get($file));
        });

        tap($this->getTempDirectory('staging').'public/.htaccess', function ($file) {
            $this->assertTrue(File::exists($file));
            $this->assertEquals('htaccess content', File::get($file));
        });
    }
}
