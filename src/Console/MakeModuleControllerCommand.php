<?php

namespace Delickate\ModuleGenerator\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeModuleControllerCommand extends Command
{
    protected $signature = 'module:make-controller 
                            {name} 
                            {module}';

    protected $description = 'Create a controller inside a module';

    public function handle()
    {
        $controller = Str::studly($this->argument('name'));
        $module = Str::studly($this->argument('module'));

        $path = base_path("Modules/{$module}/Http/Controllers");

        if (!is_dir($path)) {
            $this->error("Module {$module} does not exist.");
            return;
        }

        $file = "{$path}/{$controller}.php";

        if (file_exists($file)) {
            $this->error("Controller already exists.");
            return;
        }

        $stub = file_get_contents(__DIR__.'/../Stubs/controller.stub');

        $content = str_replace(
            ['{{module}}','{{controller}}'],
            [$module,$controller],
            $stub
        );

        file_put_contents($file,$content);

        $this->info("Controller {$controller} created successfully in module {$module}");
    }
}
