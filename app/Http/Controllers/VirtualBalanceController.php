<?php

namespace App\Http\Controllers;

use App\Models\VirtualBalance;
use Illuminate\Http\Request;

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
}
