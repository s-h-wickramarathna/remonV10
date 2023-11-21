<?php namespace Core\ModuleManage;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider {

	protected $files;

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot() 
	{
		if(is_dir(app_path().'/Modules/')) {
			$modules = config("modules.enable") ?: array_map('class_basename', $this->files->directories(app_path().'/Modules/'));
			foreach($modules as $module) {

				if(file_exists(app_path().'/Modules'.'/'.$module.'/routes.php')) {					
	                include app_path().'/Modules'.'/'.$module.'/routes.php';
	            }

	            // Load the views for each of the modules
	            if(is_dir(app_path().'/Modules'.'/'.$module.'/Views')) {
	                $this->loadViewsFrom(app_path().'/Modules'.'/'.$module.'/Views', $module);
	            }

				
			}
		}



	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register() 
	{
		$this->files = new Filesystem;
		$this->registerMakeCommand();
	}

	/**
	 * Register the "make:module" console command.
	 *
	 * @return Console\ModuleMakeCommand
	 */
	protected function registerMakeCommand() 
	{
		$this->commands('modules.make');
		
		$bind_method = method_exists($this->app, 'bindShared') ? 'bindShared' : 'singleton';

		$this->app->{$bind_method}('modules.make', function($app) {
			return new Console\ModuleMakeCommand($this->files);
		});
	}

}
