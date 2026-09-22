<?php

namespace Cyberthai\Linebot\Helpers;

use Cyberthai\Linebot\Provider\LineProviderInterface;

class Users
{
    /*
     * สร้างตัวช่วยเรียก Users API
     */
    public function __construct(public LineProviderInterface $provider)
    {
        //
    }

    /*
     * ดึงโปรไฟล์ผู้ใช้ที่เพิ่มเพื่อนแล้ว
     * @link https://developers.line.biz/en/reference/messaging-api/#get-profile
     */
    public function profile(string $userId): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', "profile/{$userId}", [], $header);
    }

    /*
     * ดึงรายการ user ID ของเพื่อน (ใช้ start จาก next เพื่อดึงหน้าถัดไป)
     * @link https://developers.line.biz/en/reference/messaging-api/#get-follower-ids
     */
    public function followers(string $start = '', int $limit = 0): array
    {
        $query = [];

        if ($start !== '') {
            $query['start'] = $start;
        }

        if ($limit > 0) {
            $query['limit'] = $limit;
        }

        $data = [];
        if ($query !== []) {
            $data['query'] = $query;
        }

        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', 'followers/ids', $data, $header);
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
