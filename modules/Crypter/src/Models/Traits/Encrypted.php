<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\Crypter\Models\Traits;

use Doinc\Modules\Crypter\Facades\Crypter;
use Doinc\Modules\Crypter\Models\Casts\JSONSodiumEncrypted;
use Doinc\Modules\Crypter\Models\Casts\SodiumEncrypted;
use Illuminate\Database\Eloquent\Builder;
use Throwable;

trait Encrypted
{
    /**
     * Save the model to the database.
     *
     * @param array $options
     * @return bool
     */
    public function save(array $options = []): bool
    {
        try {
            $casts = $this->getCasts();
            foreach ($casts as $property => $class) {
                $encryption_key = config("crypter.secure_key");

                // set the signature attribute according to the plain value and return the encrypted value
                if (!in_array($class, $this->cryptableClasses())) {
                    continue;
                }
                $value = $this->getAttributes()[$property];

                $this->setRawAttributes([
                    ...$this->getAttributes(),
                    "{$property}_sig" => hash_hmac(
                        config("crypter.algorithm"),
                        $value,
                        config("crypter.secure_key")
                    ),
                    "$property" => Crypter::encryption()->symmetric()->encrypt(
                        $value,
                        $encryption_key,
                        Crypter::derivation()->generateSymmetricNonce()
                    )
                ]);

            }
        } catch (Throwable) {
            return false;
        }

        return parent::save($options);
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

    public function cryptableClasses(): array
    {
        return [
            SodiumEncrypted::class,
            JSONSodiumEncrypted::class
        ];
    }
}
