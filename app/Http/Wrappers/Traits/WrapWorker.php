<?php

namespace App\Http\Wrappers\Traits;

trait WrapWorker
{
    /**
     * Execute an operation
     *
     * @param string $operation
     * @param $data
     * @return mixed
     */
    abstract public function run($data, string $operation = "default"): mixed;

    /**
     * Get the list of all available wrapper operations
     *
     * @return array
     */
    function availableOperations(): array {
        return [
            "default"
        ];
    }
}
