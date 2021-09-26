<?php

namespace App\Http\Wrappers\Interfaces;

interface WorkerWrapper
{
    /**
     * Execute an operation
     *
     * @param string $operation
     * @param $data
     * @return mixed
     */
    function run($data, string $operation): mixed;

    /**
     * Get the list of all available wrapper operations
     *
     * @return array
     */
    function availableOperations(): array;
}
