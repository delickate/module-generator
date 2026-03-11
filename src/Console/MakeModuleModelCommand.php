<?php 
namespace Delickate\ModuleGenerator\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeModuleModelCommand extends Command
{
    protected $signature = 'module:make-model 
                            {name} 
                            {module}';

    protected $description = 'Create model in module';

    public function handle()
    {
        $model = Str::studly($this->argument('name'));
        $module = Str::studly($this->argument('module'));

        $path = base_path("Modules/$module/Models");

        if (!is_dir($path)) {
            $this->error("Module does not exist.");
            return;
        }

        $file = "$path/$model.php";

        $stub = file_get_contents(__DIR__.'/../Stubs/model.stub');

        $content = str_replace(
            ['{{module}}','{{model}}'],
            [$module,$model],
            $stub
        );

        file_put_contents($file,$content);

        $this->info("Model created successfully.");
    }
}
