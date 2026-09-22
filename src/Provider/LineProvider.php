<?php

namespace Cyberthai\Linebot\Provider;

use Cyberthai\Linebot\Exceptions\LineInitializeException;
use GuzzleHttp\Client;

class LineProvider implements LineProviderInterface
{
    public const API_BOT_URL = 'https://api.line.me/v2/bot/';
    public const OAUTH_API_URL = 'https://api.line.me/v2/oauth/';
    public const OAUTH2_API_URL = 'https://api.line.me/oauth2/v2.1/';
    public const OAUTH2_V3_API_URL = 'https://api.line.me/oauth2/v3/';
    public const API_DATA_BOT_URL = 'https://api-data.line.me/v2/bot/';

    public string $authorization;

    /*
     * สร้าง HTTP client และตรวจว่ามี channel ID, secret, token
     */
    public function __construct(
        public int|string $channelId,
        public string $clientSecret,
        public string $channelAccessToken,
    ) {
        if (empty($channelId)) {
            throw new LineInitializeException('channelId is empty.');
        }

        if (empty($clientSecret)) {
            throw new LineInitializeException('clientSecret is empty.');
        }

        if (empty($channelAccessToken)) {
            throw new LineInitializeException('channelAccessToken is empty.');
        }

        $this->authorization = 'Bearer ' . $this->channelAccessToken;
    }

    /*
     * ส่งคำขอ HTTP ไป LINE แล้วคืน JSON หรือไฟล์ไบนารี
     */
    public function request(
        string $apiKind,
        string $urlPath,
        string $httpMethod,
        array $header = [],
        array $data = [],
    ): array {
        $url = match ($apiKind) {
            'bot' => self::API_BOT_URL,
            'oauth' => self::OAUTH_API_URL,
            'oauth2' => self::OAUTH2_API_URL,
            'oauth2v3' => self::OAUTH2_V3_API_URL,
            'data' => self::API_DATA_BOT_URL,
            default => throw new \InvalidArgumentException("Unknown apiKind: {$apiKind}"),
        };

        $client = new Client([
            'base_uri' => $url,
            'headers' => $header,
        ]);

        $result = $client->request($httpMethod, $urlPath, $data);
        $body = $result->getBody()->getContents();
        $contentType = $result->getHeaderLine('Content-Type');

        if ($body === '') {
            return [];
        }

        if (!str_contains($contentType, 'application/json')) {
            return [
                'content' => $body,
                'contentType' => $contentType,
            ];
        }

        $decoded = json_decode($body, true);

        return is_array($decoded) ? $decoded : [];
    }
}
