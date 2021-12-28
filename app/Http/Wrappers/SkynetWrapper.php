<?php

namespace App\Http\Wrappers;

use App\Http\Wrappers\Interfaces\Wrapper;
use JetBrains\PhpStorm\Pure;

class SkynetWrapper implements Wrapper
{
    /**
     * Initialize the class instance
     *
     * @param $initializer
     * @return SkynetWrapper
     */
    #[Pure]
    public static function init($initializer = null): SkynetWrapper
    {
        return new static();
    }

    public function upload() {

    }

    public function download() {

    }
}
