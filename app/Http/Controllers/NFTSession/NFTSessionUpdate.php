<?php


namespace App\Http\Controllers\NFTSession;


use Carbon\Carbon;

class NFTSessionUpdate extends NFTSession
{
    public function trackAccessedRequested(Carbon $url_elapse_time, string $nft_id, string $tmp_url) {
        session()->put(self::$PLAYING, true);
        session()->put(self::$PLAYING_TIME, $url_elapse_time->timestamp);
        session()->put(self::$NFT_ID, $nft_id);
        session()->put(self::$TMP_URL, $tmp_url);
    }

    public function trackVoteRequested() {
        session()->put(self::$PLAYING, false);
        session()->put(self::$PLAYING_TIME, null);
    }

    /**
     * #############################################################################################################
     * ##   ALERT: This function should never land in a production environment as it will bypass all the checks   ##
     * #############################################################################################################
     */
    public function globalBypass() {
        session()->put(self::$PLAYING, false);
        session()->put(self::$PLAYING_TIME, null);
        session()->forget(self::$NFT_ID);
    }
}
