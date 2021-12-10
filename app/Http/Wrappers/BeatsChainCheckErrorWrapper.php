<?php

namespace App\Http\Wrappers;

use App\Exceptions\BeatsChainInvalidAddressLength;
use App\Exceptions\BeatsChainInvalidChecksum;
use App\Exceptions\BeatsChainNotACouncilMember;
use App\Exceptions\BeatsChainRequiredSudo;
use App\Exceptions\BeatsChainUnableToPayFees;
use Throwable;

class BeatsChainCheckErrorWrapper
{
    private static array $error_map = [
        "RequireSudo" => BeatsChainRequiredSudo::class,
        "Invalid checksum" => BeatsChainInvalidChecksum::class,
        "Invalid address length" => BeatsChainInvalidAddressLength::class,
        "Invalid Transaction" => BeatsChainUnableToPayFees::class,
        "NotMember" => BeatsChainNotACouncilMember::class,
        "DuplicateVote" => "",
    ];

    /**
     * Check for errors and throws them
     *
     * @param array $errors
     * @return void
     * @throw BeatsChainRequiredSudo|BeatsChainInvalidChecksum
     */
    public static function check(array $errors): void
    {
        logger($errors);

        // check if there are errors
        if(count($errors) > 0) {
            // loop through the error and check if they are in the map
            foreach ($errors as $error) {
                // check that error is an array with the key 'name' and that the key is whitelisted
                if(is_array($error) && array_key_exists("name", $error) && array_key_exists($error["name"], self::$error_map)) {
                    // throw the error that is found in the map
                    throw new self::$error_map[$error["name"]]();
                }
                // check that error is an indexed array
                elseif (is_array($error) && count($error) > 0) {
                    foreach ($error as $e) {
                        if(array_key_exists($e, self::$error_map)) {
                            // throw the error that is found in the map
                            throw new self::$error_map[$e]();
                        }
                    }
                }
                // error is not an array, directly check in the error map for its value
                else {
                    if(array_key_exists($error, self::$error_map)) {
                        // throw the error that is found in the map
                        throw new self::$error_map[$error]();
                    }
                }
            }
        }
    }
}
