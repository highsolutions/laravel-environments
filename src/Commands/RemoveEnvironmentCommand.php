<?php

namespace HighSolutions\LaravelEnvironments\Commands;

use Illuminate\Console\Command;

class RemoveEnvironmentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:remove {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove environment setup';

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
        $name = $this->argument('name');
        $this->line("Removing environment {$name}");

        $result = $this->manager->remove($name);

        if ($result) {
            $this->line("Environment {$name} has been removed!");
        } else {
            $this->line("Environment {$name} has NOT been removed, because it's not existing.");

            return 1;
        }
    }
}
