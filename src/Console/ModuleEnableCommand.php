<?php

namespace Delickate\ModuleGenerator\Console;

use Illuminate\Console\Command;

class ModuleEnableCommand extends Command
{
    protected $signature = 'module:enable {module}';

    protected $description = 'Enable module';

    public function handle()
    {
        $module = $this->argument('module');

        set_module_status($module,true);

        $this->info("$module enabled successfully");
    }
}
