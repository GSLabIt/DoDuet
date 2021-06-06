<?php

namespace App\Http\Controllers;

use App\Models\VirtualBalance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class VirtualBalanceController extends Controller
{
    public static function addListeningPrize(string $address) {
        $virtual_balance = VirtualBalance::firstOrCreate(["address" => $address], ["address" => $address]);
        $virtual_balance->update([
            "balance" => $virtual_balance->balance + (int)env("LISTENING_PRIZE")
        ]);
    }

    public static function addVotePrize(string $address) {
        $virtual_balance = VirtualBalance::firstOrCreate(["address" => $address], ["address" => $address]);
        $virtual_balance->update([
            "balance" => $virtual_balance->balance + (int)env("VOTING_PRIZE")
        ]);
    }

    public static function redeem(string $address) {
        // TODO: mint the balance of the address and reset to 0
    }

    /**
     * Retrieve the pending balance of the given address
     * @param string $address
     * @return int
     */
    public static function balanceOf(string $address): int
    {
        $virtual_balance = VirtualBalance::where("address", $address)->first();
        $balance = 0;

        if(!is_null($virtual_balance)) {
            $balance = $virtual_balance->balance;
        }

        return $balance;
    }

    /**
     * Retrieve the total pending balance
     * @return int
     */
    public static function totalBalance(): int
    {
        return VirtualBalance::all()->sum("balance");
    }
}
