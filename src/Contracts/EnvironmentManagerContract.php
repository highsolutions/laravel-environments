<?php

namespace HighSolutions\LaravelEnvironments\Contracts;

interface EnvironmentManagerContract
{
    public function create(string $name, bool $overwrite): bool;
}