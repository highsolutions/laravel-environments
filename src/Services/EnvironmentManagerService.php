<?php

namespace HighSolutions\LaravelEnvironments\Services;

use HighSolutions\LaravelEnvironments\Contracts\EnvironmentManagerContract;
use Illuminate\Support\Facades\File;

class EnvironmentManagerService implements EnvironmentManagerContract
{
    protected $path;

    public function create($name, $overwrite = false)
    {
        $this->setPath($name);
        
        if($this->cannotOverwriteExistingDirectory($overwrite))
            return;
     
        $this->copyFiles();

        return true;
    }

    protected function setPath($anotherDirectory = '')
    {
        $basicPath = str_finish($this->getConfig('path'), DIRECTORY_SEPARATOR);
        $this->path = str_finish($basicPath . $anotherDirectory, DIRECTORY_SEPARATOR);
    }

    protected function getConfig($key)
    {
        return config('laravel-environments.' . $key);
    }

    protected function cannotOverwriteExistingDirectory($overwrite)
    {
        if(File::exists($this->path) && !$overwrite)
            return true;

        if($this->getConfig('clear_directory_when_overwriting'))
            File::cleanDirectory($this->path);

        return false;
    }

    protected function copyFiles()
    {
        collect($this->getConfig('files'))
            ->each(function ($file) {
                $this->copyFile($file);
            });
    }

    protected function copyFile($file)
    {
        list($fullDirectoryPath, $filename) = $this->getFilePath($file);

        $this->createFinalDirectoryIfNotExists($fullDirectoryPath);

        if(File::exists(base_path($file)))
            File::copy(base_path($file), $fullDirectoryPath . $filename);
    }

    protected function getFilePath($file)
    {
        $file = str_replace('/', '\\', $file);
        $filename = substr($file, strpos($file, '\\'));
        $dirPath = str_before($file, $filename);

        return [
            $this->path . $dirPath,
            $filename,
        ];
    }

    protected function createFinalDirectoryIfNotExists($path)
    {
        if(!File::exists($path))
            File::makeDirectory($path, 0755, true, true);
    }

    public function remove($name)
    {
        $this->setPath($name);
        
        if(!$this->checkExistingDirectory())
            return false;
     
        $this->removeDirectory();

        return true;
    }

    protected function checkExistingDirectory()
    {
        return File::exists($this->path);
    }

    protected function removeDirectory()
    {
        File::cleanDirectory($this->path);
    }
}
