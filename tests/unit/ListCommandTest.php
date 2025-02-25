<?php

namespace HighSolutions\LaravelEnvironments\Test;

use HighSolutions\LaravelEnvironments\Contracts\EnvironmentManagerContract;
use PHPUnit\Framework\Attributes\Test;

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

    #[Test]
    public function list_existing_environment()
    {
        $this->executeCreate([
            'name' => 'local',
        ]);

        $this->assertDoesDirectoryExist('local');

        $list = $this->getList();

        $this->assertNestedArrayContains('local', $list);
    }

    #[Test]
    public function list_existing_many_environments()
    {
        $this->executeCreate([
            'name' => 'local',
        ]);

        $this->executeCreate([
            'name' => 'master',
        ]);

        $this->assertDoesDirectoryExist('local');
        $this->assertDoesDirectoryExist('master');

        $list = $this->getList();

        $this->assertNestedArrayContains('local', $list);
        $this->assertNestedArrayContains('master', $list);
    }
}
