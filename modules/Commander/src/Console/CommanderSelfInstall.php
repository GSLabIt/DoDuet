<?php

namespace Doinc\Modules\Commander\Console;

use Illuminate\Console\Command;
use Spatie\Regex\Regex;
use Nwidart\Modules\Commands;
use wapmorgan\UnifiedArchive\UnifiedArchive;

class CommanderSelfInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commander:self-install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Modify the configuration of the modules packages routing all the module commands to ' .
    'the custom command provided by the commander module';

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
     * @throws \wapmorgan\UnifiedArchive\Exceptions\ArchiveExtractionException
     */
    public function handle(): int
    {
        $this->info("Overwriting default module configuration ...");
        $commands = [
            Commands\DisableCommand::class,
            Commands\DumpCommand::class,
            Commands\EnableCommand::class,
            Commands\ListCommand::class,
            Commands\MigrateCommand::class,
            Commands\MigrateRefreshCommand::class,
            Commands\MigrateResetCommand::class,
            Commands\MigrateRollbackCommand::class,
            Commands\MigrateStatusCommand::class,
            Commands\PublishMigrationCommand::class,
            Commands\PublishTranslationCommand::class,
            Commands\SeedCommand::class,
            Commands\SetupCommand::class,
            Commands\UnUseCommand::class,
            Commands\UpdateCommand::class,
            Commands\UseCommand::class,
            ModuleCommand::class,
            ModuleController::class,
            ModuleDelete::class,
            ModuleEvent::class,
            ModuleFactory::class,
            ModuleInstall::class,
            ModuleJob::class,
            ModuleListener::class,
            ModuleMail::class,
            ModuleMiddleware::class,
            ModuleMigration::class,
            ModuleModel::class,
            ModuleNotification::class,
            ModulePolicy::class,
            ModuleProvider::class,
            ModulePublishConfig::class,
            ModuleRule::class,
            ModuleSeeder::class,
            ModuleTest::class,
        ];

        $rex = "/'commands' => \[\n(.+\n)+\s+],/";
        $config = config_path("modules.php");
        $replacement = "'commands' => [\n        " .
            implode(
                "\n        ",
                array_map(
                    fn(string $v) => "\\\\" . $v . "::class,",
                    $commands
                )
            ) .
            "\n    ],";

        $content = file_get_contents($config);
        $content = Regex::replace($rex, $replacement, $content)->result();
        file_put_contents($config, $content);

        $this->info("Overwrite completed, commander installed!");
        $this->info("Unpacking and loading stubs ...");

        $stubs_path = module_path("commander", "stubs.zip");
        $output_path = resource_path("stubs/modules/");
        if(!file_exists($stubs_path)) {
            $this->error("Stubs archive not found");
            return 1;
        }

        if(!UnifiedArchive::canOpen($stubs_path)) {
            $this->error("Stubs archive cannot be open");
            return 1;
        }

        $answer = true;
        if(file_exists($output_path)) {
            $answer = $this->confirm("Stubs folder already exists, overwrite it?");
        }

        if($answer) {
            UnifiedArchive::open($stubs_path)->extractFiles(resource_path());
        }

        $this->info("Stubs loaded!");

        return 0;
    }
}
