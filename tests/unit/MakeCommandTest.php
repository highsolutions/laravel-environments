<?php

namespace HighSolutions\LaravelEnvironments\Test;

use Illuminate\Support\Facades\File;

class MakeCommandTest extends TestCase
{
    protected function executeCreate($params)
    {
        return $this->artisan('make:env', $params);
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
    public function overwrite_existing_environment_by_default()
    {
        config([
            'laravel-environments.clear_directory_when_overwriting' => true,
        ]);

        $this->executeCreate([
            'name' => 'local',
        ]);

        $testFile = $this->getTempDirectory('local').'testfile.php';
        File::put($testFile, 'test');

        $this->assertDirectoryExists('local');

        $this->executeCreate([
            'name' => 'local',
        ]);

        $this->assertFalse(File::exists($testFile));
    }
}
