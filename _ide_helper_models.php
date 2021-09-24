<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Functionalities
 *
 * @property string $id
 * @property string $name
 * @property string $description
 * @property int $is_controller
 * @property int $is_ui
 * @property int $is_testing
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PlatformsFunctionalities[] $platformsFunctionalities
 * @property-read int|null $platforms_functionalities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Test[] $tests
 * @property-read int|null $tests_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Functionalities newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Functionalities newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Functionalities onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Functionalities query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Functionalities whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Functionalities whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Functionalities whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Functionalities whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Functionalities whereIsController($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Functionalities whereIsTesting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Functionalities whereIsUi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Functionalities whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Functionalities whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Functionalities withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Functionalities withoutTrashed()
 */
	class Functionalities extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Mentions
 *
 * @property string $id
 * @property string $mentioner_id
 * @property string $mentioned_id
 * @property string $mentionable_type
 * @property string $mentionable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $mentionable
 * @property-read \App\Models\User $mentioned
 * @property-read \App\Models\User $mentioner
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mentions newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mentions newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mentions query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mentions whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mentions whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mentions whereMentionableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mentions whereMentionableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mentions whereMentionedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mentions whereMentionerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mentions whereUpdatedAt($value)
 */
	class Mentions extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PersonalAccessToken
 *
 * @property int $id
 * @property string $tokenable_type
 * @property string $tokenable_id
 * @property string $name
 * @property string $token
 * @property array|null $abilities
 * @property \Illuminate\Support\Carbon|null $last_used_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $tokenable
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereAbilities($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereLastUsedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereTokenableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereTokenableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class PersonalAccessToken extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PersonalInformations
 *
 * @property string $id
 * @property string $owner_id
 * @property string|null $alias
 * @property string|null $mobile
 * @property string|null $profile_cover_path
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $owner
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonalInformations newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonalInformations newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonalInformations query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonalInformations whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonalInformations whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonalInformations whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonalInformations whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonalInformations whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonalInformations whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonalInformations whereProfileCoverPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonalInformations whereUpdatedAt($value)
 */
	class PersonalInformations extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Platforms
 *
 * @property string $id
 * @property string $name
 * @property string $domain
 * @property int $is_public
 * @property int $is_password_protected
 * @property string|null $password
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PlatformsFunctionalities[] $platformsFunctionalities
 * @property-read int|null $platforms_functionalities_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Platforms newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Platforms newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Platforms query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Platforms whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Platforms whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Platforms whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Platforms whereIsPasswordProtected($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Platforms whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Platforms whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Platforms wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Platforms whereUpdatedAt($value)
 */
	class Platforms extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PlatformsFunctionalities
 *
 * @property string $functionality_id
 * @property string $platform_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Functionalities $functionality
 * @property-read \App\Models\Platforms $platform
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlatformsFunctionalities newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlatformsFunctionalities newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlatformsFunctionalities query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlatformsFunctionalities whereFunctionalityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlatformsFunctionalities wherePlatformId($value)
 */
	class PlatformsFunctionalities extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Questionnaire
 *
 * @property string $id
 * @property string $link
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Test[] $tests
 * @property-read int|null $tests_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Questionnaire newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Questionnaire newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Questionnaire query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Questionnaire whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Questionnaire whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Questionnaire whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Questionnaire whereUpdatedAt($value)
 */
	class Questionnaire extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Settings
 *
 * @property string $id
 * @property string $name
 * @property string $type
 * @property string|null $allowed_values
 * @property bool $has_default_value
 * @property string|null $default_value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserSettings[] $userSettings
 * @property-read int|null $user_settings_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereAllowedValues($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereDefaultValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereHasDefaultValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereUpdatedAt($value)
 */
	class Settings extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Test
 *
 * @property string $id
 * @property string $functionality_id
 * @property string $user_segment_id
 * @property string $questionnaire_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Functionalities $functionality
 * @property-read \App\Models\Questionnaire $questionnaire
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TestResult[] $testResults
 * @property-read int|null $test_results_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserSegments[] $userSegment
 * @property-read int|null $user_segment_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Test newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Test newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Test query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Test whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Test whereFunctionalityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Test whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Test whereQuestionnaireId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Test whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Test whereUserSegmentId($value)
 */
	class Test extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TestResult
 *
 * @property string $id
 * @property string $tester_id
 * @property int $utilizations
 * @property int $has_answered_questionnaire
 * @property string $test_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Test $test
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TestResult newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TestResult newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TestResult query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TestResult whereHasAnsweredQuestionnaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TestResult whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TestResult whereTestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TestResult whereTesterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TestResult whereUtilizations($value)
 */
	class TestResult extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property string $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $remember_token
 * @property string|null $profile_photo_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read string $profile_photo_url
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Mentions[] $mentioner
 * @property-read int|null $mentioner_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Mentions[] $mentions
 * @property-read int|null $mentions_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \App\Models\PersonalInformations|null $personalInformation
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserSettings[] $settings
 * @property-read int|null $settings_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TestResult[] $testResults
 * @property-read int|null $test_results_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \App\Models\UserSegments $userSegment
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereProfilePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserSegments
 *
 * @property string $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Test $test
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSegments newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSegments newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSegments query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSegments whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSegments whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSegments whereUpdatedAt($value)
 */
	class UserSegments extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserSettings
 *
 * @property string $id
 * @property string $owner_id
 * @property string $settings_id
 * @property \App\Models\Settings $setting
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $owner
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSettings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSettings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSettings query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSettings whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSettings whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSettings whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSettings whereSetting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSettings whereSettingsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSettings whereUpdatedAt($value)
 */
	class UserSettings extends \Eloquent {}
}

