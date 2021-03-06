<?php

namespace App\Http\Wrappers;

use App\Exceptions\SafeException;
use App\Http\Wrappers\Interfaces\Wrapper;
use App\Models\Ipfs;
use App\Models\Tracks;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use Exception;

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

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws SafeException
     */
    public function upload(UploadedFile $file, Ipfs $ipfs) {
        // get file content
        $content = $file->get();
        // generate a random encryption key
        $key = sodium()->encryption()->symmetric()->key();
        // generate the nonce
        $nonce = sodium()->derivation()->generateSymmetricNonce();
        // encrypt the nonce
        $encrypted_content = sodium()->encryption()->symmetric()->encrypt($content, $key, $nonce);
        // send the request to the connector
        $response = Http::asForm()->withHeaders([
            "Content-Type" => "application/x-www-form-urlencoded",
            "Authorization" => "Bearer ".env("NFT_STORAGE_API_KEY")
        ])->post(env("NFT_STORAGE_UPLOAD_LINK"),[
            "file" => $encrypted_content
        ]);
        if(!$response->json("ok")) {
            throw new SafeException(
                config("error-codes.NFT_STORAGE_CANT_UPLOAD_FILE.message"),
                config("error-codes.NFT_STORAGE_CANT_UPLOAD_FILE.code")
            );
        }
        $ipfs->update([
            "cid" => $response->json("value.cid"),
            "encryption_key" => $key,
        ]);
    }

    /**
     * @return string
     * @throws Exception
     */
    public function download(Ipfs $ipfs): string
    {
        try {
            // get file from ipfs
            $content = str_replace("file=","",str_replace("%3A",":",file_get_contents(env("IPFS_BASE_LINK").$ipfs->cid)));
            // REPLACE %3A URL ENCODED VERSION OF : and replace file=
            // decode and return the file content
            return sodium()->encryption()->symmetric()->decrypt($content, $ipfs->encryption_key);
        } catch (Exception) {
            throw new SafeException(
                config("error-codes.INVALID_LINK.message"),
                config("error-codes.INVALID_LINK.code")
            );
        }
    }

    /**
     * @throws SafeException
     */
    public function delete(Ipfs $ipfs) {
        $response = Http::withHeaders([
            "Authorization" => "Bearer ".env("NFT_STORAGE_API_KEY")
        ])->delete(env("NFT_STORAGE_DELETE_LINK").$ipfs->cid);
        if(!$response->json("ok")) {
            throw new SafeException(
                config("error-codes.NFT_STORAGE_CANT_DELETE_FILE.message"),
                config("error-codes.NFT_STORAGE_CANT_DELETE_FILE.code")
            );
        }
    }
}
