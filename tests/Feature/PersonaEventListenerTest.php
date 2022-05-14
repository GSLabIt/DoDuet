<?php

namespace Tests\Feature;

use App\Enums\PersonaTemplates;
use App\Models\PersonaAccount;
use App\Models\User;
use Carbon\Carbon;
use Doinc\PersonaKyc\Events\AccountArchived;
use Doinc\PersonaKyc\Events\AccountCreated;
use Doinc\PersonaKyc\Events\AccountMerged;
use Doinc\PersonaKyc\Events\AccountRedacted;
use Doinc\PersonaKyc\Events\AccountRestored;
use Doinc\PersonaKyc\Events\AccountTagAdded;
use Doinc\PersonaKyc\Events\AccountTagRemoved;
use Doinc\PersonaKyc\Events\InquiryApproved;
use Doinc\PersonaKyc\Events\InquiryCompleted;
use Doinc\PersonaKyc\Events\InquiryCreated;
use Doinc\PersonaKyc\Events\InquiryDeclined;
use Doinc\PersonaKyc\Events\InquiryExpired;
use Doinc\PersonaKyc\Events\InquiryFailed;
use Doinc\PersonaKyc\Events\InquiryMarkedForReview;
use Doinc\PersonaKyc\Events\InquiryStarted;
use Doinc\PersonaKyc\Events\InquiryTransitioned;
use Doinc\PersonaKyc\Events\VerificationCanceled;
use Doinc\PersonaKyc\Events\VerificationCreated;
use Doinc\PersonaKyc\Events\VerificationFailed;
use Doinc\PersonaKyc\Events\VerificationPassed;
use Doinc\PersonaKyc\Events\VerificationRequiresRetry;
use Doinc\PersonaKyc\Events\VerificationSubmitted;
use Doinc\PersonaKyc\Models\Account;
use Doinc\PersonaKyc\Models\Inquiry;
use Doinc\PersonaKyc\Models\Verification;
use Doinc\PersonaKyc\Persona;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonaEventListenerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Set up function.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->refreshDatabase();
        $this->seed();
    }


    /**
     *
     *  Testing Account events
     *
     */

    /**
     * Short function to help create Account models.
     *
     * @return Account
     */
    private function createAccount($reference_id, $created_at, $updated_at): Account
    {
        return Account::from([
            "data" => [
                "type" => "account",
                "id" => "act_wAXJNnZfnsDjrfP3h4nbrAkk",
                "attributes" => [
                    "reference_id" => $reference_id,
                    "created_at" => $created_at,
                    "updated_at" => $updated_at,
                    "tags" => []
                ]
            ]
        ]);
    }

    /**
     * Test the AccountCreated event.
     *
     * @return void
     */
    public function test_account_created_event()
    {
        /** @var User $user */
        $user = User::factory()->create();

        AccountCreated::dispatch($this->createAccount(
            $user->id,
            Carbon::now()->toDateTimeString(),
            Carbon::now()->toDateTimeString()
        ));

        $this->assertDatabaseHas("persona_accounts", ["reference_id" => $user->id]);
    }

    /**
     * Test the AccountArchived event.
     *
     * @return void
     */
    public function test_account_archived_event()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $created_at = Carbon::now()->toDateTimeString();

        AccountCreated::dispatch($this->createAccount(
            $user->id,
            $created_at,
            $created_at
        ));

        $updated_at = Carbon::now()->addMinute()->toDateTimeString();

        AccountArchived::dispatch($this->createAccount(
            $user->id,
            $created_at,
            $updated_at
        ));

        $this->assertDatabaseHas("persona_accounts", ["updated_at" => $updated_at]);
    }


    /**
     * Test the AccountRedacted event.
     *
     * @return void
     */
    public function test_account_redacted_event()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $created_at = Carbon::now()->toDateTimeString();

        AccountCreated::dispatch($this->createAccount(
            $user->id,
            $created_at,
            $created_at
        ));

        $updated_at = Carbon::now()->addMinute()->toDateTimeString();

        AccountRedacted::dispatch($this->createAccount(
            $user->id,
            $created_at,
            $updated_at
        ));

        $this->assertDatabaseHas("persona_accounts", ["updated_at" => $updated_at]);
    }

    /**
     * Test the AccountRestored event.
     *
     * @return void
     */
    public function test_account_restored_event()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $created_at = Carbon::now()->toDateTimeString();

        AccountCreated::dispatch($this->createAccount(
            $user->id,
            $created_at,
            $created_at
        ));

        $updated_at = Carbon::now()->addMinute()->toDateTimeString();

        AccountRestored::dispatch($this->createAccount(
            $user->id,
            $created_at,
            $updated_at
        ));

        $this->assertDatabaseHas("persona_accounts", ["updated_at" => $updated_at]);
    }

    /**
     * Test the AccountMerged event.
     *
     * @return void
     */
    public function test_account_merged_event()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $created_at = Carbon::now()->toDateTimeString();

        AccountCreated::dispatch($this->createAccount(
            $user->id,
            $created_at,
            $created_at
        ));

        $updated_at = Carbon::now()->addMinute()->toDateTimeString();

        AccountMerged::dispatch($this->createAccount(
            $user->id,
            $created_at,
            $updated_at
        ));

        $this->assertDatabaseHas("persona_accounts", ["updated_at" => $updated_at]);
    }


    /**
     * Test the AccountTagAdded event and try even without a previous instance.
     *
     * @return void
     */
    public function test_account_tag_added_event()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $this->assertDatabaseMissing("persona_accounts", ["reference_id" => $user->id]);

        $created_at = Carbon::now()->toDateTimeString();

        AccountTagAdded::dispatch($this->createAccount(
            $user->id,
            $created_at,
            $created_at
        ));

        $this->assertDatabaseHas("persona_accounts", ["reference_id" => $user->id]);
    }


    /**
     * Test the AccountTagRemoved event.
     *
     * @return void
     */
    public function test_account_tag_removed_event()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $created_at = Carbon::now()->toDateTimeString();

        AccountCreated::dispatch($this->createAccount(
            $user->id,
            $created_at,
            $created_at
        ));

        $updated_at = Carbon::now()->addMinute()->toDateTimeString();

        AccountTagRemoved::dispatch($this->createAccount(
            $user->id,
            $created_at,
            $updated_at
        ));

        $this->assertDatabaseHas("persona_accounts", ["updated_at" => $updated_at]);
    }


    /**
     *
     *  Testing Inquiry events
     *
     */

    /**
     * Short function to help create Inquiry models.
     *
     * @return Inquiry
     */
    private function createInquiry($status, $reference_id, $created_at, $started_at, $completed_at, $failed_at, $decisioned_at, $expired_at, $redacted_at): Inquiry
    {

        return Inquiry::from([
            "data" => [
                "type" => "inquiry",
                "id" => "inq_wAXJNnZfnsDjrfP3h4nbrAkk",
                "attributes" => [
                    "status" => $status,
                    "note" => "",
                    "creator" => "",
                    "fields" => [],
                    "reviewer_comment" => "",
                    "reference_id" => $reference_id,
                    "created_at" => $created_at,
                    "started_at" => $started_at,
                    "completed_at" => $completed_at,
                    "failed_at" => $failed_at,
                    "decisioned_at" => $decisioned_at,
                    "expired_at" => $expired_at,
                    "redacted_at" => $redacted_at,
                    "previous_step_name" => "",
                    "next_step_name" => "",
                    "tags" => []
                ],
                "relationships" => [
                    "account" => [
                        "data" => [
                            "type" => "account",
                            "id" => "act_wAXJNnZfnsDjrfP3h4nbrAkk",
                        ]
                    ],
                    "template" => [
                        "data" => null
                    ],
                    "inquiry_template" => [
                        "data" => [
                            "type" => "inquiry-template",
                            "id" => "itmpl_pFp1GW8kijftMt7YjAHoBNqT"
                        ]
                    ],
                    "reviewer" => [
                        "data" => null
                    ],
                    "inquiry_template_version" => [
                        "data" => [
                            "type" => "inquiry-template-version",
                            "id" => "itmplv_x3BEA1suF2qFE2QJqCHHbP6"
                        ]
                    ],
                    "reports" => [
                        "data" => []
                    ],
                    "sessions" => [
                        "data" => []
                    ],
                    "verifications" => [
                        "data" => []
                    ],
                    "documents" => [
                        "data" => []
                    ],
                    "selfies" => [
                        "data" => []
                    ],
                ]
            ]
        ]);
    }

    /**
     * Test the InquiryCreated event.
     *
     * @return void
     */
    public function test_inquiry_created_event()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $created_at = Carbon::now()->toDateTimeString();

        InquiryCreated::dispatch($this->createInquiry("created", $user->id, $created_at, $created_at, $created_at, $created_at, $created_at, $created_at, $created_at));

        $this->assertDatabaseHas("persona_inquiries", ["reference_id" => $user->id]);
    }

    /**
     * Test the InquiryStarted event.
     *
     * @return void
     */
    public function test_inquiry_started_event()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $created_at = Carbon::now()->toDateTimeString();
        $started_at = Carbon::now()->addSecond()->toDateTimeString();

        InquiryStarted::dispatch($this->createInquiry("created", $user->id, $created_at, $started_at, $created_at, $created_at, $created_at, $created_at, $created_at));

        $this->assertDatabaseHas("persona_inquiries", ["started_at" => $started_at]);
    }

    /**
     * Test the InquiryExpired event.
     *
     * @return void
     */
    public function test_inquiry_expired_event()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $created_at = Carbon::now()->toDateTimeString();
        $expired_at = Carbon::now()->addSecond()->toDateTimeString();

        InquiryCreated::dispatch($this->createInquiry("created", $user->id, $created_at, $created_at, $created_at, $created_at, $created_at, $created_at, $created_at));
        InquiryExpired::dispatch($this->createInquiry("expired", $user->id, $created_at, $created_at, $created_at, $created_at, $created_at, $expired_at, $created_at));

        $this->assertDatabaseHas("persona_inquiries", ["expired_at" => $expired_at]);
    }

    /**
     * Test the InquiryCompleted event.
     *
     * @return void
     */
    public function test_inquiry_completed_event()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $created_at = Carbon::now()->toDateTimeString();
        $completed_at = Carbon::now()->addSecond()->toDateTimeString();

        InquiryCreated::dispatch($this->createInquiry("created", $user->id, $created_at, $created_at, $created_at, $created_at, $created_at, $created_at, $created_at));
        InquiryCompleted::dispatch($this->createInquiry("completed", $user->id, $created_at, $created_at, $completed_at, $created_at, $created_at, $created_at, $created_at));

        $this->assertDatabaseHas("persona_inquiries", ["completed_at" => $completed_at]);
    }

    /**
     * Test the InquiryFailed event.
     *
     * @return void
     */
    public function test_inquiry_failed_event()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $created_at = Carbon::now()->toDateTimeString();
        $failed_at = Carbon::now()->addSecond()->toDateTimeString();

        InquiryCreated::dispatch($this->createInquiry("created", $user->id, $created_at, $created_at, $created_at, $created_at, $created_at, $created_at, $created_at));
        InquiryFailed::dispatch($this->createInquiry("failed", $user->id, $created_at, $created_at, $created_at, $failed_at, $created_at, $created_at, $created_at));

        $this->assertDatabaseHas("persona_inquiries", ["failed_at" => $failed_at]);
    }

    /**
     * Test the InquiryMarkedForReview event.
     *
     * @return void
     */
    public function test_inquiry_marked_for_review_event()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $created_at = Carbon::now()->toDateTimeString();

        InquiryCreated::dispatch($this->createInquiry("created", $user->id, $created_at, $created_at, $created_at, $created_at, $created_at, $created_at, $created_at));
        InquiryMarkedForReview::dispatch($this->createInquiry("pending", $user->id, $created_at, $created_at, $created_at, $created_at, $created_at, $created_at, $created_at));

        $this->assertDatabaseHas("persona_inquiries", ["status" => "pending"]);
    }

    /**
     * Test the InquiryApproved event.
     *
     * @return void
     */
    public function test_inquiry_approved_event()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $created_at = Carbon::now()->toDateTimeString();
        $decisioned_at = Carbon::now()->addHour()->toDateTimeString();

        InquiryCreated::dispatch($this->createInquiry("created", $user->id, $created_at, $created_at, $created_at, $created_at, $created_at, $created_at, $created_at));
        InquiryApproved::dispatch($this->createInquiry("approved", $user->id, $created_at, $created_at, $created_at, $created_at, $decisioned_at, $created_at, $created_at));

        $this->assertDatabaseHas("persona_inquiries", ["status" => "approved"]);
        // check whether ths user persona_verified_at was updated
        $this->assertDatabaseHas("users", ["id" => $user->id, "persona_verified_at" => $decisioned_at]);
    }

    /**
     * Test the InquiryDeclined event.
     *
     * @return void
     */
    public function test_inquiry_declined_event()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $created_at = Carbon::now()->toDateTimeString();
        $decisioned_at = Carbon::now()->addHour()->toDateTimeString();

        InquiryCreated::dispatch($this->createInquiry("created", $user->id, $created_at, $created_at, $created_at, $created_at, $created_at, $created_at, $created_at));
        InquiryDeclined::dispatch($this->createInquiry("declined", $user->id, $created_at, $created_at, $created_at, $created_at, $decisioned_at, $created_at, $created_at));

        $this->assertDatabaseHas("persona_inquiries", ["decisioned_at" => $decisioned_at]);
    }


    /**
     * Test the InquiryTransitioned event.
     *
     * @return void
     */
    public function test_inquiry_transitioned_event()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $created_at = Carbon::now()->toDateTimeString();

        InquiryTransitioned::dispatch($this->createInquiry("pending", $user->id, $created_at, $created_at, $created_at, $created_at, $created_at, $created_at, $created_at));

        $this->assertDatabaseHas("persona_inquiries", ["status" => "pending"]);
    }


    /**
     *
     *  Testing Verification events
     *
     */

    /**
     * Short function to help create Verification models.
     *
     * @return Verification
     */
    private function createVerification($status, $created_at, $created_at_ts, $submitted_at, $submitted_at_ts, $completed_at, $completed_at_ts, $inquiry_id = "inq_wAXJNnZfnsDjrfP3h4nbrAkk"): Verification
    {
        return Verification::from([
            "data" => [
                "type" => "verification",
                "id" => "ver_wAXJNnZfnsDjrfP3h4nbrAkk",
                "attributes" => [
                    "status" => $status,
                    "created_at" => $created_at,
                    "created_at_ts" => $created_at_ts,
                    "submitted_at" => $submitted_at,
                    "submitted_at_ts" => $submitted_at_ts,
                    "completed_at" => $completed_at,
                    "completed_at_ts" => $completed_at_ts,
                    "country_code" => "aaa",
                    "entity_confidence_reasons" => [],
                    "photo_urls" => [],
                    "capture_method" => "",
                    "checks" => []
                ],
                "relationships" => [
                    "inquiry" => [
                        "data" => [
                            "type" => "inquiry",
                            "id" => $inquiry_id
                        ]
                    ]
                ]
            ]
        ]);
    }

    /**
     * Test the VerificationCreated event.
     *
     * @return void
     */
    public function test_verification_created_event()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $created_at = Carbon::now()->toDateTimeString();
        $created_at_ts = Carbon::now()->timestamp;

        // create the inquiry that will be linked with the verification
        InquiryCreated::dispatch($this->createInquiry("created", $user->id, $created_at, $created_at, $created_at, $created_at, $created_at, $created_at, $created_at));

        VerificationCreated::dispatch($this->createVerification("initiated", $created_at, $created_at_ts, $created_at, $created_at_ts, $created_at, $created_at_ts));

        $this->assertDatabaseHas("persona_verifications", ["created_at" => $created_at]);
    }

    /**
     * Test the VerificationSubmitted event.
     *
     * @return void
     */
    public function test_verification_submitted_event()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $created_at = Carbon::now()->toDateTimeString();
        $created_at_ts = Carbon::now()->timestamp;

        // create the inquiry that will be linked with the verification
        InquiryCreated::dispatch($this->createInquiry("created", $user->id, $created_at, $created_at, $created_at, $created_at, $created_at, $created_at, $created_at));

        $submitted_at = Carbon::now()->addMinute()->toDateTimeString();
        $submitted_at_ts = Carbon::now()->addMinute()->timestamp;

        VerificationCreated::dispatch($this->createVerification("initiated", $created_at, $created_at_ts, $created_at, $created_at_ts, $created_at, $created_at_ts));
        VerificationSubmitted::dispatch($this->createVerification("submitted", $created_at, $created_at_ts, $submitted_at, $submitted_at_ts, $created_at, $created_at_ts));

        $this->assertDatabaseHas("persona_verifications", ["submitted_at" => $submitted_at]);
    }

    /**
     * Test the VerificationPassed event.
     *
     * @return void
     */
    public function test_verification_passed_event()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $created_at = Carbon::now()->toDateTimeString();
        $created_at_ts = Carbon::now()->timestamp;

        // create the inquiry that will be linked with the verification
        InquiryCreated::dispatch($this->createInquiry("created", $user->id, $created_at, $created_at, $created_at, $created_at, $created_at, $created_at, $created_at));

        $completed_at = Carbon::now()->addMinute()->toDateTimeString();
        $completed_at_ts = Carbon::now()->addMinute()->timestamp;

        VerificationCreated::dispatch($this->createVerification("initiated", $created_at, $created_at_ts, $created_at, $created_at_ts, $created_at, $created_at_ts));
        VerificationPassed::dispatch($this->createVerification("passed", $created_at, $created_at_ts, $created_at, $created_at_ts, $completed_at, $completed_at_ts));

        $this->assertDatabaseHas("persona_verifications", ["completed_at" => $completed_at]);
    }

    /**
     * Test the VerificationFailed event.
     *
     * @return void
     */
    public function test_verification_failed_event()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $created_at = Carbon::now()->toDateTimeString();
        $created_at_ts = Carbon::now()->timestamp;

        // create the inquiry that will be linked with the verification
        InquiryCreated::dispatch($this->createInquiry("created", $user->id, $created_at, $created_at, $created_at, $created_at, $created_at, $created_at, $created_at));

        $completed_at = Carbon::now()->addMinute()->toDateTimeString();
        $completed_at_ts = Carbon::now()->addMinute()->timestamp;

        VerificationCreated::dispatch($this->createVerification("initiated", $created_at, $created_at_ts, $created_at, $created_at_ts, $created_at, $created_at_ts));
        VerificationFailed::dispatch($this->createVerification("failed", $created_at, $created_at_ts, $created_at, $created_at_ts, $completed_at, $completed_at_ts));

        $this->assertDatabaseHas("persona_verifications", ["completed_at" => $completed_at]);
    }

    /**
     * Test the VerificationRequiresRetry event.
     *
     * @return void
     */
    public function test_verification_requires_retry_event()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $created_at = Carbon::now()->toDateTimeString();
        $created_at_ts = Carbon::now()->timestamp;

        // create the inquiry that will be linked with the verification
        InquiryCreated::dispatch($this->createInquiry("created", $user->id, $created_at, $created_at, $created_at, $created_at, $created_at, $created_at, $created_at));

        VerificationCreated::dispatch($this->createVerification("initiated", $created_at, $created_at_ts, $created_at, $created_at_ts, $created_at, $created_at_ts));
        // I actually don't know what's the difference in data sent, so I put it to failed to check that the event was registered
        VerificationRequiresRetry::dispatch($this->createVerification("failed", $created_at, $created_at_ts, $created_at, $created_at_ts, $created_at, $created_at_ts));

        $this->assertDatabaseHas("persona_verifications", ["status" => "failed"]);
    }

    /**
     * Test the VerificationCanceled event.
     *
     * @return void
     */
    public function test_verification_canceled_event()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $created_at = Carbon::now()->toDateTimeString();
        $created_at_ts = Carbon::now()->timestamp;

        // create the inquiry that will be linked with the verification
        InquiryCreated::dispatch($this->createInquiry("created", $user->id, $created_at, $created_at, $created_at, $created_at, $created_at, $created_at, $created_at));

        // I actually don't know what's the actual data sent, so I put it to failed to check that the event was registered
        VerificationCanceled::dispatch($this->createVerification("failed", $created_at, $created_at_ts, $created_at, $created_at_ts, $created_at, $created_at_ts));

        $this->assertDatabaseHas("persona_verifications", ["status" => "failed"]);
    }

    /**
     * Test the Verification without Inquiry already existing.
     *
     * @return void
     */
    public function test_verification_without_inquiry_event()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $created_at = Carbon::now()->toDateTimeString();
        $created_at_ts = Carbon::now()->timestamp;

        // NOTE: set template ID according to the user that you are using.
        $inquiry_id = Persona::init()->inquiries()->create($user->id, PersonaTemplates::GOVERNMENT_ID_AND_SELFIE)->id;

        // I actually don't know what's the actual data sent, so I put it to failed to check that the event was registered
        VerificationCreated::dispatch($this->createVerification("failed", $created_at, $created_at_ts, $created_at, $created_at_ts, $created_at, $created_at_ts, $inquiry_id));

        $this->assertDatabaseHas("persona_verifications", ["status" => "failed"]);
    }
}
