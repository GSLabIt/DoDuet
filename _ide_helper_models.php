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
 * App\Models\Covers
 *
 * @property string $id
 * @property string $name
 * @property string $skynet_id
 * @property string|null $nft_id
 * @property string $owner_id
 * @property string $creator_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $creator
 * @property-read \App\Models\User $owner
 * @property-read \App\Models\Skynet $skynet
 * @property-read \App\Models\Tracks|null $track
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Covers newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Covers newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Covers query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Covers whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Covers whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Covers whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Covers whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Covers whereNftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Covers whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Covers whereSkynetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Covers whereUpdatedAt($value)
 */
	class Covers extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Elections
 *
 * @property int $id
 * @property string $total_prize
 * @property float $first_prize_rate
 * @property string $first_place_id
 * @property float $second_prize_rate
 * @property string $second_place_id
 * @property float $third_prize_rate
 * @property string $third_place_id
 * @property float $treasury_rate
 * @property float $fee_rate
 * @property float $burning_rate
 * @property string|null $started_at
 * @property string|null $ended_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $firstPlace
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ListeningRequest[] $listeningRequests
 * @property-read int|null $listening_requests_count
 * @property-read \App\Models\User $secondPlace
 * @property-read \App\Models\User $thirdPlace
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Votes[] $votes
 * @property-read int|null $votes_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Elections newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Elections newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Elections query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Elections whereBurningRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Elections whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Elections whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Elections whereFeeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Elections whereFirstPlaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Elections whereFirstPrizeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Elections whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Elections whereSecondPlaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Elections whereSecondPrizeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Elections whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Elections whereThirdPlaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Elections whereThirdPrizeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Elections whereTotalPrize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Elections whereTreasuryRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Elections whereUpdatedAt($value)
 */
	class Elections extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Functionalities
 *
 * @property string $id
 * @property string $name
 * @property string $description
 * @property bool $is_controller
 * @property bool $is_ui
 * @property bool $is_testing
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Platforms[] $platforms
 * @property-read int|null $platforms_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Test[] $tests
 * @property-read int|null $tests_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserSegments[] $userSegments
 * @property-read int|null $user_segments_count
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
 * App\Models\ListeningRequest
 *
 * @property string $id
 * @property string $voter_id
 * @property string $track_id
 * @property int $election_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Elections $election
 * @property-read \App\Models\Tracks $track
 * @property-read \App\Models\User $voter
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ListeningRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ListeningRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ListeningRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ListeningRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ListeningRequest whereElectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ListeningRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ListeningRequest whereTrackId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ListeningRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ListeningRequest whereVoterId($value)
 */
	class ListeningRequest extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Lyrics
 *
 * @property string $id
 * @property string $name
 * @property string $lyric
 * @property string $owner_id
 * @property string $creator_id
 * @property string|null $nft_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $creator
 * @property-read \App\Models\User $owner
 * @property-read \App\Models\Tracks|null $track
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lyrics newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lyrics newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lyrics query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lyrics whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lyrics whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lyrics whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lyrics whereLyric($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lyrics whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lyrics whereNftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lyrics whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lyrics whereUpdatedAt($value)
 */
	class Lyrics extends \Eloquent {}
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Functionalities[] $functionalities
 * @property-read int|null $functionalities_count
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
 * App\Models\Referral
 *
 * @property string $id
 * @property string $code
 * @property string $owner_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Referred[] $referrers
 * @property-read int|null $referrers_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Referral newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Referral newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Referral query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Referral whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Referral whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Referral whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Referral whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Referral whereUpdatedAt($value)
 */
	class Referral extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Referred
 *
 * @property string $id
 * @property string $referrer_id
 * @property string $referred_id
 * @property int $is_redeemed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $referred
 * @property-read \App\Models\Referral $refferer
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Referred newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Referred newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Referred query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Referred whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Referred whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Referred whereIsRedeemed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Referred whereReferredId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Referred whereReferrerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Referred whereUpdatedAt($value)
 */
	class Referred extends \Eloquent {}
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
 * App\Models\Skynet
 *
 * @property string $id
 * @property string $link
 * @property int $encrypted
 * @property string $encryption_key
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Covers|null $cover
 * @property-read \App\Models\Tracks|null $track
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Skynet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Skynet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Skynet query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Skynet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Skynet whereEncrypted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Skynet whereEncryptionKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Skynet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Skynet whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Skynet whereUpdatedAt($value)
 */
	class Skynet extends \Eloquent {}
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
 * App\Models\Tracks
 *
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string $duration
 * @property string $nft_id
 * @property string $owner_id
 * @property string $creator_id
 * @property string $skynet_id
 * @property string $cover_id
 * @property string $lyric_id
 * @property string|null $album_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Covers $cover
 * @property-read \App\Models\User $creator
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ListeningRequest[] $listeningRequests
 * @property-read int|null $listening_requests_count
 * @property-read \App\Models\Lyrics $lyric
 * @property-read \App\Models\User $owner
 * @property-read \App\Models\Skynet $skynet
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Votes[] $votes
 * @property-read int|null $votes_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tracks newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tracks newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tracks query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tracks whereAlbumId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tracks whereCoverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tracks whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tracks whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tracks whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tracks whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tracks whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tracks whereLyricId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tracks whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tracks whereNftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tracks whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tracks whereSkynetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tracks whereUpdatedAt($value)
 */
	class Tracks extends \Eloquent {}
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Covers[] $createdCovers
 * @property-read int|null $created_covers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Lyrics[] $createdLyrics
 * @property-read int|null $created_lyrics_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tracks[] $createdTracks
 * @property-read int|null $created_tracks_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Elections[] $firstPlaces
 * @property-read int|null $first_places_count
 * @property-read string $profile_photo_url
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ListeningRequest[] $listeningRequests
 * @property-read int|null $listening_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Mentions[] $mentioner
 * @property-read int|null $mentioner_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Mentions[] $mentions
 * @property-read int|null $mentions_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Covers[] $ownedCovers
 * @property-read int|null $owned_covers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Lyrics[] $ownedLyrics
 * @property-read int|null $owned_lyrics_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tracks[] $ownedTracks
 * @property-read int|null $owned_tracks_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \App\Models\PersonalInformations|null $personalInformation
 * @property-read \App\Models\Referral|null $referral
 * @property-read \App\Models\Referred|null $referred
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Elections[] $secondPlaces
 * @property-read int|null $second_places_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserSettings[] $settings
 * @property-read int|null $settings_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TestResult[] $testResults
 * @property-read int|null $test_results_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Elections[] $thirdPlaces
 * @property-read int|null $third_places_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserSegments[] $userSegments
 * @property-read int|null $user_segments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Votes[] $votes
 * @property-read int|null $votes_count
 * @property-read \App\Models\Wallet|null $wallet
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
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Functionalities[] $functionalities
 * @property-read int|null $functionalities_count
 * @property-read \App\Models\Test $test
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSegments newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSegments newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSegments query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSegments whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSegments whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSegments whereName($value)
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

namespace App\Models{
/**
 * App\Models\Votes
 *
 * @property string $id
 * @property string $voter_id
 * @property string $track_id
 * @property int $election_id
 * @property int $vote
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Elections $election
 * @property-read \App\Models\Tracks $track
 * @property-read \App\Models\User $voter
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Votes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Votes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Votes query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Votes whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Votes whereElectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Votes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Votes whereTrackId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Votes whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Votes whereVote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Votes whereVoterId($value)
 */
	class Votes extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Wallet
 *
 * @property string $id
 * @property string $owner_id
 * @property string $chain
 * @property string $private_key
 * @property string $seed
 * @property string $address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $owner
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wallet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wallet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wallet query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wallet whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wallet whereChain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wallet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wallet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wallet whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wallet wherePrivateKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wallet whereSeed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wallet whereUpdatedAt($value)
 */
	class Wallet extends \Eloquent {}
}

