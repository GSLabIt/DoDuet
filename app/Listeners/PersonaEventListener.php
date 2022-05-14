<?php

namespace App\Listeners;

use App\Models\PersonaAccount;
use App\Models\PersonaInquiry;
use App\Models\PersonaVerification;
use App\Models\User;
use Carbon\Carbon;
use Doinc\PersonaKyc\Events\VerificationSubmitted;
use Doinc\PersonaKyc\Models\Account;
use Doinc\PersonaKyc\Models\Inquiry;
use Doinc\PersonaKyc\Models\Verification;
use Doinc\PersonaKyc\Persona;

class PersonaEventListener
{
    // avoid duplicate code
    private function personaInquiryUpdateOrCreate($inquiry): void
    {
        PersonaInquiry::updateOrInsert(
            [ // research and optionally merged for create params
                "persona_id" => $inquiry->id
            ],
            [ // update params
                "reference_id" => $inquiry->reference_id,
                "created_at" => $inquiry->created_at,
                "status" => $inquiry->status,
                "started_at" => $inquiry->started_at,
                "completed_at" => $inquiry->completed_at,
                "failed_at" => $inquiry->failed_at,
                "decisioned_at" => $inquiry->decisioned_at,
                "expired_at" => $inquiry->expired_at,
                "redacted_at" => $inquiry->redacted_at,
            ]
        );
    }

    /**
     * Handle verification passed persona events.
     */
    public function onInquiryApprovedEvent($event): void
    {
        /** @var Inquiry $inquiry */
        $inquiry = $event->inquiry;
        $this->personaInquiryUpdateOrCreate($inquiry);
        User::where("id", $inquiry->reference_id)
            ->update([
                "persona_verified_at" => $inquiry->decisioned_at,
            ]);
    }

    /**
     * Handle account persona events.
     */
    public function onAccountEvent($event): void
    {
        /** @var Account $account */
        $account = $event->account;
        PersonaAccount::updateOrInsert(
            [ // research and optionally merged for create params
                "persona_id" => $account->id,
                "reference_id" => $account->reference_id
            ],
            [  // update params
                    "created_at" => $account->created_at,
                    "updated_at" => $account->updated_at
            ]
        );
    }

    /**
     * Handle verification persona events.
     */
    public function onVerificationEvent($event): void
    {
        /** @var Verification $verification */
        $verification = $event->verification;
        $inquiry_id = $verification->relationships->inquiry->id;
        // if the inquiry is not already registered, request data from persona
        if (is_null(PersonaInquiry::where("persona_id", $inquiry_id)->first())) {
            // call the function to create the inquiry with the data got from Persona.
            $this->personaInquiryUpdateOrCreate(Persona::init()->inquiries()->get($verification->relationships->inquiry->id));
        }
        PersonaVerification::updateOrInsert(
            [ // research and optionally merged for create params
                "persona_id" => $verification->id,
            ],
            [ // update params
                "status" => $verification->status,
                "inquiry_id" => $inquiry_id,
                "created_at" => Carbon::createFromTimestamp($verification->created_at_ts)->toDateTimeString(),
                "submitted_at" => Carbon::createFromTimestamp($verification->submitted_at_ts)->toDateTimeString(),
                "completed_at" => Carbon::createFromTimestamp($verification->completed_at_ts)->toDateTimeString(),
                "country_code" => $verification->country_code,
                "entity_confidence_score" => $verification->entity_confidence_score,
                "document_similarity_score" => $verification->document_similarity_score,
                "entity_confidence_reasons" => json_encode($verification->entity_confidence_reasons),
                "photo_url" => json_encode([
                    "front" => $verification->front_photo_url,
                    "back" => $verification->back_photo_url,
                    "left" => $verification->left_photo_url,
                    "center" => $verification->center_photo_url,
                    "right" => $verification->right_photo_url,
                ]),
                "government_id_class" => $verification->id_class,
                "capture_method" => $verification->capture_method,
            ]
        );
    }

