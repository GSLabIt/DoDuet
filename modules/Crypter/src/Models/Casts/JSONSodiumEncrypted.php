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

class JSONSodiumEncrypted extends SodiumEncrypted
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
        $decrypted = parent::get($model, $key, $value, $attributes);
        return json_decode($decrypted, true);
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
        // serialize to json, then encode
        $value = json_encode($value);
        return parent::set($model, $key, $value, $attributes);
    }
}
