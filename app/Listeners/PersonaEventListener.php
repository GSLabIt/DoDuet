<?php

namespace App\Listeners;

use App\Models\PersonaAccount;
use App\Models\PersonaInquiry;
use App\Models\PersonaVerification;
use App\Models\User;
use Carbon\Carbon;
use Doinc\PersonaKyc\Models\Account;
use Doinc\PersonaKyc\Models\Inquiry;
use Doinc\PersonaKyc\Models\Verification;

class PersonaEventListener
{
    // avoid duplicate code
    private function personaVerificationUpdateOrCreate($event): void
    {
        PersonaVerification::updateOrCreate(
            [ // research and optionally merged for create params
                "persona_id" => $event->id,
                "created_at" => Carbon::createFromTimestamp($event->created_at_ts),
            ],
            [ // update params
                "status" => $event->status,
                "inquiry_id" => $event->relationships->inquiry->id,
                "submitted_at" => Carbon::createFromTimestamp($event->submitted_at_ts),
                "completed_at" => Carbon::createFromTimestamp($event->completed_at_ts),
                "country_code" => $event->country_code,
                "entity_confidence_score" => $event->entity_confidence_score,
                "document_similarity_score" => $event->document_similarity_score,
                "entity_confidence_reasons" => $event->entity_confidence_reasons,
                "photo_url" => json_encode([
                    "front" => $event->front_photo_url,
                    "back" => $event->back_photo_url,
                    "left" => $event->left_photo_url,
                    "center" => $event->center_photo_url,
                    "right" => $event->right_photo_url,
                ]),
                "government_id_class" => $event->id_class,
                "capture_method" => $event->capture_method,
            ]
        );
    }

    /**
     * Handle verification passed persona events.
     */
    public function onVerificationPassedEvent(Verification $event): void
    {
        $this->personaVerificationUpdateOrCreate($event);
        PersonaInquiry::where("persona_id", $event->id)->first()->user()
            ->update([
                "persona_verified_at" => Carbon::createFromTimestamp($event->completed_at_ts),
            ]);
    }

    /**
     * Handle account persona events.
     */
    public function onAccountEvent(Account $event): void
    {
        PersonaAccount::updateOrCreate(
            [ // research and optionally merged for create params
                "persona_id" => $event->id,
                "reference_id" => $event->refererence_id,
                "created_at" => $event->created_at
            ],
            [  // update params
                    "updated_at" => $event->updated_at
            ]
        );
    }

    /**
     * Handle verification persona events.
     */
    public function onVerificationEvent(Verification $event): void
    {
        $this->personaVerificationUpdateOrCreate($event);
    }

    /**
     * Handle inquiry persona events.
     */
    public function onInquiryEvent(Inquiry $event): void
    {
        PersonaInquiry::updateOrCreate(
            [ // research and optionally merged for create params
                "id" => $event->id,
                "reference_id" => $event->reference_id,
                "created_at" => $event->created_at,
            ],
            [ // update params
                "status" => $event->status,
                "started_at" => $event->started_at,
                "completed_at" => $event->completed_at,
                "failed_at" => $event->failed_at,
                "decisioned_at" => $event->decisioned_at,
                "expired_at" => $event->expired_at,
                "redacted_at" => $event->redacted_at,
            ]
        );
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
            'App\Events\AccountArchived',
            'App\Listeners\PersonaEventListener@onAccountEvent'
        );
        $events->listen( // creation event
            'App\Events\AccountCreated',
            'App\Listeners\PersonaEventListener@onAccountEvent'
        );
        $events->listen(
            'App\Events\AccountRedacted',
            'App\Listeners\PersonaEventListener@onAccountEvent'
        );
        $events->listen(
            'App\Events\AccountRestored',
            'App\Listeners\PersonaEventListener@onAccountEvent'
        );
        $events->listen(
            'App\Events\AccountMerged',
            'App\Listeners\PersonaEventListener@onAccountEvent'
        );
        $events->listen(
            'App\Events\AccountTagAdded',
            'App\Listeners\PersonaEventListener@onAccountEvent'
        );
        $events->listen(
            'App\Events\AccountTagRemoved',
            'App\Listeners\PersonaEventListener@onAccountEvent'
        );

        // verification events
        $events->listen( // creation event
            'App\Events\VerificationCreated',
            'App\Listeners\PersonaEventListener@onVerificationEvent'
        );
        $events->listen( // special event account verified
            'App\Events\VerificationPassed',
            'App\Listeners\PersonaEventListener@onVerificationPassedEvent'
        );
        $events->listen(
            'App\Events\VerificationFailed',
            'App\Listeners\PersonaEventListener@onVerificationEvent'
        );
        $events->listen(
            'App\Events\VerificationRequiresRetry',
            'App\Listeners\PersonaEventListener@onVerificationEvent'
        );
        $events->listen(
            'App\Events\VerificationCanceled',
            'App\Listeners\PersonaEventListener@onVerificationEvent'
        );

        // inquiry events
        $events->listen( // creation event
            'App\Events\InquiryCreated',
            'App\Listeners\PersonaEventListener@onInquiryEvent'
        );
        $events->listen(
            'App\Events\InquiryStarted',
            'App\Listeners\PersonaEventListener@onInquiryEvent'
        );
        $events->listen(
            'App\Events\InquiryExpired',
            'App\Listeners\PersonaEventListener@onInquiryEvent'
        );
        $events->listen(
            'App\Events\InquiryCompleted',
            'App\Listeners\PersonaEventListener@onInquiryEvent'
        );
        $events->listen(
            'App\Events\InquiryFailed',
            'App\Listeners\PersonaEventListener@onInquiryEvent'
        );
        $events->listen(
            'App\Events\InquiryMarkedForReview',
            'App\Listeners\PersonaEventListener@onInquiryEvent'
        );
        $events->listen(
            'App\Events\InquiryApproved',
            'App\Listeners\PersonaEventListener@onInquiryEvent'
        );
        $events->listen(
            'App\Events\InquiryDeclined',
            'App\Listeners\PersonaEventListener@onInquiryEvent'
        );
        $events->listen(
            'App\Events\InquiryTransitioned',
            'App\Listeners\PersonaEventListener@onInquiryEvent'
        );
    }
}
