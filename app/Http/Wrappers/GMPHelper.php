<?php

namespace App\Http\Wrappers;

use App\Http\Wrappers\Interfaces\Wrapper;
use Spatie\Regex\Regex;
use TypeError;

class GMPHelper
{
    private string $value;

    /**
     * Initialize the class instance
     *
     * @param string|int $value
     * @return GMPHelper
     */
    public static function init(string|int $value): GMPHelper
    {
        return new static($value);
    }

    public function __construct(string|int $value)
    {
        if(is_string($value) && Regex::match("/^[0-9]+$/", $value)->hasMatch()) {
            $this->value = $value;
        }
        elseif (is_int($value)) {
            $this->value = gmp_strval($value);
        }
        else {
            throw new TypeError("GMPHelper accepts only numeric strings or integer value");
        }
    }
}
