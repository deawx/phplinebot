<?php

declare(strict_types=1);

namespace cyberthai\Linebot\Helpers;

use cyberthai\Linebot\Provider\LineProviderInterface;

// จัดการข้อมูลผู้ใช้ที่ได้เพิ่มบอทของเราเป็นเพื่อน

class Users {
    public function __construct(public LineProviderInterface $provider) {
    }


    /*
     * Get profile
     * https://developers.line.biz/en/reference/messaging-api/#users
     */
    public function profile(string $userId): array {
        $header = [
            'Authorization' => $this->provider->authorization
        ];

        return $this->request('get', "profile/{$userId}", $data ?? [], $header);
    }
    /**
     * Follower
     * https://developers.line.biz/en/reference/messaging-api/#get-follower-ids
     * เรียกดูรายชื่อผู้ใช้ที่ติดตามบอทเรา
     * ใช้ได้เฉพาะบัญชีที่เป็น พรีเมี่ยม เท่านั้น
     */

    public function getfollowers(): array {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];
        return $this->request('get', 'followers/ids', $data ?? [], $header);
    }


    /*
     * Provider Request API
     */
    public function request(string $method, string $urlPath, array $data = [], array $header = []): array {
        return $this->provider->request(
            'bot',
            $urlPath,
            strtoupper($method),
            $header,
            $data
        );
    }
}