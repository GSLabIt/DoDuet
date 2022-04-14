<?php

use App\Models\User;
use Doinc\Wallet\Exceptions\CannotTransfer;
use Doinc\Wallet\Exceptions\InvalidWalletModelProvided;

if(!function_exists("payTransactionFee")) {
    /**
     * Pay for the transaction execution
     *
     * @param User $user
     * @return void
     * @throws Throwable
     * @throws CannotTransfer
     * @throws InvalidWalletModelProvided
     */
    function payTransactionFee(User $user): void
    {
        /** @var User $company */
        $company = User::query()->first();

        $user->transfer($company, "0.5");
    }
}

if(!function_exists("payChallengeParticipationFee")) {
    /**
     * Pay for the transaction execution
     *
     * @param User $user
     * @return void
     * @throws Throwable
     * @throws CannotTransfer
     * @throws InvalidWalletModelProvided
     */
    function payChallengeParticipationFee(User $user): void
    {
        /** @var User $company */
        $company = User::query()->first();

        $user->transfer($company, 100);
    }
}
