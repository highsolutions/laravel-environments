<?php

namespace HighSolutions\LaravelEnvironments\Commands;

use Illuminate\Console\Command;

class MakeEnvironmentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:env {name}';

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
        \Artisan::call('env:create', [
            'name' => $this->argument('name', 'dev'),
            '--overwrite' => true,
        ]);
    }
}
