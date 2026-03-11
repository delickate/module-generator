<?php 
namespace Delickate\ModuleGenerator\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ModuleDeleteCommand extends Command
{
    protected $signature = 'module:delete {module}';

    protected $description = 'Delete a module';

    public function handle()
    {
        $module = $this->argument('module');

        $path = base_path("Modules/$module");

        if (!is_dir($path)) {
            $this->error("Module not found");
            return;
        }

        File::deleteDirectory($path);

        $this->info("$module deleted successfully");
    }
}
