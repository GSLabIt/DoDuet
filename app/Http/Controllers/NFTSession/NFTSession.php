<?php

namespace App\Http\Controllers\NFTSession;

class NFTSession
{
    public static string $PLAYING = "playing";
    public static string $PLAYING_TIME = "playing_url_elapse_time";
    public static string $NFT_ID = "nft_working_id";
    public static string $TMP_URL = "nft_temporary_listening_url";

    protected static function get(string $variable) {
        return session($variable);
    }

    protected static function has(string $variable): bool {
        return session()->has($variable);
    }

    public static function states(): NFTStates {
        return new NFTStates();
    }

    public static function times(): NFTTimes {
        return new NFTTimes();
    }

    public static function update(): NFTSessionUpdate {
        return new NFTSessionUpdate();
    }

    public static function canRequestNftTrackAccess(): bool
    {
        return (    // session variables not started yet so, no playing nor voting in progress
                self::states()->hasNotPlaying() &&
                self::times()->hasNotPlaying()
            ) ||
            (       // session variables started but actually not playing nor voting in progress
                self::states()->isNotPlaying()
            ) ||
            (       // session is in playing state but the playing time is elapsed and voting is elapsed or inactive
                self::states()->isPlaying() &&
                self::times()->isPlayingTimeElapsed()
            );
    }

    public static function canRequestNftVotingAccess(string $nft_id): bool {
        // states are initialized
        return self::states()->hasPlaying() &&
            self::times()->hasPlaying() &&

            // The vote request MUST be sent before the track validity time elapses otherwise the track must be listened again
            self::states()->isPlaying() &&
            self::times()->isNotPlayingTimeElapsed() &&
            self::states()->matchCurrentNft($nft_id);
    }

    public static function canAccessNft(string $nft_id): bool {
        // states are initialized
        return self::states()->hasPlaying() &&
            self::times()->hasPlaying() &&

            // check that the nft is in playing state
            self::states()->isPlaying() &&
            self::times()->isNotPlayingTimeElapsed() &&
            self::states()->matchCurrentNft($nft_id);
    }

    public static function canVoteNft(string $nft_id): bool {
        return self::states()->hasPlaying() &&
            self::times()->hasPlaying() &&

            // check that the nft is in voting state
            self::states()->matchCurrentNft($nft_id) &&

            // check that the nft is not in the playing state
            self::states()->isNotPlaying();
    }
}
