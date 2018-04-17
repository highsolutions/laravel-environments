<?php

namespace HighSolutions\LaravelEnvironments\Commands;

use Illuminate\Console\Command;

class CopyEnvironmentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:copy {old} {new} {--overwrite}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a copy of existing environment setup';

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
        $old = $this->argument('old');
        $new = $this->argument('new');
        $this->line("Copying new environment from {$old} to {$new}");

        $result = $this->manager->copy($old, $new, $this->option('overwrite', false));

        if ($result) {
            $this->line("Environment {$new} has been created from {$old}!");
        } elseif($result === null) {
            $this->error("Environment {$new} has NOT been created becacuse environemnt {$old} does NOT exist.");
        } else {
            $this->info("Environment {$new} has NOT been created becacuse it's already exists. If you want to overwrite it, use `--overwrite` option.");
        }
    }
}
