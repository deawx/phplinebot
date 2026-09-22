<?php

namespace Cyberthai\Linebot\Helpers;

use Cyberthai\Linebot\Provider\LineProviderInterface;

class Content
{
    /*
     * สร้างตัวช่วยดาวน์โหลดเนื้อหาจาก webhook
     */
    public function __construct(public LineProviderInterface $provider)
    {
        //
    }

    /*
     * ดาวน์โหลดไฟล์รูป วิดีโอ หรือเสียงจาก message ID
     * @link https://developers.line.biz/en/reference/messaging-api/#get-content
     */
    public function get(string|int $messageId): array
    {
        return $this->request('get', "message/{$messageId}/content");
    }

    /*
     * ตรวจว่าวิดีโอหรือเสียงพร้อมดาวน์โหลดหรือยัง
     * @link https://developers.line.biz/en/reference/messaging-api/#verify-video-or-audio-preparation-status
     */
    public function transcoding(string|int $messageId): array
    {
        return $this->request('get', "message/{$messageId}/content/transcoding");
    }

    /*
     * ดาวน์โหลดรูปตัวอย่างของรูปหรือวิดีโอ
     * @link https://developers.line.biz/en/reference/messaging-api/#get-image-or-video-preview
     */
    public function preview(string|int $messageId): array
    {
        return $this->request('get', "message/{$messageId}/content/preview");
    }

    /*
     * ส่งคำขอไป api-data.line.me
     */
    public function request(string $method, string $urlPath, array $data = []): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->provider->request(
            'data',
            $urlPath,
            strtoupper($method),
            $header,
            $data,
        );
    }
}
