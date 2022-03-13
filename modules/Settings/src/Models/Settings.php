<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\Settings\Models;

use Doinc\Modules\Crypter\Models\Casts\SodiumEncrypted;
use Doinc\Modules\Crypter\Models\Traits\Encrypted;
use Doinc\Modules\Settings\Models\Traits\ActivityLogAll;
use Doinc\Modules\Settings\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;


/**
 * Doinc\Modules\Settings\Models\Settings
 *
 * @property int $id
 * @property string $name
 * @property string $name_sig
 * @property string $type
 * @property string $type_sig
 * @property bool $has_default_value
 * @property string $default_value
 * @property string $default_value_sig
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Doinc\Modules\Settings\Models\UserSettings[] $userSettings
 * @property-read int|null $user_settings_count
 * @method static \Illuminate\Database\Eloquent\Builder|Settings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Settings newQuery()
 * @method static \Illuminate\Database\Query\Builder|Settings onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Settings query()
 * @method static \Illuminate\Database\Eloquent\Builder|Settings whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Settings whereDefaultValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Settings whereDefaultValueSig($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Settings whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Settings whereEncryptedIs(string $column_name, string $value)
 * @method static \Illuminate\Database\Eloquent\Builder|Settings whereHasDefaultValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Settings whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Settings whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Settings whereNameSig($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Settings whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Settings whereTypeSig($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Settings whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Settings withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Settings withoutTrashed()
 * @mixin IdeHelperSettings
 */
class Settings extends Model
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll, Encrypted, SoftDeletes;

    protected $guarded = ["created_at", "updated_at", "deleted_at"];

    protected $casts = [
        "has_default_value" => "boolean",
        "default_value" => SodiumEncrypted::class,
        "name" => SodiumEncrypted::class,
        "type" => SodiumEncrypted::class,
    ];

    function userSettings(): HasMany
    {
        return $this->hasMany(UserSettings::class);
    }
}
