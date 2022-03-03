{!! $opening_tag !!}
/*
* Copyright (c) {{$year}} - Do Group LLC - All Right Reserved.
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
* Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, {{$year}}
*/

namespace {{ $namespace }}\Console;

use Illuminate\Console\Command;

class {{ $studly_param }} extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = '{{ $command }}:name';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Command description';

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
        return 0;
    }
}
