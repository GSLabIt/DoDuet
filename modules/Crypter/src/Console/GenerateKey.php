<?php
/*
* Copyright (c) 2022 - Do Group LLC - All Right Reserved.
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
* Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
*/

namespace Doinc\Modules\Crypter\Console;

use Doinc\Modules\Crypter\Facades\Crypter;
use Illuminate\Console\Command;
use Spatie\Regex\Regex;

class GenerateKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var  string
     */
    protected $signature = 'crypter:gen-key';

    /**
     * The console command description.
     *
     * @var  string
     */
    protected $description = 'Generates a new key and add it to .env';

    /**
     * Create a new command instance.
     *
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return  int
     */
    public function handle()
    {
        $this->info("Generating encryption key ...");
        $rex = "/^APP_SYMMETRIC_KEY.+$/";
        $env = base_path(".env");
        $env_content = file_get_contents($env);
        if(Regex::match($rex, $env_content)->hasMatch()) {
            $env_content = Regex::replace(
                $rex,
                "APP_SYMMETRIC_KEY=" . Crypter::encryption()->symmetric()->key(),
                $env_content
            );
        }
        else {
            $rex = "/^(APP_KEY.+)$/";
            $env_content = Regex::replace(
                $rex,
                "$1\nAPP_SYMMETRIC_KEY=" . Crypter::encryption()->symmetric()->key(),
                $env_content
            );
        }
        file_put_contents($env, $env_content);
        $this->info("Key generated and written into .env file");
        return 0;
    }
}
