<?php
namespace App\Http\Controllers\NodeBackEnd;

use Illuminate\Http\Client\Response;

class Endpoints extends NodeBackEnd
{
    public string $VOTE_REQUEST_ENDPOINT = "vote/request";

    private static function forgeUrl(string $endpoint): string
    {
        return self::$BASE_URL . $endpoint;
    }

    protected static function get(string $endpoint, array $parameters): Response {
        return parent::get(self::forgeUrl($endpoint), $parameters);
    }

    protected static function post(string $endpoint, array $parameters): Response {
        return parent::post(self::forgeUrl($endpoint), $parameters);
    }

    public function sendVoteRequest(string $address, string $nft) {
        self::post($this->VOTE_REQUEST_ENDPOINT, [
            "address" => $address,
            "nft" => $nft
        ]);
    }
}
