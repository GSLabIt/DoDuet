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
 * App\Models\Albums
 *
 * @property string $id
 * @property string $name
 * @property string $owner_id
 * @property string $creator_id
 * @property string|null $nft_id
 * @property string|null $cover_id
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comments[] $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\Covers|null $cover
 * @property-read \App\Models\User $creator
 * @property-read \App\Models\Explicits|null $explicit
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Mentions[] $mentions
 * @property-read int|null $mentions_count
 * @property-read \App\Models\User $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Reports[] $reports
 * @property-read int|null $reports_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Taggable[] $tags
 * @property-read int|null $tags_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tracks[] $tracks
 * @property-read int|null $tracks_count
 * @method static \Database\Factories\AlbumsFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Albums newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Albums newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Albums query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Albums whereCoverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Albums whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Albums whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Albums whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Albums whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Albums whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Albums whereNftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Albums whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Albums whereUpdatedAt($value)
 */
	class IdeHelperAlbums {}
}

namespace App\Models{
/**
 * App\Models\Challenges
 *
 * @property int $id
 * @property string $total_prize
 * @property float $first_prize_rate
 * @property string|null $first_place_id
 * @property float $second_prize_rate
 * @property string|null $second_place_id
 * @property float $third_prize_rate
 * @property string|null $third_place_id
 * @property float $treasury_rate
 * @property float $fee_rate
 * @property float $burning_rate
 * @property string|null $started_at
 * @property string|null $ended_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User|null $firstPlace
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ListeningRequest[] $listeningRequests
 * @property-read int|null $listening_requests_count
 * @property-read \App\Models\User|null $secondPlace
 * @property-read \App\Models\User|null $thirdPlace
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tracks[] $tracks
 * @property-read int|null $tracks_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Votes[] $votes
 * @property-read int|null $votes_count
 * @method static \Database\Factories\ChallengesFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Challenges newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Challenges newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Challenges query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Challenges whereBurningRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Challenges whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Challenges whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Challenges whereFeeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Challenges whereFirstPlaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Challenges whereFirstPrizeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Challenges whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Challenges whereSecondPlaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Challenges whereSecondPrizeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Challenges whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Challenges whereThirdPlaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Challenges whereThirdPrizeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Challenges whereTotalPrize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Challenges whereTreasuryRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Challenges whereUpdatedAt($value)
 */
	class IdeHelperChallenges {}
}

namespace App\Models{
/**
 * App\Models\Comments
 *
 * @property string $id
 * @property string $commentor_id
 * @property string $content
 * @property string $commentable_type
 * @property string $commentable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $commentable
 * @property-read \App\Models\User $commentor
 * @property-read \App\Models\Explicits|null $explicit
 * @method static \Database\Factories\CommentsFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comments newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comments newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comments query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comments whereCommentableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comments whereCommentableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comments whereCommentorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comments whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comments whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comments whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comments whereUpdatedAt($value)
 */
	class IdeHelperComments {}
}

namespace App\Models{
/**
 * App\Models\Covers
 *
 * @property string $id
 * @property string $name
 * @property string $ipfs_id
 * @property string|null $nft_id
 * @property string $owner_id
 * @property string $creator_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Albums|null $album
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comments[] $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\User $creator
 * @property-read \App\Models\Explicits|null $explicit
 * @property-read \App\Models\Ipfs $ipfs
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Mentions[] $mentions
 * @property-read int|null $mentions_count
 * @property-read \App\Models\User $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Reports[] $reports
 * @property-read int|null $reports_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Taggable[] $tags
 * @property-read int|null $tags_count
 * @property-read \App\Models\Tracks|null $track
 * @method static \Database\Factories\CoversFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Covers newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Covers newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Covers query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Covers whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Covers whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Covers whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Covers whereIpfsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Covers whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Covers whereNftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Covers whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Covers whereUpdatedAt($value)
 */
	class IdeHelperCovers {}
}

namespace App\Models{
/**
 * App\Models\Explicits
 *
 * @property string $id
 * @property string $explicit_content_type
 * @property string $explicit_content_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $explicitContent
 * @method static \Database\Factories\ExplicitsFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Explicits newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Explicits newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Explicits query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Explicits whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Explicits whereExplicitContentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Explicits whereExplicitContentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Explicits whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Explicits whereUpdatedAt($value)
 */
	class IdeHelperExplicits {}
}

