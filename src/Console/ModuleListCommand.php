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
            $this->line(basename($module));
        }
    }
}
