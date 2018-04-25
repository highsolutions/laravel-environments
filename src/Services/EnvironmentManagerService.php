<?php

namespace HighSolutions\LaravelEnvironments\Services;

use Illuminate\Support\Facades\File;
use HighSolutions\LaravelEnvironments\Contracts\EnvironmentManagerContract;

class EnvironmentManagerService implements EnvironmentManagerContract
{
    protected $path;

    public function create($name, $overwrite = false)
    {
        $this->setPath($name);

        if ($this->cannotOverwriteExistingDirectory($overwrite)) {
            return;
        }

        $this->copyFiles();

        return true;
    }

    protected function setPath($anotherDirectory = '')
    {
        $this->path = str_finish($this->getStoragePath().$anotherDirectory, DIRECTORY_SEPARATOR);
    }

    protected function getStoragePath()
    {
        $path = $this->getConfig('path');
        if (! File::exists($path)) {
            File::makeDirectory($path, 0755, true, true);
        }

        return str_finish($path, DIRECTORY_SEPARATOR);
    }

    protected function getConfig($key)
    {
        return config('laravel-environments.'.$key);
    }

    protected function cannotOverwriteExistingDirectory($overwrite)
    {
        if (File::exists($this->path) && ! $overwrite) {
            return true;
        }

        if ($this->getConfig('clear_directory_when_overwriting')) {
            File::cleanDirectory($this->path);
        }

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

        if (File::exists(base_path($file))) {
            File::copy(base_path($file), $fullDirectoryPath.$filename);
        }
    }

    protected function getFilePath($file)
    {
        $file = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $file);
        $filename = substr($file, strpos($file, DIRECTORY_SEPARATOR));
        $dirPath = str_before($file, $filename);

        return [
            $this->path.$dirPath,
            $filename,
        ];
    }

    protected function createFinalDirectoryIfNotExists($path)
    {
        if (! File::exists($path)) {
            File::makeDirectory($path, 0755, true, true);
        }
    }

    public function remove($name)
    {
        $this->setPath($name);

        if (! $this->checkExistingDirectory()) {
            return false;
        }

        $this->removeDirectory();

        return true;
    }

    protected function checkExistingDirectory()
    {
        return File::exists($this->path);
    }

    protected function removeDirectory()
    {
        File::deleteDirectory($this->path);
    }

    public function getList()
    {
        $path = $this->getStoragePath();
        $list = File::directories($path);

        return collect($list)
            ->map(function ($dir, $index) {
                return $this->transformListRow($dir, $index);
            })->all();
    }

    protected function transformListRow($name, $index)
    {
        return [
            $index + 1,
            $this->stripPathAndSeparators($name),
        ];
    }

    private function stripPathAndSeparators($name)
    {
        $path = $this->getStoragePath();

        return str_replace($path, '', $name);
    }

    public function copy($old, $new, $overwrite = false)
    {
        $this->setPath($new);

        $path = $this->getStoragePath();
        if (! File::exists($path.$old)) {
            return;
        }

        if (File::exists($path.$new)) {
            if (! $overwrite) {
                return false;
            }

            $this->cleanDirectory();
        }

        return File::copyDirectory($path.$old, $path.$new);
    }

    protected function cleanDirectory()
    {
        File::cleanDirectory($this->path);
    }

    public function setActive($name)
    {
        $this->setPath($name);

        if (! $this->checkExistingDirectory()) {
            return false;
        }

        $this->activateFiles();

        if (! $this->getConfig('keep_existing_file_when_missing'))
            $this->deleteNotExistingFiles();

        return true;
    }

    protected function activateFiles()
    {
        collect($this->getEnvironmentFiles())
            ->each(function ($file) {
                $this->activateFile($file);
            });
    }

    protected function getEnvironmentFiles()
    {
        return File::allFiles($this->path, true);
    }

    protected function activateFile($file)
    {
        $fileName = $this->stripEnvPath($file);
        File::copy($file, base_path($fileName));
    }

    protected function stripEnvPath($file)
    {
        return str_replace($this->path, '', $file);
    }

    protected function deleteNotExistingFiles()
    {
        $files = $this->getConfig('files');

        collect($files)
            ->each(function ($file) {
                if (File::exists($this->path . $file)) {
                    return;
                }

                File::delete(base_path($file));
            });
    }
}
