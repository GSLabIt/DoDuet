<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\Crypter\Models\Traits;

use Doinc\Modules\Crypter\Exceptions\InsecureRandomSourceInCryptographicallyCriticalImplementation;
use Doinc\Modules\Crypter\Facades\Crypter;
use Doinc\Modules\Crypter\Models\Casts\JSONSodiumEncrypted;
use Doinc\Modules\Crypter\Models\Casts\SodiumEncrypted;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use SodiumException;

trait Encrypted
{
    /**
     * The "booted" method of the model.
     *
     * @return void
     * @throws InsecureRandomSourceInCryptographicallyCriticalImplementation|SodiumException
     */
    protected static function booted()
    {
        static::saving(function (Model $model) {
            $casts = $model->getCasts();
            foreach ($casts as $property => $class) {
                $encryption_key = config("crypter.secure_key");

                // set the signature attribute according to the plain value and return the encrypted value
                if (in_array($class, $model->cryptableClasses())) {
                    $value = $model->getAttributes()[$property];

                    $model->{"{$property}_sig"} = hash_hmac(
                        config("crypter.algorithm"),
                        $value,
                        config("crypter.secure_key")
                    );

                    $model->setRawAttributes([
                        ...$model->getAttributes(),
                        "$property" => Crypter::encryption()->symmetric()->encrypt(
                            $value,
                            $encryption_key,
                            Crypter::derivation()->generateSymmetricNonce()
                        )
                    ]);
                }
            }
        });
    }

    /**
     * Scope a query to only include raws where an encrypted value matches the raw value provided.
     *
     * @param Builder $query
     * @param string $column_name
     * @param string $value
     * @return Builder
     */
    public function scopeWhereEncryptedIs(Builder $query, string $column_name, string $value): Builder
    {
        return $query->where(
            "{$column_name}_sig",
            hash_hmac(config("crypter.algorithm"), $value, config("crypter.secure_key"))
        );
    }

    public function cryptableClasses(): array {
        return [
            SodiumEncrypted::class,
            JSONSodiumEncrypted::class
        ];
    }
}
