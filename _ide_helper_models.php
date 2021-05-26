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
 * App\Models\Election
 *
 * @property string $id
 * @property string|null $first_track_id
 * @property string|null $second_track_id
 * @property string|null $third_track_id
 * @property int|null $first_prize
 * @property int|null $second_prize
 * @property int|null $third_prize
 * @property int|null $burned
 * @property int|null $fee
 * @property int|null $liquidity_pool
 * @property string|null $contract
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Track|null $firstClassified
 * @property-read \App\Models\Track|null $secondClassified
 * @property-read \App\Models\Track|null $thirdClassified
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Votes[] $votes
 * @property-read int|null $votes_count
 * @method static \Illuminate\Database\Eloquent\Builder|Election newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Election newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Election query()
 * @method static \Illuminate\Database\Eloquent\Builder|Election whereBurned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Election whereContract($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Election whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Election whereFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Election whereFirstPrize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Election whereFirstTrackId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Election whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Election whereLiquidityPool($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Election whereSecondPrize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Election whereSecondTrackId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Election whereThirdPrize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Election whereThirdTrackId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Election whereUpdatedAt($value)
 */
	class Election extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Genre
 *
 * @property string $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Track[] $tracks
 * @property-read int|null $tracks_count
 * @method static \Illuminate\Database\Eloquent\Builder|Genre newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Genre newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Genre query()
 * @method static \Illuminate\Database\Eloquent\Builder|Genre whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Genre whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Genre whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Genre whereUpdatedAt($value)
 */
	class Genre extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Invite
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Invite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invite query()
 * @method static \Illuminate\Database\Eloquent\Builder|Invite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invite whereUpdatedAt($value)
 */
	class Invite extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Track
 *
 * @property string $id
 * @property string $name
 * @property string $visibility
 * @property string|null $description
 * @property string|null $lyric
 * @property string|null $daw
 * @property string $duration
 * @property string|null $nft_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $owner_id
 * @property string $creator_id
 * @property string $genre_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $creator
 * @property-read \App\Models\Genre $genre
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $lovedBy
 * @property-read int|null $loved_by_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 * @property-read \App\Models\User $owner
 * @method static \Illuminate\Database\Eloquent\Builder|Track newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Track newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Track query()
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereDaw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereGenreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereLyric($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereNftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereVisibility($value)
 */
	class Track extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
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
 * @property int|null $current_team_id
 * @property string|null $profile_photo_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $actions
 * @property-read int|null $actions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Track[] $favourite
 * @property-read int|null $favourite_count
 * @property-read string $profile_photo_url
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Track[] $tracks
 * @property-read int|null $tracks_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCurrentTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfilePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \Spatie\MediaLibrary\HasMedia, \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

namespace App\Models{
/**
 * App\Models\VirtualBalance
 *
 * @property string $id
 * @property string $address
 * @property int $balance
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder|VirtualBalance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VirtualBalance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VirtualBalance query()
 * @method static \Illuminate\Database\Eloquent\Builder|VirtualBalance whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VirtualBalance whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VirtualBalance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VirtualBalance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VirtualBalance whereUpdatedAt($value)
 */
	class VirtualBalance extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\VoteRequest
 *
 * @property string $id
 * @property string $election_id
 * @property string $voter_id
 * @property string $track_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Election $election
 * @property-read \App\Models\Track $track
 * @property-read \App\Models\User $voter
 * @method static \Illuminate\Database\Eloquent\Builder|VoteRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VoteRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VoteRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|VoteRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoteRequest whereElectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoteRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoteRequest whereTrackId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoteRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoteRequest whereVoterId($value)
 */
	class VoteRequest extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Votes
 *
 * @property string $id
 * @property string $voter_id
 * @property string $track_id
 * @property string $election_id
 * @property int $half_stars
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Election $election
 * @property-read \App\Models\Track $track
 * @property-read \App\Models\User $voter
 * @method static \Illuminate\Database\Eloquent\Builder|Votes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Votes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Votes query()
 * @method static \Illuminate\Database\Eloquent\Builder|Votes whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Votes whereElectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Votes whereHalfStars($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Votes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Votes whereTrackId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Votes whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Votes whereVoterId($value)
 */
	class Votes extends \Eloquent {}
}

