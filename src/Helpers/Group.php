<?php

namespace Cyberthai\Linebot\Helpers;

use Cyberthai\Linebot\Provider\LineProviderInterface;

class Group
{
    /*
     * สร้างตัวช่วยเรียก Group chats API
     */
    public function __construct(public LineProviderInterface $provider)
    {
        //
    }

    /*
     * ดึงสรุปข้อมูลกลุ่ม
     * @link https://developers.line.biz/en/reference/messaging-api/#get-group-summary
     */
    public function summary(string $groupId): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', "group/{$groupId}/summary", [], $header);
    }

    /*
     * นับจำนวนสมาชิกในกลุ่ม
     * @link https://developers.line.biz/en/reference/messaging-api/#get-members-group-count
     */
    public function membersCount(string $groupId): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', "group/{$groupId}/members/count", [], $header);
    }

    /*
     * ดึง user ID ของสมาชิกกลุ่ม (ใช้ start จาก next เพื่อดึงหน้าถัดไป)
     * @link https://developers.line.biz/en/reference/messaging-api/#get-group-member-user-ids
     */
    public function membersIds(string $groupId, string $start = ''): array
    {
        $data = [];

        if ($start !== '') {
            $data['query'] = [
                'start' => $start,
            ];
        }

        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', "group/{$groupId}/members/ids", $data, $header);
    }

    /*
     * ดึงโปรไฟล์สมาชิกในกลุ่ม
     * @link https://developers.line.biz/en/reference/messaging-api/#get-group-member-profile
     */
    public function member(string $groupId, string $userId): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', "group/{$groupId}/member/{$userId}", [], $header);
    }

    /*
     * ให้บอทออกจากกลุ่ม
     * @link https://developers.line.biz/en/reference/messaging-api/#leave-group
     */
    public function leave(string $groupId): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('post', "group/{$groupId}/leave", [], $header);
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
