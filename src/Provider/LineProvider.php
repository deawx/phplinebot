<?php

declare(strict_types=1);

namespace cyberthai\Linebot\Provider;

use cyberthai\Linebot\Exceptions\IineException;
use GuzzleHttp\Client;

class LineProvider implements LineProviderInterface {

    const API_BOT_URL = 'https://api.line.me/v2/bot/';
    const OAUTH2_API_URL = 'https://api.line.me/oauth2/v2.1/';
    const API_DATA_BOT_URL = 'https://api-data.line.me/v2/bot/';

    public string $authorization;

    public function __construct(
        public int|string $channelId,
        public string $clientSecret,
        public string $channelAccessToken
    ) {
        if (empty($channelId)) {
            throw new IineException('channelId is empty.');
        }

        if (empty($clientSecret)) {
            throw new IineException('clientSecret is empty.');
        }

        if (empty($channelAccessToken)) {
            throw new IineException('channelAccessToken is empty.');
        }

        $this->authorization = 'Bearer ' . $this->channelAccessToken;
    }

    /*
     * Request API
     */
    public function request(
        string $apiKind,
        string $urlPath,
        string $httpMethod,
        array $header = [],
        array $data = []
    ): array {
        $url = match ($apiKind) {
            'bot' => self::API_BOT_URL,
            'oauth2' => self::OAUTH2_API_URL,
            'data' => self::API_DATA_BOT_URL
        };

        $client = new Client([
            'base_uri' => $url,
            'headers' => $header
        ]);

        $result = $client->request($httpMethod, $urlPath, $data);

        return json_decode($result->getBody()->getContents(), true);
    }
}