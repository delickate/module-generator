<?php

namespace Delickate\ModuleGenerator\Console;

use Illuminate\Console\Command;

class ModuleListCommand extends Command
{
    protected $signature = 'module:list';

    protected $description = 'List all modules';

    public function handle()
    {
        $modules = glob(base_path('Modules/*'));

        if (!$modules) {
            $this->info("No modules found.");
            return;
        }

        foreach ($modules as $module) {

            $name = basename($module);

            $status = module_enabled($name)
                ? '<info>Enabled</info>'
                : '<comment>Disabled</comment>';

            $this->line("$name  -  $status");
        }
    }
}
