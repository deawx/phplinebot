<?php

namespace Cyberthai\Linebot\Helpers;

use Cyberthai\Linebot\Provider\LineProviderInterface;

class Room
{
    /*
     * สร้างตัวช่วยเรียก Multi-person chats API
     */
    public function __construct(public LineProviderInterface $provider)
    {
        //
    }

    /*
     * นับจำนวนสมาชิกในห้องแชทหลายคน
     * @link https://developers.line.biz/en/reference/messaging-api/#get-members-room-count
     */
    public function memberCount(string $roomId): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', "room/{$roomId}/members/count", [], $header);
    }

    /*
     * ดึง user ID ของสมาชิกห้อง (ใช้ start จาก next เพื่อดึงหน้าถัดไป)
     * @link https://developers.line.biz/en/reference/messaging-api/#get-room-member-user-ids
     */
    public function memberIds(string $roomId, string $start = ''): array
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

        return $this->request('get', "room/{$roomId}/members/ids", $data, $header);
    }

    /*
     * ดึงโปรไฟล์สมาชิกในห้องแชทหลายคน
     * @link https://developers.line.biz/en/reference/messaging-api/#get-room-member-profile
     */
    public function member(string $roomId, string $userId): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', "room/{$roomId}/member/{$userId}", [], $header);
    }

    /*
     * ให้บอทออกจากห้องแชทหลายคน
     * @link https://developers.line.biz/en/reference/messaging-api/#leave-room
     */
    public function leave(string $roomId): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('post', "room/{$roomId}/leave", [], $header);
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
