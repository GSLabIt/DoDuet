<?php

namespace Doinc\Modules\Commander\Console;

use Illuminate\Console\Command;
use Spatie\Regex\Regex;
use Spatie\Emoji\Emoji;

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
    public function handle()
    {
        $this->info("Overwriting default module configuration ...");
        $commands = [

        ];

        $rex = '';
        $config = config_path("modules.php");
        $replacement = "";

        $content = file_get_contents($config);
        // $content = Regex::replace($rex, $replacement, $content)->result();
        file_put_contents($config, $content);

        $this->info("Overwrite completed, commander installed and working " . Emoji::winkingFace());

        return 0;
    }
}
