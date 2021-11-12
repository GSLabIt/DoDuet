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
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comments[] $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\Covers $cover
 * @property-read \App\Models\User $creator
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
 */
	class Albums extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Comments
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $commentable
 * @property-read \App\Models\User $commentor
 * @method static \Database\Factories\CommentsFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comments newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comments newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comments query()
 */
	class Comments extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Covers
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Albums|null $album
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comments[] $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\User $creator
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Mentions[] $mentions
 * @property-read int|null $mentions_count
 * @property-read \App\Models\User $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Reports[] $reports
 * @property-read int|null $reports_count
 * @property-read \App\Models\Skynet $skynet
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Taggable[] $tags
 * @property-read int|null $tags_count
 * @property-read \App\Models\Tracks|null $track
 * @method static \Database\Factories\CoversFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Covers newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Covers newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Covers query()
 */
	class Covers extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Elections
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $firstPlace
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ListeningRequest[] $listeningRequests
 * @property-read int|null $listening_requests_count
 * @property-read \App\Models\User $secondPlace
 * @property-read \App\Models\User $thirdPlace
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Votes[] $votes
 * @property-read int|null $votes_count
 * @method static \Database\Factories\ElectionsFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Elections newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Elections newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Elections query()
 */
	class Elections extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Explicits
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $explicitContent
 * @method static \Database\Factories\ExplicitsFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Explicits newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Explicits newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Explicits query()
 */
	class Explicits extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Follows
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $followed
 * @property-read \App\Models\User $follower
 * @method static \Database\Factories\FollowsFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Follows newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Follows newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Follows query()
 */
	class Follows extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Functionalities
 *
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
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Functionalities withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Functionalities withoutTrashed()
 */
	class Functionalities extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Hashtags
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Taggable[] $tags
 * @property-read int|null $tags_count
 * @method static \Database\Factories\HashtagsFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hashtags newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hashtags newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hashtags query()
 */
	class Hashtags extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ListeningRequest
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Elections $election
 * @property-read \App\Models\Tracks $track
 * @property-read \App\Models\User $voter
 * @method static \Database\Factories\ListeningRequestFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ListeningRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ListeningRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ListeningRequest query()
 */
	class ListeningRequest extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Lyrics
 *
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
 */
	class Lyrics extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Mentions
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $mentionable
 * @property-read \App\Models\User $mentioned
 * @property-read \App\Models\User $mentioner
 * @method static \Database\Factories\MentionsFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mentions newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mentions newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mentions query()
 */
	class Mentions extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Messages
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $receiver
 * @property-read \App\Models\Reports|null $reports
 * @property-read \App\Models\User $sender
 * @method static \Database\Factories\MessagesFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messages newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messages newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messages query()
 */
	class Messages extends \Eloquent {}
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $owner
 * @method static \Database\Factories\PersonalInformationsFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonalInformations newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonalInformations newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonalInformations query()
 */
	class PersonalInformations extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PersonalLibraries
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tracks[] $tracks
 * @property-read int|null $tracks_count
 * @method static \Database\Factories\PersonalLibrariesFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonalLibraries newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonalLibraries newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonalLibraries query()
 */
	class PersonalLibraries extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Platforms
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Functionalities[] $functionalities
 * @property-read int|null $functionalities_count
 * @method static \Database\Factories\PlatformsFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Platforms newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Platforms newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Platforms query()
 */
	class Platforms extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Questionnaire
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Test[] $tests
 * @property-read int|null $tests_count
 * @method static \Database\Factories\QuestionnaireFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Questionnaire newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Questionnaire newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Questionnaire query()
 */
	class Questionnaire extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Referral
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $owner
 * @method static \Database\Factories\ReferralFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Referral newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Referral newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Referral query()
 */
	class Referral extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Referred
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $referred
 * @property-read \App\Models\User $referrer
 * @method static \Database\Factories\ReferredFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Referred newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Referred newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Referred query()
 */
	class Referred extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ReportReasons
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Reports[] $report
 * @property-read int|null $report_count
 * @method static \Database\Factories\ReportReasonsFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReportReasons newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReportReasons newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReportReasons query()
 */
	class ReportReasons extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Reports
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\ReportReasons $reportReason
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $reportable
 * @method static \Database\Factories\ReportsFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reports newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reports newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reports query()
 */
	class Reports extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Settings
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserSettings[] $userSettings
 * @property-read int|null $user_settings_count
 * @method static \Database\Factories\SettingsFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings query()
 */
	class Settings extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Skynet
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Covers|null $cover
 * @property-read \App\Models\Tracks|null $track
 * @method static \Database\Factories\SkynetFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Skynet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Skynet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Skynet query()
 */
	class Skynet extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SocialChannels
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Socials[] $socials
 * @property-read int|null $socials_count
 * @method static \Database\Factories\SocialChannelsFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialChannels newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialChannels newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SocialChannels onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialChannels query()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SocialChannels withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SocialChannels withoutTrashed()
 */
	class SocialChannels extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Socials
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\SocialChannels $socialChannels
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserSettings[] $userSettings
 * @property-read int|null $user_settings_count
 * @method static \Database\Factories\SocialsFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Socials newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Socials newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Socials query()
 */
	class Socials extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Taggable
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $taggable
 * @method static \Database\Factories\TaggableFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taggable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taggable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taggable query()
 */
	class Taggable extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Test
 *
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
 */
	class Test extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TestResult
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Test $test
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\TestResultFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TestResult newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TestResult newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TestResult query()
 */
	class TestResult extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Tips
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $tipped
 * @property-read \App\Models\User $tipper
 * @method static \Database\Factories\TipsFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tips newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tips newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tips query()
 */
	class Tips extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Tracks
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Albums $album
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comments[] $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\Covers $cover
 * @property-read \App\Models\User $creator
 * @property-read \App\Models\Explicits|null $explicit
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PersonalLibraries[] $libraries
 * @property-read int|null $libraries_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ListeningRequest[] $listeningRequests
 * @property-read int|null $listening_requests_count
 * @property-read \App\Models\Lyrics $lyric
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Mentions[] $mentions
 * @property-read int|null $mentions_count
 * @property-read \App\Models\User $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Reports[] $reports
 * @property-read int|null $reports_count
 * @property-read \App\Models\Skynet $skynet
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Taggable[] $tags
 * @property-read int|null $tags_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Votes[] $votes
 * @property-read int|null $votes_count
 * @method static \Database\Factories\TracksFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tracks newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tracks newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tracks query()
 */
	class Tracks extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comments[] $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Covers[] $createdCovers
 * @property-read int|null $created_covers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Lyrics[] $createdLyrics
 * @property-read int|null $created_lyrics_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tracks[] $createdTracks
 * @property-read int|null $created_tracks_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Elections[] $firstPlaces
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
 * @property-read \App\Models\Referral|null $referral
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Referred[] $referred
 * @property-read int|null $referred_count
 * @property-read \App\Models\Referred|null $referredBy
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Reports[] $reports
 * @property-read int|null $reports_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Elections[] $secondPlaces
 * @property-read int|null $second_places_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Messages[] $sentMessages
 * @property-read int|null $sent_messages_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserSettings[] $settings
 * @property-read int|null $settings_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TestResult[] $testResults
 * @property-read int|null $test_results_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Elections[] $thirdPlaces
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
 * @method static \Database\Factories\UserSegmentsFactory factory(...$parameters)
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $owner
 * @property-read \App\Models\Settings $setting
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Socials[] $socials
 * @property-read int|null $socials_count
 * @method static \Database\Factories\UserSettingsFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSettings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSettings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSettings query()
 */
	class UserSettings extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Votes
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Elections $election
 * @property-read \App\Models\Tracks $track
 * @property-read \App\Models\User $voter
 * @method static \Database\Factories\VotesFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Votes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Votes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Votes query()
 */
	class Votes extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Wallet
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $owner
 * @method static \Database\Factories\WalletFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wallet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wallet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wallet query()
 */
	class Wallet extends \Eloquent {}
}

