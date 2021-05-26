<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ServeTracksNFTController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param Media $media
     * @return Media
     */
    public function getMedia(Request $request, Media $media): Media
    {
        // The signed middleware in front of this controller takes care of all the possible user tampering trials and
        // always return a 403 if modified from the original value
        return $media;
    }
}
