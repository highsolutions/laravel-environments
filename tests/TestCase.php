<?php

namespace HighSolutions\LaravelEnvironments\Test;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    public function getTempDirectory(): string
    {
        return __DIR__.'/temp';
    }
}