namespace App\Models{
/**
 * App\Models\Follows
 *
 * @property string $id
 * @property string $follower_id
 * @property string $followed_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $followed
 * @property-read \App\Models\User $follower
 * @method static \Database\Factories\FollowsFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Follows newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Follows newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Follows query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Follows whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Follows whereFollowedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Follows whereFollowerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Follows whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Follows whereUpdatedAt($value)
 */
	class IdeHelperFollows {}
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
 * @method static \Database\Factories\FunctionalitiesFactory factory(...$parameters)
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
	class IdeHelperFunctionalities {}
}

namespace App\Models{
/**
 * App\Models\Hashtags
 *
 * @property string $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Taggable[] $tags
 * @property-read int|null $tags_count
 * @method static \Database\Factories\HashtagsFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hashtags newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hashtags newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hashtags query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hashtags whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hashtags whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hashtags whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hashtags whereUpdatedAt($value)
 */
	class IdeHelperHashtags {}
}

namespace App\Models{
/**
 * App\Models\Ipfs
 *
 * @property string $id
 * @property string $cid
 * @property int $encrypted
 * @property mixed $encryption_key
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Covers|null $cover
 * @property-read \App\Models\Tracks|null $track
 * @method static \Database\Factories\IpfsFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ipfs newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ipfs newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ipfs query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ipfs whereCid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ipfs whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ipfs whereEncrypted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ipfs whereEncryptionKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ipfs whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ipfs whereUpdatedAt($value)
 */
	class IdeHelperIpfs {}
}

namespace App\Models{
/**
 * App\Models\ListeningRequest
 *
 * @property string $id
 * @property string $voter_id
 * @property string $track_id
 * @property int|null $challenge_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Challenges|null $challenge
 * @property-read \App\Models\Tracks $track
 * @property-read \App\Models\User $voter
 * @method static \Database\Factories\ListeningRequestFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ListeningRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ListeningRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ListeningRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ListeningRequest whereChallengeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ListeningRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ListeningRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ListeningRequest whereTrackId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ListeningRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ListeningRequest whereVoterId($value)
 */
	class IdeHelperListeningRequest {}
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comments[] $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\User $creator
 * @property-read \App\Models\Explicits|null $explicit
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Mentions[] $mentions
 * @property-read int|null $mentions_count
 * @property-read \App\Models\User $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Reports[] $reports
 * @property-read int|null $reports_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Taggable[] $tags
 * @property-read int|null $tags_count
 * @property-read \App\Models\Tracks|null $track
 * @method static \Database\Factories\LyricsFactory factory(...$parameters)
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
	class IdeHelperLyrics {}
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
 * @method static \Database\Factories\MentionsFactory factory(...$parameters)
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
	class IdeHelperMentions {}
}

namespace App\Models{
/**
 * App\Models\Messages
 *
 * @property string $id
 * @property string $sender_id
 * @property string $receiver_id
 * @property string $content
 * @property string $read_at
 * @property string|null $sender_deleted_at
 * @property string|null $receiver_deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $receiver
 * @property-read \App\Models\Reports|null $reports
 * @property-read \App\Models\User $sender
 * @method static \Database\Factories\MessagesFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messages newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messages newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messages query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messages whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messages whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messages whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messages whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messages whereReceiverDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messages whereReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messages whereSenderDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messages whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messages whereUpdatedAt($value)
 */
	class IdeHelperMessages {}
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
	class IdeHelperPersonalAccessToken {}
}

namespace App\Models{
/**
 * App\Models\PersonalInformations
 *
 * @property string $id
 * @property string $owner_id
 * @property string|null $alias
 * @property mixed|null $mobile
 * @property string|null $profile_cover_path
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $owner
 * @method static \Database\Factories\PersonalInformationsFactory factory(...$parameters)
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
	class IdeHelperPersonalInformations {}
}

namespace App\Models{
/**
 * App\Models\PersonalLibraries
 *
 * @property string $id
 * @property string $owner_id
 * @property string $description
 * @property string $name
 * @property int $is_public
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tracks[] $tracks
 * @property-read int|null $tracks_count
 * @method static \Database\Factories\PersonalLibrariesFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonalLibraries newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonalLibraries newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonalLibraries query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonalLibraries whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonalLibraries whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonalLibraries whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonalLibraries whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonalLibraries whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonalLibraries whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonalLibraries whereUpdatedAt($value)
 */
	class IdeHelperPersonalLibraries {}
}

