<?php

namespace Cyberthai\Linebot\Helpers;

use Cyberthai\Linebot\Provider\LineProviderInterface;

class Oauth
{
    /*
     * สร้างตัวช่วยออก short-lived token
     */
    public function __construct(public LineProviderInterface $provider)
    {
        //
    }

    /*
     * ออก channel access token อายุสั้น (30 วัน)
     * @link https://developers.line.biz/en/reference/messaging-api/#issue-shortlived-channel-access-token
     */
    public function accessToken(): array
    {
        $data = [
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => $this->provider->channelId,
                'client_secret' => $this->provider->clientSecret,
            ],
        ];

        return $this->request('post', 'accessToken', $data);
    }

    /*
     * ตรวจสอบว่า token อายุสั้นหรือยาวยังใช้ได้หรือไม่
     * @link https://developers.line.biz/en/reference/messaging-api/
     *     #verify-the-validity-of-short-lived-and-long-lived-channel-access-tokens
     */
    public function verify(string $accessToken): array
    {
        $data = [
            'form_params' => [
                'access_token' => $accessToken,
            ],
        ];

        return $this->request('post', 'verify', $data);
    }

    /*
     * เพิกถอน token อายุสั้นหรือยาว
     * @link https://developers.line.biz/en/reference/messaging-api/
     *     #revoke-short-lived-or-long-lived-channel-access-token
     */
    public function revoke(string $accessToken): array
    {
        $data = [
            'form_params' => [
                'access_token' => $accessToken,
            ],
        ];

        return $this->request('post', 'revoke', $data);
    }

    /*
     * ส่งคำขอไป /v2/oauth
     */
    public function request(string $method, string $urlPath, array $data = []): array
    {
        $header = [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];

        return $this->provider->request(
            'oauth',
            $urlPath,
            strtoupper($method),
            $header,
            $data,
        );
    }
}
