<?php

namespace App\Http\Wrappers;

use Spatie\Regex\Regex;
use TypeError;

enum GMPHelperFormat {
    case decimal;
    case thousand;
    case both;
}

class GMPHelper
{
    private string $value;

    /**
     * Initialize the class instance
     *
     * @param string|int|GMPHelper $value
     * @return GMPHelper
     */
    public static function init(string|int|GMPHelper $value): GMPHelper
    {
        // check if the value is instance of the class, in case return otherwise initialize
        return $value instanceof GMPHelper ? $value : new static($value);
    }

    public function __construct(string|int $value)
    {
        if(is_string($value) && is_numeric($value)) {
            $this->value = $value;
        }
        elseif (is_int($value)) {
            $this->value = gmp_strval($value);
        }
        else {
            throw new TypeError("GMPHelper accepts only numeric strings or integer value");
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * Format current instance number in decimal notation
     *
     * @param int $decimal_positions
     * @return string
     */
    private function formatDecimal(int $decimal_positions): string {
        $length = strlen($this->value);
        // check if the length of the string is equals at least to the decimal position requested, in that case take
        // the subset of decimals
        if($length >= $decimal_positions && $decimal_positions > 0) {
            $decimal = substr($this->value, -$decimal_positions);
        }
        elseif ($length >= $decimal_positions && $decimal_positions === 0) {
            $decimal = null;
        }
        // otherwise pad the number with 0 till the decimal length is reached
        else {
            $offset = $decimal_positions - $length;
            $decimal = str_repeat("0", $offset) . $this->value;
        }

        // take the subset of the integer part or set it null in case of errors
        $integer = false;
        if($decimal_positions > 0 && !($integer = substr($this->value, 0, -$decimal_positions))) {
            $integer = null;
        }
        elseif (!$integer && $decimal_positions === 0) {
            $integer = $this->value;
        }

        return ($integer ?? "0") . ($decimal ? ".{$decimal}" : "");
    }

    private function formatThousand(string $value = null): string {
        $value = $value ?? $this->value;

        // split the string in 1 char chunks array, reverse it and rejoin it forming the reversed original string,
        // then split the reversed string in chunks of 3 chars
        $reversed_value = str_split(implode("", array_reverse(str_split($value))), 3);

        $result = "";
        $max = count($reversed_value);
        for($i = 0; $i < $max; $i++) {
            if($i === 0) {
                $result = $reversed_value[$i];
            }
            else {
                $result = "{$reversed_value[$i]},{$result}";
            }
        }

        return $result;
    }

    public function format(int $decimal_positions = 0, GMPHelperFormat $format = GMPHelperFormat::decimal): string {
        switch($format) {
            case GMPHelperFormat::decimal:
                return $this->formatDecimal($decimal_positions);
            case GMPHelperFormat::thousand:
                return $this->formatThousand();
            case GMPHelperFormat::both:
                [$integer, $decimal] = explode($this->formatDecimal($decimal_positions), ".");
                return "{$this->formatThousand($integer)}.{$decimal}";
        }

        // fallback
        return $this->value;
    }

    public function equals(string|int|GMPHelper $value): bool {
        return self::eq($this, $value);
    }

    public function notEquals(string|int|GMPHelper $value): bool {
        return self::neq($this, $value);
    }

    public function greaterThan(string|int|GMPHelper $value): bool {
        return self::gt($this, $value);
    }

    public function lessThan(string|int|GMPHelper $value): bool {
        return self::lt($this, $value);
    }

    public static function eq(string|int|GMPHelper $lhs, string|int|GMPHelper $rhs): bool {
        return (new static($lhs))->raw() === (new static($rhs))->raw();
    }

    public static function neq(string|int|GMPHelper $lhs, string|int|GMPHelper $rhs): bool {
        return !self::eq($lhs, $rhs);
    }

    public static function gt(string|int|GMPHelper $lhs, string|int|GMPHelper $rhs): bool {
        return gmp_cmp((new static($lhs))->raw(), (new static($rhs))->raw()) > 0;
    }

    public static function lt(string|int|GMPHelper $lhs, string|int|GMPHelper $rhs): bool {
        return gmp_cmp((new static($lhs))->raw(), (new static($rhs))->raw()) < 0;
    }

    /**
     * Return the raw representation of the number stored
     * @return string
     */
    public function raw(): string {
        return (string) $this;
    }

    private function isValidRepresentation($value): bool {
        return $value instanceof GMPHelper || is_numeric($value);
    }

    /**
     * Overload sum operators.
     *
     * Refer to https://wiki.php.net/rfc/userspace_operator_overloading for more information
     * @param $rhs
     * @return GMPHelper
     */
    public function add($rhs): GMPHelper
    {
        if(!$this->isValidRepresentation($rhs)) {
            throw new TypeError("Cannot execute addition, incompatible type");
        }

        return new static(gmp_strval(gmp_add($this->value, (new static($rhs))->raw())));
    }

    /**
     * Overload sub operators.
     *
     * Refer to https://wiki.php.net/rfc/userspace_operator_overloading for more information
     * @param $rhs
     * @return GMPHelper
     */
    public function sub($rhs): GMPHelper
    {
        if(!$this->isValidRepresentation($rhs)) {
            throw new TypeError("Cannot execute subtraction, incompatible type");
        }

        return new static(gmp_strval(gmp_sub($this->value, (new static($rhs))->raw())));
    }

    /**
     * Overload mul operators.
     *
     * Refer to https://wiki.php.net/rfc/userspace_operator_overloading for more information
     * @param $rhs
     * @return GMPHelper
     */
    public function mul($rhs): GMPHelper
    {
        if(!$this->isValidRepresentation($rhs)) {
            throw new TypeError("Cannot execute multiplication, incompatible type");
        }

        return new static(gmp_strval(gmp_mul($this->value, (new static($rhs))->raw())));
    }

    /**
     * Overload div operators.
     *
     * Refer to https://wiki.php.net/rfc/userspace_operator_overloading for more information
     * @param $rhs
     * @return GMPHelper
     */
    public function div($rhs): GMPHelper
    {
        if(!$this->isValidRepresentation($rhs)) {
            throw new TypeError("Cannot execute division, incompatible type");
        }

        return new static(gmp_strval(gmp_div($this->value, (new static($rhs))->raw())));
    }

    /**
     * Overload pow operators.
     *
     * Refer to https://wiki.php.net/rfc/userspace_operator_overloading for more information
     * @param $rhs
     * @return GMPHelper
     */
    public function pow($rhs): GMPHelper
    {
        if(!$this->isValidRepresentation($rhs)) {
            throw new TypeError("Cannot execute division, incompatible type");
        }

        return new static(gmp_strval(gmp_pow($this->value, gmp_intval((new static($rhs))->raw()))));
    }

    /**
     * Overload mod operators.
     *
     * Refer to https://wiki.php.net/rfc/userspace_operator_overloading for more information
     * @param $rhs
     * @return GMPHelper
     */
    public function mod($rhs): GMPHelper
    {
        if(!$this->isValidRepresentation($rhs)) {
            throw new TypeError("Cannot execute division, incompatible type");
        }

        return new static(gmp_strval(gmp_mod($this->value, (new static($rhs))->raw())));
    }
}
