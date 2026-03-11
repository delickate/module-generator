<?php 
namespace Delickate\ModuleGenerator\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeModuleMigrationCommand extends Command
{
    protected $signature = 'module:make-migration 
                            {name} 
                            {module}';

    protected $description = 'Create migration in module';

    public function handle()
    {
        $name = $this->argument('name');
        $module = Str::studly($this->argument('module'));

        $path = base_path("Modules/$module/Database/Migrations");

        if (!is_dir($path)) {
            $this->error("Module does not exist.");
            return;
        }

        $file = date('Y_m_d_His')."_$name.php";

        $this->call('make:migration', [
            'name'=>$name,
            '--path'=>"Modules/$module/Database/Migrations"
        ]);

        $this->info("Migration created.");
    }
}
