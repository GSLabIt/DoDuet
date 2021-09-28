<?php

namespace App\Http\Wrappers\Interfaces;

interface CryptographicWrapper
{
    function key(string $seed): mixed;
    function encrypt(string $plain, string $encryption_key, string $nonce): string;
    function decrypt(string $encoded, string $encryption_key): string;
}
