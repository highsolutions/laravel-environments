<?php

namespace HighSolutions\LaravelEnvironments\Test;

use PHPUnit\Framework\Attributes\Test;

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

    #[Test]
    public function remove_existing_environment()
    {
        $this->executeCreate([
            'name' => 'local',
        ]);

        $this->assertDoesDirectoryExist('local');

        $this->executeRemove([
            'name' => 'local',
        ]);

        $this->assertDirectoryDoesNotExist('local');
    }

    #[Test]
    public function not_remove_not_existing_environment()
    {
        $this->assertDirectoryDoesNotExist('local');

        $code = $this->executeRemove([
            'name' => 'local',
        ]);

        $this->assertEquals(1, $code);
        $this->assertDirectoryDoesNotExist('local');
    }
}
