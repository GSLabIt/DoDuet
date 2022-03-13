<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\Crypter\Models\Casts;

use Doinc\Modules\Crypter\Exceptions\InsecureRandomSourceInCryptographicallyCriticalImplementation;
use Doinc\Modules\Crypter\Exceptions\UnmatchingEncryptedData;
use Doinc\Modules\Crypter\Facades\Crypter;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use SodiumException;

class SodiumEncrypted implements CastsAttributes
{
    /**
     * Transform the attribute from the underlying model values.
     *
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return string
     * @throws SodiumException
     * @throws UnmatchingEncryptedData
     */
    public function get($model, string $key, $value, array $attributes): mixed
    {
        $encryption_key = config("crypter.secure_key");

        // verify that the signature attribute matches the value otherwise throw an exception
        $decrypted = Crypter::encryption()->symmetric()->decrypt($value, $encryption_key);

        if (
            hash_hmac(
                config("crypter.algorithm"),
                $decrypted,
                $encryption_key
            ) === $model->getAttribute("{$key}_sig")
        ) {
            return $decrypted;
        }
        throw new UnmatchingEncryptedData();
    }

    /**
     * Transform the attribute to its underlying model values.
     *
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes): string
    {
        return $value;
    }
}
