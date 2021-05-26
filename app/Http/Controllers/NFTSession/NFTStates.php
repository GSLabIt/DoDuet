<?php


namespace App\Http\Controllers\NFTSession;


class NFTStates extends NFTSession
{
    public function getPlaying(): bool {
        return self::get(self::$PLAYING);
    }

    public function hasPlaying(): bool
    {
        return self::has(self::$PLAYING);
    }

    public function hasNotPlaying(): bool
    {
        return !$this->hasPlaying();
    }

    public function isPlaying(): bool {
        return $this->getPlaying();
    }

    public function isNotPlaying(): bool {
        return !$this->getPlaying();
    }

    public function matchCurrentNft(string $nft_id): bool
    {
        return self::get(self::$NFT_ID) === $nft_id;
    }
}
