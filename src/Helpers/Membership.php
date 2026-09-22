<?php

namespace Cyberthai\Linebot\Helpers;

use Cyberthai\Linebot\Provider\LineProviderInterface;

class Membership
{
    /*
     * สร้างตัวช่วยเรียก Membership API
     */
    public function __construct(public LineProviderInterface $provider)
    {
        //
    }

    /*
     * ดูสถานะแผนสมาชิกที่ผู้ใช้สมัครไว้
     * @link https://developers.line.biz/en/reference/messaging-api/#get-a-users-membership-subscription-status
     */
    public function subscription(string $userId): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', "membership/subscription/{$userId}", [], $header);
    }

    /*
     * ดึงรายการแผนสมาชิกของบัญชีทางการ
     * @link https://developers.line.biz/en/reference/messaging-api/#get-membership-plans
     */
    public function getList(): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', 'membership/list', [], $header);
    }

    /*
     * ดึง user ID ของผู้ที่เข้าร่วมแผนสมาชิก
     * @link https://developers.line.biz/en/reference/messaging-api/#get-membership-user-ids
     */
    public function userIds(string|int $membershipId, string $start = '', int $limit = 0): array
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

        return $this->request(
            'get',
            "membership/{$membershipId}/users/ids",
            $data,
            $header,
        );
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
