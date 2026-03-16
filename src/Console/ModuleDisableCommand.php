<?php 

namespace Delickate\ModuleGenerator\Console;

use Illuminate\Console\Command;

class ModuleDisableCommand extends Command
{
    protected $signature = 'module:disable {module}';

    protected $description = 'Disable module';

    public function handle()
    {
        $module = $this->argument('module');

        set_module_status($module,false);

        $this->info("$module disabled successfully");
    }
}