namespace App\Models{
/**
 * App\Models\Platforms
 *
 * @property string $id
 * @property mixed $name
 * @property mixed $domain
 * @property bool $is_public
 * @property bool $is_password_protected
 * @property string|null $password
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Functionalities[] $functionalities
 * @property-read int|null $functionalities_count
 * @method static \Database\Factories\PlatformsFactory factory(...$parameters)
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
	class IdeHelperPlatforms {}
}

namespace App\Models{
/**
 * App\Models\Questionnaire
 *
 * @property string $id
 * @property mixed $link
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Test[] $tests
 * @property-read int|null $tests_count
 * @method static \Database\Factories\QuestionnaireFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Questionnaire newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Questionnaire newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Questionnaire query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Questionnaire whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Questionnaire whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Questionnaire whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Questionnaire whereUpdatedAt($value)
 */
	class IdeHelperQuestionnaire {}
}

namespace App\Models{
/**
 * App\Models\ReportReasons
 *
 * @property string $id
 * @property string $reportable_type
 * @property string $name
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Reports[] $report
 * @property-read int|null $report_count
 * @method static \Database\Factories\ReportReasonsFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReportReasons newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReportReasons newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReportReasons query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReportReasons whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReportReasons whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReportReasons whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReportReasons whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReportReasons whereReportableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReportReasons whereUpdatedAt($value)
 */
	class IdeHelperReportReasons {}
}

namespace App\Models{
/**
 * App\Models\Reports
 *
 * @property string $id
 * @property string $reportable_type
 * @property string $reportable_id
 * @property string $reason_id
 * @property string $extra_informations
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\ReportReasons|null $reportReason
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $reportable
 * @method static \Database\Factories\ReportsFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reports newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reports newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reports query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reports whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reports whereExtraInformations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reports whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reports whereReasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reports whereReportableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reports whereReportableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reports whereUpdatedAt($value)
 */
	class IdeHelperReports {}
}

namespace App\Models{
/**
 * App\Models\Settings
 *
 * @property string $id
 * @property mixed $name
 * @property string $name_sig
 * @property mixed $type
 * @property string $type_sig
 * @property array $allowed_values
 * @property string $allowed_values_sig
 * @property bool $has_default_value
 * @property mixed $default_value
 * @property string $default_value_sig
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserSettings[] $userSettings
 * @property-read int|null $user_settings_count
 * @method static \Database\Factories\SettingsFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereAllowedValues($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereAllowedValuesSig($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereDefaultValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereDefaultValueSig($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereHasDefaultValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereNameSig($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereTypeSig($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereUpdatedAt($value)
 */
	class IdeHelperSettings {}
}

namespace App\Models{
/**
 * App\Models\SocialChannels
 *
 * @property string $id
 * @property string $name
 * @property string $safe_domain
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Socials[] $socials
 * @property-read int|null $socials_count
 * @method static \Database\Factories\SocialChannelsFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialChannels newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialChannels newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SocialChannels onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialChannels query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialChannels whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialChannels whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialChannels whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialChannels whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialChannels whereSafeDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialChannels whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SocialChannels withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SocialChannels withoutTrashed()
 */
	class IdeHelperSocialChannels {}
}

namespace App\Models{
/**
 * App\Models\Socials
 *
 * @property string $id
 * @property string $channel_id
 * @property string $link
 * @property int $is_public
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\SocialChannels|null $socialChannels
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserSettings[] $userSettings
 * @property-read int|null $user_settings_count
 * @method static \Database\Factories\SocialsFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Socials newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Socials newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Socials query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Socials whereChannelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Socials whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Socials whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Socials whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Socials whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Socials whereUpdatedAt($value)
 */
	class IdeHelperSocials {}
}

namespace App\Models{
/**
 * App\Models\Taggable
 *
 * @property string $hashtag_id
 * @property string $taggable_type
 * @property string $taggable_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $taggable
 * @method static \Database\Factories\TaggableFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taggable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taggable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taggable query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taggable whereHashtagId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taggable whereTaggableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taggable whereTaggableType($value)
 */
	class IdeHelperTaggable {}
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
 * @method static \Database\Factories\TestFactory factory(...$parameters)
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
	class IdeHelperTest {}
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
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\TestResultFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TestResult newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TestResult newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TestResult query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TestResult whereHasAnsweredQuestionnaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TestResult whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TestResult whereTestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TestResult whereTesterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TestResult whereUtilizations($value)
 */
	class IdeHelperTestResult {}
}

