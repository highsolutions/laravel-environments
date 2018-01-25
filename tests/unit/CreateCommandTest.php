<?php

namespace HighSolutions\LaravelEnvironments\Test;

use HighSolutions\LaravelEnvironments\Commands\CreateEnvironmentCommand;
use HighSolutions\LaravelEnvironments\Test\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class CreateCommandTest extends TestCase
{
    protected function executeCreate(array $params): bool
    {
        return Artisan::call('env:create', $params);
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
        ]);

        $this->assertTrue(File::exists($testFile));
    }

    /** @test */
    public function overwrite_existing_environment_when_intend_to()
    {
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
}
