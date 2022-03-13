<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\Settings\Models;

use App\Models\User;
use Doinc\Modules\Crypter\Models\Casts\SodiumEncrypted;
use Doinc\Modules\Crypter\Models\Traits\Encrypted;
use Doinc\Modules\Settings\Models\Traits\ActivityLogAll;
use Doinc\Modules\Settings\Models\Traits\HasDTO;
use Doinc\Modules\Settings\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;


/**
 * Doinc\Modules\Settings\Models\UserSettings
 *
 * @property int $id
 * @property int $owner_id
 * @property int $settings_id
 * @property string $setting_value
 * @property string $setting_value_sig
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read User $owner
 * @property-read \Doinc\Modules\Settings\Models\Settings|null $setting
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings newQuery()
 * @method static \Illuminate\Database\Query\Builder|UserSettings onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings whereEncryptedIs(string $column_name, string $value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings whereSettingValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings whereSettingValueSig($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings whereSettingsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|UserSettings withTrashed()
 * @method static \Illuminate\Database\Query\Builder|UserSettings withoutTrashed()
 * @mixin IdeHelperUserSettings
 */
class UserSettings extends Model
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll, Encrypted, HasDTO, SoftDeletes;

    protected $guarded = ["created_at", "updated_at", "deleted_at"];

    protected $casts = [
        "setting_value" => SodiumEncrypted::class
    ];

    function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner_id");
    }

    function setting(): BelongsTo
    {
        return $this->belongsTo(Settings::class);
    }
}
