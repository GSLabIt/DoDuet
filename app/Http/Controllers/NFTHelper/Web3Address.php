<?php


namespace App\Http\Controllers\NFTHelper;


use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Web3Address
{
    /**
     * Validate the provided address, returns true if validation passes, $error will contain eventual errors
     * @param string $address
     * @param $error
     * @return bool
     */
    public static function validateAddress(string $address, &$error): bool
    {
        // Validate the provided address
        $validator = Validator::make(
                [
                    "address" => $address
                ],
                [
                    "address" => "required|string|max:42|regex:/0x[A-Fa-f0-9]{40}/"
                ]);

        if(!$validator->passes()) {
            $error = $validator->errors()->get("address")[0];
        }
        return $validator->passes();
    }
}
