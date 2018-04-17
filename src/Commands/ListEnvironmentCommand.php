<?php

namespace HighSolutions\LaravelEnvironments\Commands;

use Illuminate\Console\Command;

class ListEnvironmentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all environment setups';

    /**
     * The service with logic.
     *
     * @var EnvironmentManagerContract
     */
    protected $manager;

    /**
     * Create a new command instance.
     *
     * @param  EnvironmentManagerContract   $manager
     * @return void
     */
    public function __construct($manager)
    {
        parent::__construct();

        $this->manager = $manager;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $environments = $this->manager->getList();

        if (count($environments) == 0) {
            $this->error('There are no defined environments.');

            return;
        }

        $this->table([
            'No.',
            'Name',
        ], $environments);
    }
}
