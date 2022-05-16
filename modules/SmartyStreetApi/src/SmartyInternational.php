<?php

namespace Doinc\Modules\SmartyStreetApi;

use Doinc\Modules\SmartyStreetApi\Enums\CountriesISO3;
use Doinc\Modules\SmartyStreetApi\Enums\InternationalUrl;
use Doinc\Modules\SmartyStreetApi\Exceptions\IncorrectCredentials;
use Doinc\Modules\SmartyStreetApi\Exceptions\LimitedInternationalService;
use Doinc\Modules\SmartyStreetApi\Exceptions\MalformedInput;
use Doinc\Modules\SmartyStreetApi\Exceptions\MissingRequiredFields;
use Doinc\Modules\SmartyStreetApi\Exceptions\NoActiveSubscription;
use Doinc\Modules\SmartyStreetApi\Exceptions\SmartyTooSlow;
use Doinc\Modules\SmartyStreetApi\Exceptions\TooManyRequests;
use Doinc\Modules\SmartyStreetApi\Models\DTOs\SmartyRootDTO;
use Illuminate\Support\Facades\Http;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class SmartyInternational
{
    protected string $auth_id;
    protected string $auth_token;

    /**
     * Init the request parameters
     *
     * @param string $auth_id
     * @param string $auth_token
     * @return static
     */
    public function init(string $auth_id, string $auth_token): static
    {
        $this->auth_id = $auth_id;
        $this->auth_token = $auth_token;
        return $this;
    }

    /**
     * Request the verification of an address
     *
     * @param CountriesISO3 $country
     * @param string $address
     * @param string $locality
     * @param string $administrative_area
     * @param string $zip
     * @return SmartyRootDTO
     * @throws IncorrectCredentials
     * @throws LimitedInternationalService
     * @throws MalformedInput
     * @throws MissingRequiredFields
     * @throws NoActiveSubscription
     * @throws SmartyTooSlow
     * @throws TooManyRequests
     * @throws UnknownProperties
     */
    public function verify(
        CountriesISO3 $country,
        string $address,
        string $locality,
        string $administrative_area,
        string $zip
    ): SmartyRootDTO
    {
        $response = Http::get(InternationalUrl::BASE_URL->value . InternationalUrl::VERIFY->value, [
            "auth-id" => $this->auth_id,
            "auth-token" => $this->auth_token,
            "country" => $country->name,
            "geocode" => true,
            "language" => "latin",
            "address1" => $address,
            "locality" => $locality,
            "administrative_area" => $administrative_area,
            "postal_code" => $zip,
        ]);

        switch ($response->status()) {
            case 400:
                throw new MalformedInput();
            case 401:
                throw new IncorrectCredentials();
            case 402:
                throw new NoActiveSubscription();
            case 403:
                throw new LimitedInternationalService();
            case 422:
                throw new MissingRequiredFields();
            case 429:
                throw new TooManyRequests();
            case 504:
                throw new SmartyTooSlow();
            default:
                break;
        }

        return new SmartyRootDTO($response->json()[0]);
    }
}
