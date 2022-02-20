<?php

namespace App\Http\Wrappers;

use App\Http\Wrappers\Interfaces\Wrapper;
use App\Models\Skynet;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use Exception;
use Storage;

class IpfsWrapper implements Wrapper
{

    /**
     * Initialize the class instance
     *
     * @param $initializer
     * @return IpfsWrapper
     */
    #[Pure]
    public static function init($initializer = null): IpfsWrapper
    {
        return new static();
    }

    public function upload(UploadedFile $file) {
        // build the url and send the request
        //$path = "/upload";
        //$url = $this->buildRequestUrl($path);

        // get file content
        $content = $file->get();
        // generate a random encryption key
        $key = sodium()->encryption()->symmetric()->key();
        // generate the nonce
        $nonce = sodium()->derivation()->generateSymmetricNonce();
        // encrypt the nonce
        $encrypted_content = sodium()->encryption()->symmetric()->encrypt($content, $key, $nonce);
        // send the request to the connector
        $filename = Str::random(18);
        Storage::put("ipfs/".$filename,$encrypted_content);
        /*try {

        } finally {

        }*/
        /*$response = Http::asForm()->post($url, [
            "user" => env("SKYNET_USER"),
            "filename" => Str::random(18),
            "file" => $encrypted_content,
        ]);*/


        // update the skynet model
        /*$skynet->update([
            "link" => $response->json("upload.skylink"),
            "encryption_key" => $key,
        ]);*/
    }

    /**
     * @return string
     * @throws Exception
     */
    public function download(Skynet $skynet): string
    {
        try {
            // get file from skynet
            $content = file_get_contents(env("SKYNET_BASE_LINK").$skynet->link);
            // decode and return the file content
            return sodium()->encryption()->symmetric()->decrypt($content, $skynet->encryption_key);
        } catch (Exception $e) {
            throw new Exception(
                config("error-codes.INVALID_LINK.message"),
                config("error-codes.INVALID_LINK.code")
            );
        }
    }

    public function buildRequestUrl(string $path): string
    {
        // load the hostname
        $chain_host = env("SKYNET_CONNECTOR_HOST");
        // check if ending with a /, if not one is appended
        if(!Str::endsWith($chain_host, "/") && !Str::startsWith($path, "/")) {
            $chain_host .= "/";
        }

        return $chain_host . $path;
    }
}
