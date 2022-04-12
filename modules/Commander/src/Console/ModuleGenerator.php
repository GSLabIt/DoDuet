<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\Commander\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Regex\Regex;
use Symfony\Component\Console\Helper\ProgressBar;
use function base_path;
use function now;
use function resource_path;

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
    private Filesystem $filesystem;

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
    public function handle(): int
    {
        // define some common var
        $this->raw_module_name = $this->argument("module-name");
        $this->module_name = Str::camel($this->raw_module_name);
        $this->studly_module_name = Str::studly($this->raw_module_name);

        $this->info("Creating module named '{$this->studly_module_name}' ...");

        // display progress bar
        $this->withProgressBar(0, function (ProgressBar $bar) {
            $this->generate($bar);
            $this->line("");
        });

        $this->info('Module creation completed!');
        return 0;
    }

    protected function generate(ProgressBar $bar)
    {
        // retrieve the base path for module stub and define each of the file that should be generated
        // note the usage of blade template syntax in filenames
        $base_path = resource_path("stubs/modules");
        $files = [
            "config/config.php.blade.php",
            "database/factories/.gitkeep.blade.php",
            "database/seeders/.gitkeep.blade.php",
            "database/migrations/{{\$now}}_create_{{\$plural_snake}}_table.php.blade.php",
            "resources/assets/css/app.css.blade.php",
            "resources/assets/js/app.js.blade.php",
            "resources/assets/js/pages/index.vue.blade.php",
            "routes/api.php.blade.php",
            "routes/web.php.blade.php",
            "src/Console/.gitkeep.blade.php",
            "src/Enums/{{\$studly}}Routes.php.blade.php",
            "src/Events/.gitkeep.blade.php",
            "src/Facades/{{\$studly}}.php.blade.php",
            "src/Http/Controllers/{{\$studly}}Controller.php.blade.php",
            "src/Http/Middleware/.gitkeep.blade.php",
            "src/Models/Interfaces/.gitkeep.blade.php",
            "src/Models/Traits/Uuid.php.blade.php",
            "src/Models/Traits/ActivityLogAll.php.blade.php",
            "src/Models/{{\$studly}}.php.blade.php",
            "src/Providers/{{\$studly}}ServiceProvider.php.blade.php",
            "src/Providers/RouteServiceProvider.php.blade.php",
            "src/{{\$studly}}.php.blade.php",
            "tests/Feature/{{\$studly}}Test.php.blade.php",
            "tests/Unit/{{\$studly}}Test.php.blade.php",
            ".gitignore.blade.php",
            "composer.json.blade.php",
            "LICENSE.md.blade.php",
            "module.json.blade.php",
            "package.json.blade.php",
            "README.md.blade.php",
        ];

        // set the progressbar elements
        $bar->start(count($files) +2);

        // create an array of the blade templates substitutions
        $compilation_data = [
            "studly" => $this->studly_module_name,
            "camel" => $this->module_name,
            "snake" => Str::snake($this->raw_module_name),
            "kebab" => Str::kebab($this->raw_module_name),
            "capitalized" => Str::headline($this->raw_module_name),
            "plural_snake" => Str::snake(Str::plural($this->raw_module_name)),
            "namespace" => "Doinc\\Modules\\{$this->studly_module_name}",
            "escaped_namespace" => "Doinc\\\\Modules\\\\{$this->studly_module_name}",
            "now" => now()->format("Y_m_d_his"),
            "year" => now()->year,
            "opening_tag" => "<?php"
        ];

        // we create a filesystem instance on the fly pointing to the modules folder, we'll use this instance
        // to write the files
        $this->filesystem = Storage::createLocalDriver([
            'driver' => 'local',
            'root' => base_path("modules/"),
        ]);

        // override progressbar default format
        $bar->setFormat(" %current%/%max% [%bar%] %percent:3s%% %message%");

        // start looping through the files to generate and compile their content
        foreach ($files as $file) {
            $full_path = $base_path . "/$file";
            $full_module_path = "{$this->studly_module_name}/$file";

            $compiled_filename = Blade::render(
            // each stub must end in .blade.php to allow its compilation, remove it when generating the real filename
                Str::replaceLast(".blade.php", "", $full_module_path),
                $compilation_data,
                true
            );
            $bar->setMessage("Generating $compiled_filename");
            usleep(10000);

            // create the folder if not exists
            $module_dir = dirname($full_module_path);
            $this->mkdir($module_dir);

            // get the file content, compile it and write it to the disk
            $stub = file_get_contents($full_path);
            $compiled = Blade::render($stub, $compilation_data, true);
            $this->filesystem->put($compiled_filename, $compiled);

            $bar->setMessage("");
            $bar->advance();
        }

        // update the main composer.json adding the psr4 definition
        $composer = base_path("composer.json");
        $composer_content = file_get_contents($composer);

        $replacement_mbase = "\"Doinc\\\\\\\\Modules\\\\\\\\{$this->studly_module_name}\\\\\\\\";
        $replacement_fbase = "\"modules/{$this->studly_module_name}/";
        $spacer = ",\n            ";

        $replacement = $spacer .
            "{$replacement_mbase}\": {$replacement_fbase}src/\"" .
            $spacer .
            "{$replacement_mbase}Database\\\\\\\\Factories\\\\\\\\\": {$replacement_fbase}database/factories/\"" .
            $spacer .
            "{$replacement_mbase}Database\\\\\\\\Seeders\\\\\\\\\": {$replacement_fbase}database/seeders/\"" .
            "$1";
        $composer_content = $this->replacer(
            '/"autoload": {\n\s+"psr-4": {\n\s+(.+\n)+\s+}(?>,\n\s+"files": \[|\n\s+},\n\s+"autoload-dev")/',
            $composer_content,
            '/(\n\s+}(?>,\n\s+"files": \[|\n\s+},\n\s+"autoload-dev"))/',
            $replacement,
            $this->studly_module_name
        );
        $bar->advance();

        $replacement = $spacer .
            "{$replacement_mbase}Tests\\\\\\\\Feature\\\\\\\\\": {$replacement_fbase}tests/Feature/\"" .
            $spacer .
            "{$replacement_mbase}Tests\\\\\\\\Unit\\\\\\\\\": {$replacement_fbase}tests/Unit/\"" .
            "$1";
        $composer_content = $this->replacer(
            '/"autoload-dev": {\n\s+"psr-4": {\n\s+(.+\n)+\s+}\n\s+},\n\s+"scripts": {/',
            $composer_content,
            '/(\n\s+}\n\s+},\n\s+"scripts": \{)/',
            $replacement,
            $this->studly_module_name
        );

        file_put_contents($composer, $composer_content);
        $bar->advance();
    }

    protected function mkdir($target)
    {
        if ($this->filesystem->directoryMissing($target)) {
            $this->filesystem->makeDirectory($target);
        }
    }

    /**
     * Generalization of the replacement method for files
     *
     * @param string $extractor_regex Regex for the extraction of the first section where `$replacement` will be added
     * @param string $full_content
     * @param string $replacement_regex Regex for the definition of the place where the `$replacement` will actually occur
     * @param string $replacement Replacement string, can contain regex groups values
     * @param string $module_name Module name for replacement check
     * @return string
     */
    protected function replacer(
        string $extractor_regex,
        string $full_content,
        string $replacement_regex,
        string $replacement,
        string $module_name
    ): string {
        $content = Regex::match($extractor_regex, $full_content)->result();
        $is_registered = Str::contains($content, "Doinc\\\\Modules\\\\{$module_name}\\");

        if(!$is_registered) {
            $content = Regex::replace($replacement_regex, $replacement, $content)->result();
            return Regex::replace(
                $extractor_regex,
                Str::replace("\\", "\\\\", $content),
                $full_content
            )->result();
        }
        return $full_content;
    }
}
