<?php

namespace App\Http\Wrappers;

use App\Http\Wrappers\Interfaces\WorkerWrapper;
use App\Http\Wrappers\Interfaces\Wrapper;
use App\Http\Wrappers\Traits\WrapWorker;
use App\Models\Covers;
use App\Models\Lyrics;
use App\Models\Mentions;
use App\Models\PersonalInformations;
use App\Models\Tracks;
use App\Models\User;
use App\Notifications\UserMentionedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Regex\Exceptions\RegexFailed;
use Spatie\Regex\Regex;

class MentionWrapper implements Wrapper, WorkerWrapper
{
    use WrapWorker;

    private User $user;

    /**
     * Initialize the class instance
     *
     * @param User|Request $initializer
     * @return MentionWrapper|null
     */
    public static function init($initializer): ?MentionWrapper
    {
        // check if init method was called with an already created user model instance or if it is passed directly
        // from a request
        if($initializer instanceof User) {
            // init a new instance of the class and finally call the method with the user instance
            return (new static)->initWithUser($initializer);
        }
        elseif($initializer instanceof Request) {
            // init a new instance of the class and finally call the method with the request instance
            return (new static)->initWithRequest($initializer);
        }
        return null;
    }

    /**
     * Initialize the wrapper with a user instance
     *
     * @param User $user
     * @return $this
     */
    private function initWithUser(User $user): static
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Initialize the wrapper with a request instance
     *
     * @param Request $request
     * @return $this
     */
    private function initWithRequest(Request $request): static
    {
        $this->user = $request->user();
        return $this;
    }

    /**
     * Execute an operation.
     * The mention wrapper always executes the extraction of mentions from a string
     *
     * @param string $operation
     * @param $data
     * @return bool
     */
    public function run($data, string $operation = "default"): bool
    {
        $executed = false;

        // ensure the provided argument is an array of 2 elems with the instance of the mentionable class and the string
        // from where extract eventual mentions
        if(is_array($data) && count($data) === 2 && $this->isValidMorph($data[0]) && is_string($data[1])) {
            $executed = true;

            // matches every string starting with @ and followed by some text and that is not immediately preceded by
            // some text:
            // @test        -> matches
            // .@test       -> matches
            // email@test   -> does not match
            // e5@test      -> does not match
            $mentions = Regex::matchAll("/((?<!\w)@\w+)/", $data[1]);

            // ensure there is at least one mention
            if($mentions->hasMatch()) {
                $raw_mentions = collect();

                // retrieve the mentions and unwrap them from their class
                foreach ($mentions->results() as $mention) {
                    try {
                        $raw_mentions->push($mention->group(1));
                    }
                    catch (RegexFailed) {
                        break;
                    }
                }

                // retrieve unique values only and reindex the array
                $unique_mentions = $raw_mentions->uniqueStrict()->values();

                // start working on each mention
                foreach ($unique_mentions as $mention) {
                    // retrieve the alias and the mentioned user
                    $user_alias = Str::replace("@", "", $mention);
                    $mentioned = PersonalInformations::where("alias", $user_alias)->first();

                    // ensure the user exists
                    if(!is_null($mentioned)) {
                        $mentioned = $mentioned->owner;

                        // create the mention
                        /**@var Mentions $mention_instance*/
                        $mention_instance = $this->user->mentioner()->create([
                            "mentioned_id" => $mentioned->id,
                            "mentionable_type" => $data[0]::class,
                            "mentionable_id" => $data[0]->id
                        ]);

                        // send the mention notification to the user
                        $mentioned->notify(new UserMentionedNotification($mention_instance));
                    }
                }
            }
        }

        return $executed;
    }

    /**
     *
     * TODO: Missing model instances of morph relation, the checks must be implemented for every mentionable model
     *
     * @param $morph
     * @return bool
     */
    public function isValidMorph($morph): bool {
        return is_object($morph) && (
                $morph instanceof Tracks ||
                $morph instanceof Covers ||
                $morph instanceof Lyrics /*||
                $morph instanceof Albums*/
            );
    }
}
