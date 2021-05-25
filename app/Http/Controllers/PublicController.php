<?php

namespace App\Http\Controllers;

use App\Models\Track;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * @param string $nft_id
     * @return JsonResponse
     */
    public function nftReference(string $nft_id): JsonResponse
    {
        $track = Track::where("nft_id", $nft_id)->first();
        if(!is_null($track)) {
            return response()->json([
                "name" => $track->name,
                "owner" => $track->owner->name,
                "description" => $track->description,
                "lyric" => $track->lyric,
                "daw" => $track->daw,
                "genre" => $track->genre->name,
                "duration" => $track->duration,
                "creator" => $track->creator->name,
            ]);
        }
        abort(404);
    }
}
