<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class MakeWrapper extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:wrapper';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new wrapper';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Wrapper';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        $stub = "/stubs/wrapper.plain.stub";

        if ($this->option("worker")) {
            $stub = "/stubs/wrapper.worker.stub";
        }
        elseif ($this->option("interactive")) {
            $stub = "/stubs/wrapper.interactive.stub";
        }

        return $this->resolveStubPath($stub);
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param string $stub
     * @return string
     */
    protected function resolveStubPath(string $stub): string
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Http\Wrappers';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['worker', 'w', InputOption::VALUE_NONE, 'Generate a worker wrapper.'],
            ['interactive', 'i', InputOption::VALUE_NONE, 'Generate an interactive wrapper.'],
        ];
    }
}
