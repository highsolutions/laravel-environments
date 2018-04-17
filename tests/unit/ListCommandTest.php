<?php

namespace HighSolutions\LaravelEnvironments\Test;

use HighSolutions\LaravelEnvironments\Contracts\EnvironmentManagerContract;

class ListCommandTest extends TestCase
{
    protected function executeCreate($params)
    {
        return $this->artisan('env:create', $params);
    }

    protected function getList()
    {
        return resolve(EnvironmentManagerContract::class)->getList();
    }

    /** @test */
    public function list_existing_environment()
    {
        $this->executeCreate([
            'name' => 'local',
        ]);

        $this->assertDirectoryExists('local');

        $list = $this->getList();

        $this->assertNestedArrayContains('local', $list);
    }

    /** @test */
    public function list_existing_many_environments()
    {
        $this->executeCreate([
            'name' => 'local',
        ]);

        $this->executeCreate([
            'name' => 'master',
        ]);

        $this->assertDirectoryExists('local');
        $this->assertDirectoryExists('master');

        $list = $this->getList();

        $this->assertNestedArrayContains('local', $list);
        $this->assertNestedArrayContains('master', $list);
    }
}
