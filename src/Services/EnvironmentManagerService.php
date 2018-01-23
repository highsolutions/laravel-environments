<?php

namespace HighSolutions\LaravelEnvironments\Services;

use HighSolutions\LaravelEnvironments\Contracts\EnvironmentManagerContract;
use Illuminate\Support\Facades\File;

class EnvironmentManagerService implements EnvironmentManagerContract
{
    public function create(string $name): bool
    {
        File::makeDirectory(config('laravel-environments.path') . DIRECTORY_SEPARATOR . $name);
        return true;
    }
}