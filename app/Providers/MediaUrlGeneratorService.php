<?php

namespace App\Providers;

use DateTimeInterface;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Spatie\MediaLibrary\Support\UrlGenerator\DefaultUrlGenerator;

class MediaUrlGeneratorService extends DefaultUrlGenerator
{
    public function getTemporaryUrl(DateTimeInterface $expiration, array $options = []): string
    {
        return URL::temporarySignedRoute(
            'nft_private_storage',
            $expiration,
            array_merge(['media' => $this->media, 'filename' => $this->media->name], $options)
        );
    }
}