    /**
     * Handle inquiry persona events.
     */
    public function onInquiryEvent($event): void
    {

        /** @var Inquiry $inquiry */
        $inquiry = $event->inquiry;
        $this->personaInquiryUpdateOrCreate($inquiry);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events): void
    {
        // account events
        $events->listen(
            'Doinc\PersonaKyc\Events\AccountArchived',
            'App\Listeners\PersonaEventListener@onAccountEvent'
        );
        $events->listen( // creation event
            'Doinc\PersonaKyc\Events\AccountCreated',
            'App\Listeners\PersonaEventListener@onAccountEvent'
        );
        $events->listen(
            'Doinc\PersonaKyc\Events\AccountRedacted',
            'App\Listeners\PersonaEventListener@onAccountEvent'
        );
        $events->listen(
            'Doinc\PersonaKyc\Events\AccountRestored',
            'App\Listeners\PersonaEventListener@onAccountEvent'
        );
        $events->listen(
            'Doinc\PersonaKyc\Events\AccountMerged',
            'App\Listeners\PersonaEventListener@onAccountEvent'
        );
        $events->listen(
            'Doinc\PersonaKyc\Events\AccountTagAdded',
            'App\Listeners\PersonaEventListener@onAccountEvent'
        );
        $events->listen(
            'Doinc\PersonaKyc\Events\AccountTagRemoved',
            'App\Listeners\PersonaEventListener@onAccountEvent'
        );

        // verification events
        $events->listen( // creation event
            'Doinc\PersonaKyc\Events\VerificationCreated',
            'App\Listeners\PersonaEventListener@onVerificationEvent'
        );
        $events->listen(
            'Doinc\PersonaKyc\Events\VerificationSubmitted',
            'App\Listeners\PersonaEventListener@onVerificationEvent'
        );
        $events->listen(
            'Doinc\PersonaKyc\Events\VerificationPassed',
            'App\Listeners\PersonaEventListener@onVerificationEvent'
        );
        $events->listen(
            'Doinc\PersonaKyc\Events\VerificationFailed',
            'App\Listeners\PersonaEventListener@onVerificationEvent'
        );
        $events->listen(
            'Doinc\PersonaKyc\Events\VerificationRequiresRetry',
            'App\Listeners\PersonaEventListener@onVerificationEvent'
        );
        $events->listen(
            'Doinc\PersonaKyc\Events\VerificationCanceled',
            'App\Listeners\PersonaEventListener@onVerificationEvent'
        );

        // inquiry events
        $events->listen( // creation event
            'Doinc\PersonaKyc\Events\InquiryCreated',
            'App\Listeners\PersonaEventListener@onInquiryEvent'
        );
        $events->listen(
            'Doinc\PersonaKyc\Events\InquiryStarted',
            'App\Listeners\PersonaEventListener@onInquiryEvent'
        );
        $events->listen(
            'Doinc\PersonaKyc\Events\InquiryExpired',
            'App\Listeners\PersonaEventListener@onInquiryEvent'
        );
        $events->listen(
            'Doinc\PersonaKyc\Events\InquiryCompleted',
            'App\Listeners\PersonaEventListener@onInquiryEvent'
        );
        $events->listen(
            'Doinc\PersonaKyc\Events\InquiryFailed',
            'App\Listeners\PersonaEventListener@onInquiryEvent'
        );
        $events->listen(
            'Doinc\PersonaKyc\Events\InquiryMarkedForReview',
            'App\Listeners\PersonaEventListener@onInquiryEvent'
        );
        $events->listen( // special event account verified
            'Doinc\PersonaKyc\Events\InquiryApproved',
            'App\Listeners\PersonaEventListener@onInquiryApprovedEvent'
        );
        $events->listen(
            'Doinc\PersonaKyc\Events\InquiryDeclined',
            'App\Listeners\PersonaEventListener@onInquiryEvent'
        );
        $events->listen(
            'Doinc\PersonaKyc\Events\InquiryTransitioned',
            'App\Listeners\PersonaEventListener@onInquiryEvent'
        );
    }
}
