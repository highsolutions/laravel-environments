<?php

namespace HighSolutions\LaravelEnvironments\Test;

class RemoveCommandTest extends TestCase
{
    protected function executeCreate($params)
    {
        return $this->artisan('env:create', $params);
    }

    protected function executeRemove($params)
    {
        return $this->artisan('env:remove', $params);
    }

    /** @test */
    public function remove_existing_environment()
    {
        $this->executeCreate([
            'name' => 'local',
        ]);

        $this->assertDirectoryExists('local');

        $this->executeRemove([
            'name' => 'local',
        ]);

        $this->assertDirectoryNotExists('local');
    }

    /** @test */
    public function not_remove_not_existing_environment()
    {
        $this->assertDirectoryNotExists('local');

        $code = $this->executeRemove([
            'name' => 'local',
        ]);

        $this->assertEquals(1, $code);
        $this->assertDirectoryNotExists('local');
    }
}
