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
namespace Delickate\ModuleGenerator\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeModuleCommand extends Command
{
    protected $signature = 'delickate:module 
                            {name} 
                            {--migration} 
                            {--config}';

    protected $description = 'Create a new module';

    public function handle()
    {
        $name = Str::studly($this->argument('name'));
        $basePath = base_path("Modules/{$name}");

        if (is_dir($basePath)) 
        {
            $this->error("Module already exists.");
            return;
        }

        $this->createDirectories($basePath);
        $this->createFiles($name, $basePath);

        $this->info("Module {$name} created successfully.");
    }

    protected function createDirectories($basePath)
    {
        $directories = [
            'Http/Controllers',
            'Models',
            'Routes',
            'Database/Migrations',
            'Resources/Views',
            'Providers',
            'Config'
        ];

        foreach ($directories as $dir) {
            if (!is_dir($basePath.'/'.$dir)) {
                mkdir($basePath.'/'.$dir,0755,true);
            }
        }
    }


    protected function createFiles($name, $basePath)
    {
        $this->createFromStub('controller.stub',
            "{$basePath}/Http/Controllers/{$name}Controller.php",
            $name);

        $this->createFromStub('model.stub',
            "{$basePath}/Models/{$name}.php",
            $name);

        $this->createFromStub('routes.stub',
            "{$basePath}/Routes/web.php",
            $name);

        $this->createFromStub('provider.stub',
            "{$basePath}/Providers/{$name}ServiceProvider.php",
            $name);

        if ($this->option('config')) 
        {
            $this->createFromStub('config.stub',
                "{$basePath}/Config/config.php",
                $name);
        }
    }

    protected function createFromStub($stub, $path, $name)
    {
        $stubPath = __DIR__.'/../Stubs/'.$stub;
        $content = str_replace(
            ['{{module}}', '{{moduleLower}}'],
            [$name, strtolower($name)],
            file_get_contents($stubPath)
        );

        file_put_contents($path, $content);
    }
}