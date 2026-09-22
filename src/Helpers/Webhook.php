<?php

namespace Cyberthai\Linebot\Helpers;

use Cyberthai\Linebot\Provider\LineProviderInterface;

class Webhook
{
    /*
     * สร้างตัวช่วยเรียก Webhook settings API
     */
    public function __construct(public LineProviderInterface $provider)
    {
        //
    }

    /*
     * ตั้ง URL ของ webhook
     * @link https://developers.line.biz/en/reference/messaging-api/#set-webhook-endpoint-url
     */
    public function set(string $url): array
    {
        $data = [
            'json' => [
                'endpoint' => $url,
            ],
        ];

        return $this->request('put', 'channel/webhook/endpoint', $data);
    }

    /*
     * ดู URL และสถานะ webhook ที่ตั้งไว้
     * @link https://developers.line.biz/en/reference/messaging-api/#get-webhook-endpoint-information
     */
    public function get(): array
    {
        return $this->request('get', 'channel/webhook/endpoint');
    }

    /*
     * ทดสอบ webhook (ไม่ส่ง URL จะทดสอบค่าที่ตั้งไว้แล้ว)
     * @link https://developers.line.biz/en/reference/messaging-api/#test-webhook-endpoint
     */
    public function test(string $url = ''): array
    {
        $data = [];

        if ($url !== '') {
            $data = [
                'json' => [
                    'endpoint' => $url,
                ],
            ];
        }

        return $this->request('post', 'channel/webhook/test', $data);
    }

    /*
     * ส่งคำขอไป Messaging API
     */
    public function request(string $method, string $urlPath, array $data = []): array
    {
        $header = [
            'Content-Type' => 'application/json',
            'Authorization' => $this->provider->authorization,
        ];

        return $this->provider->request(
            'bot',
            $urlPath,
            strtoupper($method),
            $header,
            $data,
        );
    }
}
