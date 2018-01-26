<?php

namespace HighSolutions\LaravelEnvironments\Contracts;

interface EnvironmentManagerContract
{
    public function create($name, $overwrite);
}