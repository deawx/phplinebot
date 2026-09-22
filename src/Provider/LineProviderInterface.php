<?php

namespace Cyberthai\Linebot\Provider;

/**
 * @property-read string $authorization
 * @property-read int|string $channelId
 * @property-read string $clientSecret
 * @property-read string $channelAccessToken
 */
interface LineProviderInterface
{
    /*
     * ส่งคำขอไป LINE Messaging API
     */
    public function request(
        string $apiKind,
        string $urlPath,
        string $httpMethod,
        array $header = [],
        array $data = [],
    ): array;
}
