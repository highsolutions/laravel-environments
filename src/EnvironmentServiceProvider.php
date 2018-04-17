<?php 

namespace HighSolutions\LaravelEnvironments;

use HighSolutions\LaravelEnvironments\Commands\CreateEnvironmentCommand;
use HighSolutions\LaravelEnvironments\Commands\MakeEnvironmentCommand;
use HighSolutions\LaravelEnvironments\Commands\RemoveEnvironmentCommand;
use HighSolutions\LaravelEnvironments\Contracts\EnvironmentManagerContract;
use HighSolutions\LaravelEnvironments\Services\EnvironmentManagerService;
use Illuminate\Support\ServiceProvider;

class EnvironmentServiceProvider extends ServiceProvider 
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->_basicRegister();

        $this->_serviceRegister();

        $this->_commandsRegister();
    }

    private function _basicRegister() 
    {
        $configPath = __DIR__ . '/../config/laravel-environments.php';
        $this->mergeConfigFrom($configPath, 'laravel-environments');
        $this->publishes([
            $configPath => config_path('laravel-environments.php')
        ], 'config');
    }

    private function _serviceRegister()
    {
        app()->bind(EnvironmentManagerContract::class, EnvironmentManagerService::class);
    }

    private function _commandsRegister() 
    {
        foreach($this->commandsList() as $name => $class) {
            $this->initCommand($name, $class);
        }
    }

    protected function commandsList()
    {
        return [
            'create' => CreateEnvironmentCommand::class,
            'make' => MakeEnvironmentCommand::class,
            'remove' => RemoveEnvironmentCommand::class,
        ];
    }

    private function initCommand($name, $class)
    {
        $this->app->singleton("command.laravel-environments.{$name}", function($app) use ($class) {
            return new $class(resolve(EnvironmentManagerContract::class));
        });

        $this->commands("command.laravel-environments.{$name}");
    }

    /**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{

	}

}
