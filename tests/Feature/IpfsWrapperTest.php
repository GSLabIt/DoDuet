<?php

namespace Tests\Feature;

use App\Models\Tracks;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class IpfsWrapperTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Set up function.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->seed();
        $this->refreshDatabase();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_upload()
    {
        /** @var Tracks $track */
        $track = Tracks::factory()->create();

        $file = UploadedFile::fake()->createWithContent("colossus", "manythingsinside");

        $this->expectNotToPerformAssertions();
        // upload the file
        ipfs()->upload($file);
    }
}
