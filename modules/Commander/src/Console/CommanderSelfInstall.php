<?php

namespace Doinc\Modules\Commander\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Spatie\Regex\Regex;
use Nwidart\Modules\Commands;

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
     */
    public function handle(): int
    {
        $this->info("Overwriting default module configuration ...");
        $commands = [
            \Doinc\Modules\Commander\Console\ModuleCommand::class,
            \Doinc\Modules\Commander\Console\ModuleController::class,
            Commands\DisableCommand::class,
            Commands\DumpCommand::class,
            Commands\EnableCommand::class,
            \Doinc\Modules\Commander\Console\ModuleEvent::class,
            \Doinc\Modules\Commander\Console\ModuleJob::class,
            \Doinc\Modules\Commander\Console\ModuleListener::class,
            \Doinc\Modules\Commander\Console\ModuleMail::class,
            \Doinc\Modules\Commander\Console\ModuleMiddleware::class,
            \Doinc\Modules\Commander\Console\ModuleNotification::class,
            \Doinc\Modules\Commander\Console\ModuleProvider::class,
            \Doinc\Modules\Commander\Console\ModuleInstall::class,
            Commands\ListCommand::class,
            // TODO: empower composer psr4 module removal
            Commands\ModuleDeleteCommand::class,
            \Doinc\Modules\Commander\Console\ModuleFactory::class,
            \Doinc\Modules\Commander\Console\ModulePolicy::class,
            \Doinc\Modules\Commander\Console\ModuleRule::class,
            Commands\MigrateCommand::class,
            Commands\MigrateRefreshCommand::class,
            Commands\MigrateResetCommand::class,
            Commands\MigrateRollbackCommand::class,
            Commands\MigrateStatusCommand::class,
            \Doinc\Modules\Commander\Console\ModuleMigration::class,
            \Doinc\Modules\Commander\Console\ModuleModel::class,
            \Doinc\Modules\Commander\Console\ModulePublishConfig::class,
            Commands\PublishMigrationCommand::class,
            Commands\PublishTranslationCommand::class,
            Commands\SeedCommand::class,
            \Doinc\Modules\Commander\Console\ModuleSeeder::class,
            Commands\SetupCommand::class,
            Commands\UnUseCommand::class,
            Commands\UpdateCommand::class,
            Commands\UseCommand::class,
            \Doinc\Modules\Commander\Console\ModuleTest::class,
        ];

        $rex = "/'commands' => \[\n(.+\n)+\s+],/";
        $config = config_path("modules.php");
        $replacement = "'command' => [\n        " .
            implode(
                "\n        ",
                array_map(
                    fn(string $v) => $v . "::class,",
                    $commands
                )
            ) .
            "\n    ],";

        $content = file_get_contents($config);
        $content = Regex::replace($rex, $replacement, $content)->result();
        file_put_contents($config, $content);

        $this->info("Overwrite completed, commander installed");

        return 0;
    }
}
