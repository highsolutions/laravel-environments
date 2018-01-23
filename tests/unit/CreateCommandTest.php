<?php

namespace HighSolutions\LaravelEnvironments\Test;

use HighSolutions\LaravelEnvironments\Commands\CreateEnvironmentCommand;
use HighSolutions\LaravelEnvironments\Test\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class CreateCommandTest extends TestCase
{
    /** @test */
    public function create_new_environment()
    {
        $code = Artisan::call('env:create', [
            'name' => 'local',
        ]);

        $this->assertEquals(0, $code);
        $this->assertTrue(File::exists(config('laravel-environments.path') . DIRECTORY_SEPARATOR . 'local'));
    }
}
