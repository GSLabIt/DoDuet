<?php

namespace Tests\Feature;

use App\Http\Wrappers\IpfsWrapper;
use App\Models\Ipfs;
use App\Models\Tracks;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Nuwave\Lighthouse\Testing\ClearsSchemaCache;
use Tests\TestCase;

class IpfsWrapperTest extends TestCase
{
    use RefreshDatabase,ClearsSchemaCache, RefreshDatabase;
    /**
     * Set up function.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->bootClearsSchemaCache();
        $this->refreshDatabase();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_upload_and_download()
    {
        $this->seed();
        /** @var Ipfs $ipfs */
        $ipfs = Ipfs::factory()->create();
        $original_file = UploadedFile::fake()->create('test.mp3',1000);
        ipfs()->upload($original_file,$ipfs);

        Http::asForm()->withHeaders([
            "Authorization" => "Bearer ".env("NFT_STORAGE_API_KEY")
        ])->get(env("NFT_STORAGE_CHECK_LINK").$ipfs->cid);

        $this->assertTrue($original_file->get() === ipfs()->download($ipfs));
    }

    /**
     * Test the exception with wrong or invalid cid.
     *
     * @return void
     */
    public function test_download_wrong_cid()
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
        ipfs()->download($track->ipfs);
    }
}
