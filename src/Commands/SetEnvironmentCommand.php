<?php

namespace HighSolutions\LaravelEnvironments\Commands;

use Illuminate\Console\Command;

class SetEnvironmentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:set {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set a given environment as active';

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
        $this->line("Setting environment {$name} as active...");

        $result = $this->manager->setActive($name);

        if ($result) {
            $this->info("Environment {$name} is active!");
        } else {
            $this->error("Environment {$name} has NOT been set active becacuse it's not exists.");
        }
    }
}