namespace App\Models{
/**
 * App\Models\Tips
 *
 * @property string $id
 * @property string $tipper_id
 * @property string $tipped_id
 * @property string $tip
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $tipped
 * @property-read \App\Models\User $tipper
 * @method static \Database\Factories\TipsFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tips newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tips newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tips query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tips whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tips whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tips whereTip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tips whereTippedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tips whereTipperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tips whereUpdatedAt($value)
 */
	class IdeHelperTips {}
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
 * @property string $ipfs_id
 * @property string|null $cover_id
 * @property string|null $lyric_id
 * @property string|null $album_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Albums|null $album
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Challenges[] $challenges
 * @property-read int|null $challenges_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comments[] $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\Covers|null $cover
 * @property-read \App\Models\User $creator
 * @property-read \App\Models\Explicits|null $explicit
 * @property-read \App\Models\Ipfs $ipfs
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PersonalLibraries[] $libraries
 * @property-read int|null $libraries_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ListeningRequest[] $listeningRequests
 * @property-read int|null $listening_requests_count
 * @property-read \App\Models\Lyrics|null $lyric
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Mentions[] $mentions
 * @property-read int|null $mentions_count
 * @property-read \App\Models\User $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Reports[] $reports
 * @property-read int|null $reports_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Taggable[] $tags
 * @property-read int|null $tags_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Votes[] $votes
 * @property-read int|null $votes_count
 * @method static \Database\Factories\TracksFactory factory(...$parameters)
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tracks whereIpfsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tracks whereLyricId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tracks whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tracks whereNftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tracks whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tracks whereUpdatedAt($value)
 */
	class IdeHelperTracks {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property string $id
 * @property mixed $name
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comments[] $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Albums[] $createdAlbums
 * @property-read int|null $created_albums_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Covers[] $createdCovers
 * @property-read int|null $created_covers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Lyrics[] $createdLyrics
 * @property-read int|null $created_lyrics_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tracks[] $createdTracks
 * @property-read int|null $created_tracks_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Challenges[] $firstPlaces
 * @property-read int|null $first_places_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Follows[] $followed
 * @property-read int|null $followed_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Follows[] $followers
 * @property-read int|null $followers_count
 * @property-read string $profile_photo_url
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PersonalLibraries[] $libraries
 * @property-read int|null $libraries_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ListeningRequest[] $listeningRequests
 * @property-read int|null $listening_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Mentions[] $mentioner
 * @property-read int|null $mentioner_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Mentions[] $mentions
 * @property-read int|null $mentions_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Albums[] $ownedAlbums
 * @property-read int|null $owned_albums_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Covers[] $ownedCovers
 * @property-read int|null $owned_covers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Lyrics[] $ownedLyrics
 * @property-read int|null $owned_lyrics_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tracks[] $ownedTracks
 * @property-read int|null $owned_tracks_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \App\Models\PersonalInformations|null $personalInformation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Messages[] $receivedMessages
 * @property-read int|null $received_messages_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tips[] $receivedTips
 * @property-read int|null $received_tips_count
 * @property-read \Doinc\Modules\Referral\Models\Referral|null $referral
 * @property-read \Illuminate\Database\Eloquent\Collection|\Doinc\Modules\Referral\Models\Referred[] $referred
 * @property-read int|null $referred_count
 * @property-read \Doinc\Modules\Referral\Models\Referred|null $referredBy
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Reports[] $reports
 * @property-read int|null $reports_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Challenges[] $secondPlaces
 * @property-read int|null $second_places_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Messages[] $sentMessages
 * @property-read int|null $sent_messages_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserSettings[] $settings
 * @property-read int|null $settings_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TestResult[] $testResults
 * @property-read int|null $test_results_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Challenges[] $thirdPlaces
 * @property-read int|null $third_places_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tips[] $tipped
 * @property-read int|null $tipped_count
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
	class IdeHelperUser {}
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
 * @method static \Database\Factories\UserSegmentsFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSegments newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSegments newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSegments query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSegments whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSegments whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSegments whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSegments whereUpdatedAt($value)
 */
	class IdeHelperUserSegments {}
}

namespace App\Models{
/**
 * App\Models\UserSettings
 *
 * @property string $id
 * @property string $owner_id
 * @property string $settings_id
 * @property \App\Models\Settings|null $setting
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Socials[] $socials
 * @property-read int|null $socials_count
 * @method static \Database\Factories\UserSettingsFactory factory(...$parameters)
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
	class IdeHelperUserSettings {}
}

namespace App\Models{
/**
 * App\Models\Votes
 *
 * @property string $id
 * @property string $voter_id
 * @property string $track_id
 * @property int $challenge_id
 * @property int $vote
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Challenges $challenge
 * @property-read \App\Models\Tracks $track
 * @property-read \App\Models\User $voter
 * @method static \Database\Factories\VotesFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Votes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Votes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Votes query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Votes whereChallengeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Votes whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Votes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Votes whereTrackId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Votes whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Votes whereVote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Votes whereVoterId($value)
 */
	class IdeHelperVotes {}
}

namespace App\Models{
/**
 * App\Models\Wallet
 *
 * @property string $id
 * @property string $owner_id
 * @property mixed $chain
 * @property mixed $private_key
 * @property mixed $public_key
 * @property mixed $seed
 * @property mixed $address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $owner
 * @method static \Database\Factories\WalletFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wallet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wallet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wallet query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wallet whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wallet whereChain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wallet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wallet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wallet whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wallet wherePrivateKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wallet wherePublicKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wallet whereSeed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wallet whereUpdatedAt($value)
 */
	class IdeHelperWallet {}
}

namespace Doinc\Modules\Referral\Models{
/**
 * Doinc\Modules\Referral\Models\Referral
 *
 * @property string $id
 * @property string $code
 * @property string $owner_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $owner
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Referral\Models\Referral newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Referral\Models\Referral newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Referral\Models\Referral query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Referral\Models\Referral whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Referral\Models\Referral whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Referral\Models\Referral whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Referral\Models\Referral whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Referral\Models\Referral whereUpdatedAt($value)
 */
	class IdeHelperReferral {}
}

namespace Doinc\Modules\Referral\Models{
/**
 * Doinc\Modules\Referral\Models\Referred
 *
 * @property string $id
 * @property string $referrer_id
 * @property string $referred_id
 * @property bool $is_redeemed
 * @property int $prize
 * @property \Illuminate\Support\Carbon|null $redeemed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $referred
 * @property-read \App\Models\User $referrer
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Referral\Models\Referred newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Referral\Models\Referred newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Referral\Models\Referred query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Referral\Models\Referred whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Referral\Models\Referred whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Referral\Models\Referred whereIsRedeemed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Referral\Models\Referred wherePrize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Referral\Models\Referred whereRedeemedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Referral\Models\Referred whereReferredId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Referral\Models\Referred whereReferrerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Referral\Models\Referred whereUpdatedAt($value)
 */
	class IdeHelperReferred {}
}

namespace Doinc\Modules\Settings\Models{
/**
 * Doinc\Modules\Settings\Models\Settings
 *
 * @property string $id
 * @property mixed|null $name
 * @property string $name_sig
 * @property mixed|null $type
 * @property string $type_sig
 * @property array $allowed_values
 * @property string $allowed_values_sig
 * @property bool $has_default_value
 * @property mixed|null $default_value
 * @property string $default_value_sig
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Doinc\Modules\Settings\Models\UserSettings[] $userSettings
 * @property-read int|null $user_settings_count
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Settings\Models\Settings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Settings\Models\Settings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Settings\Models\Settings query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Settings\Models\Settings whereAllowedValues($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Settings\Models\Settings whereAllowedValuesSig($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Settings\Models\Settings whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Settings\Models\Settings whereDefaultValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Settings\Models\Settings whereDefaultValueSig($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Settings\Models\Settings whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Settings\Models\Settings whereEncryptedIs(string $column_name, string $value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Settings\Models\Settings whereHasDefaultValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Settings\Models\Settings whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Settings\Models\Settings whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Settings\Models\Settings whereNameSig($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Settings\Models\Settings whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Settings\Models\Settings whereTypeSig($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Settings\Models\Settings whereUpdatedAt($value)
 */
	class IdeHelperSettings {}
}

namespace Doinc\Modules\Settings\Models{
/**
 * Doinc\Modules\Settings\Models\UserSettings
 *
 * @property string $id
 * @property string $owner_id
 * @property string $settings_id
 * @property \Doinc\Modules\Settings\Models\Settings|null $setting
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Settings\Models\UserSettings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Settings\Models\UserSettings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Settings\Models\UserSettings query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Settings\Models\UserSettings whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Settings\Models\UserSettings whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Settings\Models\UserSettings whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Settings\Models\UserSettings whereSetting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Settings\Models\UserSettings whereSettingsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Doinc\Modules\Settings\Models\UserSettings whereUpdatedAt($value)
 */
	class IdeHelperUserSettings {}
}

