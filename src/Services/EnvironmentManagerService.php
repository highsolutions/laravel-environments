<?php

namespace HighSolutions\LaravelEnvironments\Services;

use HighSolutions\LaravelEnvironments\Contracts\EnvironmentManagerContract;
use Illuminate\Support\Facades\File;

class EnvironmentManagerService implements EnvironmentManagerContract
{
    public function create($name, $overwrite = false)
    {
        $path = $this->getPath($name);
        
        if(!$this->proceedOverwriteOfExistingDirectory($path, $overwrite))
            return false;
     
        return File::makeDirectory($path);
    }

    protected function getPath($anotherDirectory = null)
    {
        return str_finish(config('laravel-environments.path'), DIRECTORY_SEPARATOR) . $anotherDirectory;
    }

    protected function proceedOverwriteOfExistingDirectory($path, $overwrite)
    {
        if($overwrite)
            File::deleteDirectory($path);
        
        return !File::exists($path);
    }
}