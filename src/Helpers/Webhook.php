<?php

namespace cyberthai\Linebot\Helpers;

use cyberthai\Linebot\Provider\LineProviderInterface;

/*
* Webhook settings
* https://developers.line.biz/en/reference/messaging-api/#webhook-settings
*/

class Webhook {
    public function __construct(public LineProviderInterface $provider) {
    }

    /*
* Set webhook endpoint URL
* https://developers.line.biz/en/reference/messaging-api/#set-webhook-endpoint-url
*/
    public function setwebhook(string $url): array {
        $data = [
            'json' => [
                'endpoint' => $url
            ]
        ];
        return $this->request('put', 'channel/webhook/endpoint', $data);
    }


    /*
* Get webhook endpoint information
* https://developers.line.biz/en/reference/messaging-api/#get-webhook-endpoint-information
*/
    public function getwebhook(): array {
        return $this->request('get', 'channel/webhook/endpoint');
    }

    /*
* Test webhook endpoint
* https://developers.line.biz/en/reference/messaging-api/#test-webhook-endpoint
*/


    public function testwebhook(string $url): array {
        $data = [
            'json' => [
                'endpoint' => $url
            ]
        ];
        return $this->request('post', 'channel/webhook/test', $data);
    }
    /*
     * Provider Request API
     */
    public function request(string $method, string $urlPath, array $data = []): array {
        $header = [
            'Content-Type' => 'application/json',
            'Authorization' => $this->provider->authorization
        ];

        return $this->provider->request(
            'bot',
            $urlPath,
            strtoupper($method),
            $header,
            $data
        );
    }
}