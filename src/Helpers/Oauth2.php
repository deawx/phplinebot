<?php

namespace Cyberthai\Linebot\Helpers;

use Cyberthai\Linebot\Provider\LineProviderInterface;

class Oauth2
{
    /*
     * สร้างตัวช่วยออก token รุ่น 2.1 และ v3
     */
    public function __construct(public LineProviderInterface $provider)
    {
        //
    }

    /*
     * ออก channel access token v2.1 ด้วย JWT
     * @link https://developers.line.biz/en/reference/messaging-api/#issue-channel-access-token-v2-1
     */
    public function token(string $jwt): array
    {
        $data = [
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_assertion_type' => 'urn:ietf:params:oauth:client-assertion-type:jwt-bearer',
                'client_assertion' => $jwt,
            ],
        ];

        return $this->request('post', 'token', $data);
    }

    /*
     * ตรวจสอบว่า token v2.1 ยังใช้ได้หรือไม่
     * @link https://developers.line.biz/en/reference/messaging-api/#verify-channel-access-token-v2-1
     */
    public function verify(string $accessToken): array
    {
        $data = [
            'query' => [
                'access_token' => $accessToken,
            ],
        ];

        return $this->request('get', 'verify', $data);
    }

    /*
     * ดึง key ID ของ token v2.1 ที่ยังใช้ได้ทั้งหมด
     * @link https://developers.line.biz/en/reference/messaging-api/#get-all-valid-channel-access-token-key-ids-v2-1
     */
    public function tokens(string $jwt): array
    {
        $data = [
            'query' => [
                'client_assertion_type' => 'urn:ietf:params:oauth:client-assertion-type:jwt-bearer',
                'client_assertion' => $jwt,
            ],
        ];

        return $this->request('get', 'tokens/kid', $data);
    }

    /*
     * เพิกถอน channel access token v2.1
     * @link https://developers.line.biz/en/reference/messaging-api/#revoke-channel-access-token-v2-1
     */
    public function revoke(string $accessToken): array
    {
        $data = [
            'form_params' => [
                'client_id' => $this->provider->channelId,
                'client_secret' => $this->provider->clientSecret,
                'access_token' => $accessToken,
            ],
        ];

        return $this->request('post', 'revoke', $data);
    }

    /*
     * ออก token แบบ stateless (15 นาที) ด้วย secret หรือ JWT
     * @link https://developers.line.biz/en/reference/messaging-api/#issue-stateless-channel-access-token
     */
    public function tokenV3(string $jwt = ''): array
    {
        if ($jwt !== '') {
            $formParams = [
                'grant_type' => 'client_credentials',
                'client_assertion_type' => 'urn:ietf:params:oauth:client-assertion-type:jwt-bearer',
                'client_assertion' => $jwt,
            ];
        } else {
            $formParams = [
                'grant_type' => 'client_credentials',
                'client_id' => $this->provider->channelId,
                'client_secret' => $this->provider->clientSecret,
            ];
        }

        return $this->request('post', 'token', ['form_params' => $formParams], 'oauth2v3');
    }

    /*
     * ส่งคำขอไป oauth2 v2.1 หรือ v3
     */
    public function request(
        string $method,
        string $urlPath,
        array $data = [],
        string $apiKind = 'oauth2',
    ): array {
        $header = [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];

        return $this->provider->request(
            $apiKind,
            $urlPath,
            strtoupper($method),
            $header,
            $data,
        );
    }
}
