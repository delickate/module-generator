<?php
/**
 * --------------------------------------------------------------------------
 * Delickate User Sessions Package
 * --------------------------------------------------------------------------
 *
 * @package     Delickate\UserSessions
 * @author      Sani Hyne 
 * @copyright   Copyright (c) 2026 Delickate
 * @license     MIT
 * @version     1.0.0
 * @since       1.0.0
 *
 * This file is part of the Delickate User Sessions module.
 * It provides session tracking, activity logging, and audit features.
 *
 */
namespace Delickate\ModuleGenerator;

use Illuminate\Support\ServiceProvider;

# use Delickate\ModuleGenerator\Console\MakeModuleCommand;
use Delickate\ModuleGenerator\Console\MakeModuleControllerCommand;


class ModuleGeneratorServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) 
        {
            $this->commands([
                MakeModuleCommand::class,
                MakeModuleControllerCommand::class,
            ]);
        }

        $this->registerModules();
    }

    protected function registerModules()
    {
        $modulesPath = base_path('Modules');

        if (!is_dir($modulesPath)) 
        {
            return;
        }

        foreach (glob($modulesPath . '/*/Providers/*ServiceProvider.php') as $file) {
            $class = $this->getClassFromFile($file);
            if ($class && class_exists($class)) {
                $this->app->register($class);
            }
        }
    }

    protected function getClassFromFile($file)
    {
        $contents = file_get_contents($file);

        if (preg_match('/namespace (.*);/', $contents, $namespace) &&
            preg_match('/class (\w+)/', $contents, $class)) {
            return $namespace[1] . '\\' . $class[1];
        }

        return null;
    }
}