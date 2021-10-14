<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Regex\Regex;

class EditControllerToAddGraphQL extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:graphql-method
        {controller-name : The full name of the controller without extension}
        {method-name : The name of the new method to create}
        {--P|path : Custom path where the controller is stored}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Edit an existing class adding a new graphql request handling method';

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
        $path = empty($this->option("path")) ? "app/Http/Controllers" : $this->option("path");
        $file_path = base_path("$path/{$this->argument('controller-name')}.php");

        if(!file_exists($file_path)) {
            $this->error("Unable to locate the controller at $path/{$this->argument('controller-name')}.php\nhave you generated it?");
            return 1;
        }

        $method_name = $this->argument('method-name');
        if(empty($method_name)) {
            $this->error("Invalid method name");
            return 1;
        }

        $content = file_get_contents($file_path);

        $addition = /**@lang php*/<<<NEW_METHOD

    /**
     * TODO: describe what this function does
     *
     * @param null \$root Always null, since this field has no parent.
     * @param array<string, mixed> \$args The field arguments passed by the client.
     * @param GraphQLContext \$context Shared between all fields.
     * @param ResolveInfo \$resolveInfo Metadata for advanced query resolution.
     * @return mixed
     */
    public function $method_name(\$root, array \$args, GraphQLContext \$context, ResolveInfo \$resolveInfo): mixed {
        // TODO: implement $method_name resolver
        return null;
    }
}

NEW_METHOD;

        $result = Regex::replace("/(?<! )}$/m", $addition, $content);

        file_put_contents($file_path, $result->result());

        return 0;
    }
}
