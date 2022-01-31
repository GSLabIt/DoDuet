<?php

namespace Tests\Feature;

use App\Models\Tracks;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SkynetWrapperTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private User $alice;
    private User $bob;

    /**
     * Set up function.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->refreshDatabase();
    }

    /**
     * Test track file upload.
     *
     * @return void
     */
    public function test_upload()
    {
        $this->seed();

        /** @var Tracks $track */
        $track = Tracks::factory()->create();

        $file = UploadedFile::fake()->createWithContent("colossus", "manythingsinside");

        $this->expectNotToPerformAssertions();
        // upload the file
        skynet()->upload($file, $track->skynet);
    }

    /**
     * Test the file download.
     *
     * @return void
     */
    public function test_download()
    {
        $this->seed();

        /** @var Tracks $track */
        $track = Tracks::factory()->create();

        $file = UploadedFile::fake()->createWithContent("colossus", "manythingsinside");

        // upload and then check that the file downloaded is the right one
        skynet()->upload($file, $track->skynet);
        $this->assertEquals("manythingsinside", skynet()->download($track->skynet));
    }

    /**
     * Test the exception with wrong or invalid link.
     *
     * @return void
     */
    public function test_download_wrong_link()
    {
        $this->seed();

        // remove exception handling
        $this->withoutExceptionHandling();

        /** @var Tracks $track */
        $track = Tracks::factory()->create();

        // prepare the expected exception
        $this->expectExceptionObject(new Exception(
            config("error-codes.INVALID_LINK.message"),
            config("error-codes.INVALID_LINK.code")
        ));

        // execute the wrapper
        skynet()->download($track->skynet);
    }
}
