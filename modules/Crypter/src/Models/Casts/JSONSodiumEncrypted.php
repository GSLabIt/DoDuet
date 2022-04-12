<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\Crypter\Models\Casts;

use Doinc\Modules\Crypter\Exceptions\UnmatchingEncryptedData;
use Doinc\Modules\Crypter\Facades\Crypter;
use Illuminate\Database\Eloquent\Model;
use SodiumException;

class JSONSodiumEncrypted
{
    /**
     * Transform the attribute from the underlying model values.
     *
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return array
     * @throws SodiumException
     * @throws UnmatchingEncryptedData
     */
    public function get($model, string $key, $value, array $attributes): array
    {
        // decode then deserialize json
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
            return json_decode($decrypted, true);
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
     * @return string
     */
    public function set($model, string $key, $value, array $attributes): string
    {
        // serialize to json, then encode
        return json_encode($value);
    }
}
