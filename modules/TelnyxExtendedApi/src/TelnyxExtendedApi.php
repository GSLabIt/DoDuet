<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\TelnyxExtendedApi;

use Doinc\Modules\TelnyxExtendedApi\Events\NumberLookup;
use Doinc\Modules\TelnyxExtendedApi\Exceptions\InvalidNumber;
use Doinc\Modules\TelnyxExtendedApi\Models\DTOs\TelnyxPhoneLookupRootDTO;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class TelnyxExtendedApi
{
    protected string $base_url = "https://api.telnyx.com/v2/";

    protected function baseRequest(): PendingRequest
    {
        return Http::withHeaders([
            "Content-Type" => "application/json",
            "Accept" => "application/json",
            "Authorization" => "Bearer " . config("laravel-telnyx.api_key")
        ]);
    }

    /**
     * Execute a number lookup on the provided telephone number.
     *
     * @param string $phone_number Phone number to check
     * @param bool $with_carrier Retrieve the carrier if present
     * @param bool $with_caller Retrieve the caller if present
     * @return  TelnyxPhoneLookupRootDTO
     * @throws UnknownProperties
     * @throws InvalidNumber
     */
    public function numberLookup(string $phone_number, bool $with_carrier = true, bool $with_caller = false): TelnyxPhoneLookupRootDTO
    {
        $options = [];
        if ($with_caller) {
            $options["type"] = "caller-name";
        }
        if ($with_carrier) {
            $options["type"] = "carrier";
        }

        // replace spaces with %20 and plus with %2B as otherwise the PHP builtin urlencode function encodes spaces as
        // plus and telnyx processor fucks up
        $phone_number = Str::replace(["+", " "], ["%2B", "%20"], $phone_number);
        $response = $this->baseRequest()->get("{$this->base_url}number_lookup/$phone_number", $options);

        // check for errors in response
        switch ($response->json("errors.0.title")) {
            case "Invalid parameter":
                throw new InvalidNumber();
        }

        $dto = new TelnyxPhoneLookupRootDTO($response->json());
        NumberLookup::dispatch($phone_number, $dto);

        return $dto;
    }
}
