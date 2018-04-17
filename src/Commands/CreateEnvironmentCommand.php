<?php

namespace HighSolutions\LaravelEnvironments\Commands;

use Illuminate\Console\Command;

class CreateEnvironmentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:create {name} {--overwrite}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new environment setup';

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
        $name = $this->argument('name', 'dev');
        $this->line("Creating new environment {$name}");

        $result = $this->manager->create($name, $this->option('overwrite', false));

        if ($result) {
            $this->info("Environment {$name} has been created!");
        } else {
            $this->error("Environment {$name} has NOT been created becacuse it's already exists. If you want to overwrite it, use `--overwrite` option.");
        }
    }
}
