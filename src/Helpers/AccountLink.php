<?php

namespace Cyberthai\Linebot\Helpers;

use Cyberthai\Linebot\Provider\LineProviderInterface;

class AccountLink
{
    /*
     * สร้างตัวช่วยเรียก Account link API
     */
    public function __construct(public LineProviderInterface $provider)
    {
        //
    }

    /*
     * ออก link token สำหรับผูกบัญชีบริการกับบัญชี LINE
     * @link https://developers.line.biz/en/reference/messaging-api/#issue-link-token
     */
    public function token(string $userId): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('post', "user/{$userId}/linkToken", [], $header);
    }

    /*
     * ส่งคำขอไป Messaging API
     */
    public function request(string $method, string $urlPath, array $data = [], array $header = []): array
    {
        return $this->provider->request(
            'bot',
            $urlPath,
            strtoupper($method),
            $header,
            $data,
        );
    }
}
