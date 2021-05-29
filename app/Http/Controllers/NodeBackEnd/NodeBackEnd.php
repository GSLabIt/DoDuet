<?php

namespace App\Http\Controllers\NodeBackEnd;


use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class NodeBackEnd
{
    public static string $BASE_URL = "http://node.internal.doduet.studio/api/";

    protected static function get(string $url, array $parameters): Response
    {
        return Http::timeout(60)->get($url, $parameters);
    }

    protected static function post(string $url, array $parameters): Response
    {
        return Http::timeout(60)->post($url, $parameters);
    }

    public static function endpoints(): Endpoints
    {
        return new Endpoints();
    }
}
