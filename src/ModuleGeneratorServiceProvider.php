<?php
/**
 * --------------------------------------------------------------------------
 * Delickate User Sessions Package
 * --------------------------------------------------------------------------
 *
 * @package     Delickate\module-generator
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

class ModuleGeneratorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) 
        {
            $this->commands([

                                Console\MakeModuleCommand::class,

                                Console\MakeModuleControllerCommand::class,
                                Console\MakeModuleModelCommand::class,
                                Console\MakeModuleMigrationCommand::class,

                                Console\ModuleListCommand::class,
                                Console\ModuleEnableCommand::class,
                                Console\ModuleDisableCommand::class,
                                Console\ModuleDeleteCommand::class,
                            ]);


        }

        $this->loadModules();
    }

    protected function loadModules()
    {
        $modulesPath = config('modules.modules_path');

        if (!is_dir($modulesPath)) {
            return;
        }

        $statuses = $this->getModuleStatuses();

        foreach (glob($modulesPath.'/*') as $modulePath) {

            $module = basename($modulePath);

            if (!($statuses[$module] ?? true)) {
                continue;
            }


            // load routes
            if (file_exists($modulePath.'/Routes/web.php')) {
                $this->loadRoutesFrom($modulePath.'/Routes/web.php');
            }

            // load migrations
            if (is_dir($modulePath.'/Database/Migrations')) {
                $this->loadMigrationsFrom($modulePath.'/Database/Migrations');
            }

            // load views
            if (is_dir($modulePath.'/Resources/Views')) {
                $moduleName = strtolower(basename($modulePath));
                $this->loadViewsFrom($modulePath.'/Resources/Views', $moduleName);
            }

            // load configs
            if (is_dir($modulePath.'/Config')) {
                foreach (glob($modulePath.'/Config/*.php') as $config) {
                    $this->mergeConfigFrom(
                        $config,
                        basename($config,'.php')
                    );
                }
            }

            // register module providers
            foreach (glob($modulePath.'/Providers/*ServiceProvider.php') as $provider) {
                $class = $this->getClassFromFile($provider);
                if ($class && class_exists($class)) {
                    $this->app->register($class);
                }
            }
        }
    }

    protected function getClassFromFile($file)
    {
        $content = file_get_contents($file);

        if (preg_match('/namespace (.*);/', $content, $namespace) &&
            preg_match('/class (\w+)/', $content, $class)) {
            return $namespace[1].'\\'.$class[1];
        }

        return null;
    }
}
