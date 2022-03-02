<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Console\Helper\ProgressBar;

class ModuleGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:new {module-name : Name of the module to create}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new raw module';

    private string $module_name;
    private string $raw_module_name;
    private string $studly_module_name;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->raw_module_name = $this->argument("module-name");
        $this->module_name = Str::camel($this->raw_module_name);
        $this->studly_module_name = Str::studly($this->raw_module_name);

        $this->comment("Creating module named '{$this->module_name}' ...");
        $this->withProgressBar(null, function (ProgressBar $bar) {
            $this->generate($bar);
        });
        $this->info('Module creation completed!');
    }

    protected function generate(ProgressBar $bar)
    {
        $base_path = resource_path("stubs/modules");
        $files = [
            "config/config.php.blade.php",
            "database/factories/.gitkeep.blade.php",
            "database/migrations/{{now}}_create_{{plural_snake}}_table.php.blade.php",
            "resources/assets/css/app.css.blade.php",
            "resources/assets/js/app.js.blade.php",
            "resources/assets/js/pages/index.vue.blade.php",
            "routes/api.php.blade.php",
            "routes/web.php.blade.php",
            "src/Console/.gitkeep.blade.php",
            "src/Enums/{{studly}}Routes.php.blade.php",
            "src/Events/.gitkeep.blade.php",
            "src/Facades/{{studly}}.php.blade.php",
            "src/Http/Controllers/{{studly}}Controller.php.blade.php",
            "src/Http/Middleware/.gitkeep.blade.php",
            "src/Models/Interfaces/.gitkeep.blade.php",
            "src/Models/Traits/Uuid.php.blade.php",
            "src/Models/Traits/ActivityLogAll.php.blade.php",
            "src/Models/{{studly}}.php.blade.php",
            "src/Providers/{{studly}}ServiceProvider.php.blade.php",
            "src/Providers/RouteServiceProvider.php.blade.php",
            "test/Feature/{{studly}}Test.php.blade.php",
            "test/Unit/{{studly}}Test.php.blade.php",
            ".gitignore.blade.php",
            "composer.json.blade.php",
            "LICENSE.md.blade.php",
            "module.json.blade.php",
            "package.json.blade.php",
            "README.md.blade.php",
        ];

        $bar->start(count($files));

        $compilation_data = [
            "studly" => $this->studly_module_name,
            "camel" => $this->module_name,
            "snake" => Str::snake($this->raw_module_name),
            "plural_snake" => Str::snake(Str::plural($this->raw_module_name)),
            "namespace" => "Doinc\\Modules\\{$this->studly_module_name}",
            "now" => now()->format("Y_m_d_his"),
            "year" => now()->year
        ];
        $base_module_dir = base_path("modules/");

        foreach ($files as $file) {
            $full_path = $base_path . "/$file";

            $full_module_path = $base_module_dir . "{$this->module_name}/$file";
            $module_dir = dirname($full_module_path, 100);
            $this->mkdir($module_dir);

            $stub = file_get_contents($full_path);
            $compiled = Blade::render($stub, $compilation_data, true);
            $compiled_filename = Blade::render(
                Str::replaceLast(".blade.php", "", $full_module_path),
                $compilation_data,
                true
            );
            file_put_contents($compiled_filename, $compiled);

            $bar->advance();
        }
    }

    protected function mkdir($target)
    {
        if (Storage::directoryMissing($target)) {
            Storage::makeDirectory($target);
        }
    }
}
