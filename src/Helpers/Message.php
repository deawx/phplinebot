<?php

namespace Cyberthai\Linebot\Helpers;

use Cyberthai\Linebot\Provider\LineProviderInterface;

class Message
{
    /*
     * สร้างตัวช่วยส่งข้อความและจัดการแชท
     */
    public function __construct(public LineProviderInterface $provider)
    {
        //
    }

    /*
     * ตอบกลับข้อความด้วย replyToken
     * @link https://developers.line.biz/en/reference/messaging-api/#send-reply-message
     */
    public function reply(string $to, array $messages, bool $notificationDisabled = false): array
    {
        $data = [
            'json' => [
                'replyToken' => $to,
                'messages' => $messages,
                'notificationDisabled' => $notificationDisabled,
            ],
        ];

        $header = [
            'Content-Type' => 'application/json',
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('post', 'message/reply', $data, $header);
    }

    /*
     * ส่งข้อความหาผู้ใช้ กลุ่ม หรือห้องได้ทุกเมื่อ
     * @link https://developers.line.biz/en/reference/messaging-api/#send-push-message
     */
    public function push(
        string $to,
        array $messages,
        bool $notificationDisabled = false,
        string|int $retryKey = '',
    ): array {
        $data = [
            'json' => [
                'to' => $to,
                'messages' => $messages,
                'notificationDisabled' => $notificationDisabled,
            ],
        ];

        $header = [
            'Content-Type' => 'application/json',
            'Authorization' => $this->provider->authorization,
        ];

        if (!empty($retryKey)) {
            $header['X-Line-Retry-Key'] = $retryKey;
        }

        return $this->request('post', 'message/push', $data, $header);
    }

    /*
     * ส่งข้อความชุดเดียวกันหาหลาย user ID
     * @link https://developers.line.biz/en/reference/messaging-api/#send-multicast-message
     */
    public function multicast(
        array $tos,
        array $messages,
        bool $notificationDisabled = false,
        string|int $retryKey = '',
    ): array {
        $data = [
            'json' => [
                'to' => $tos,
                'messages' => $messages,
                'notificationDisabled' => $notificationDisabled,
            ],
        ];

        $header = [
            'Content-Type' => 'application/json',
            'Authorization' => $this->provider->authorization,
        ];

        if (!empty($retryKey)) {
            $header['X-Line-Retry-Key'] = $retryKey;
        }

        return $this->request('post', 'message/multicast', $data, $header);
    }

    /*
     * ส่งข้อความตาม audience หรือตัวกรอง
     * @link https://developers.line.biz/en/reference/messaging-api/#send-narrowcast-message
     */
    public function narrowcast(
        array $messages,
        array $recipient = [],
        array $demographic = [],
        int $limit = 0,
        bool $notificationDisabled = false,
        string|int $retryKey = '',
    ): array {
        $json = [
            'messages' => $messages,
            'notificationDisabled' => $notificationDisabled,
        ];

        if ($recipient !== []) {
            $json['recipient'] = $recipient;
        }

        if ($demographic !== []) {
            $json['filter'] = [
                'demographic' => $demographic,
            ];
        }

        if ($limit > 0) {
            $json['limit'] = [
                'max' => $limit,
            ];
        }

        $data = [
            'json' => $json,
        ];

        $header = [
            'Content-Type' => 'application/json',
            'Authorization' => $this->provider->authorization,
        ];

        if (!empty($retryKey)) {
            $header['X-Line-Retry-Key'] = $retryKey;
        }

        return $this->request('post', 'message/narrowcast', $data, $header);
    }

    /*
     * ดูสถานะการส่ง narrowcast
     * @link https://developers.line.biz/en/reference/messaging-api/#get-narrowcast-progress-status
     */
    public function progressNarrowcast(string|int $requestId): array
    {
        $data = [
            'query' => [
                'requestId' => $requestId,
            ],
        ];

        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', 'message/progress/narrowcast', $data, $header);
    }

    /*
     * ส่งข้อความหาเพื่อนทั้งหมด
     * @link https://developers.line.biz/en/reference/messaging-api/#send-broadcast-message
     */
    public function broadcast(array $messages, bool $notificationDisabled = false, string|int $retryKey = ''): array
    {
        $data = [
            'json' => [
                'messages' => $messages,
                'notificationDisabled' => $notificationDisabled,
            ],
        ];

        $header = [
            'Content-Type' => 'application/json',
            'Authorization' => $this->provider->authorization,
        ];

        if (!empty($retryKey)) {
            $header['X-Line-Retry-Key'] = $retryKey;
        }

        return $this->request('post', 'message/broadcast', $data, $header);
    }

    /*
     * แสดงแอนิเมชันกำลังพิมพ์ในแชท 1 ต่อ 1
     * @link https://developers.line.biz/en/reference/messaging-api/#display-a-loading-indicator
     */
    public function loading(string $chatId, int $loadingSeconds = 0): array
    {
        $json = [
            'chatId' => $chatId,
        ];

        if ($loadingSeconds > 0) {
            $json['loadingSeconds'] = $loadingSeconds;
        }

        $data = [
            'json' => $json,
        ];

        $header = [
            'Content-Type' => 'application/json',
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('post', 'chat/loading/start', $data, $header);
    }

    /*
     * ทำเครื่องหมายว่าอ่านข้อความผู้ใช้แล้ว
     * @link https://developers.line.biz/en/reference/messaging-api/#mark-as-read
     */
    public function markAsRead(string $markAsReadToken): array
    {
        $data = [
            'json' => [
                'markAsReadToken' => $markAsReadToken,
            ],
        ];

        $header = [
            'Content-Type' => 'application/json',
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('post', 'chat/markAsRead', $data, $header);
    }

    /*
     * ดูเพดานส่งข้อความเดือนนี้
     * @link https://developers.line.biz/en/reference/messaging-api/#get-quota
     */
    public function quota(): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', 'message/quota', [], $header);
    }

    /*
     * ดูจำนวนข้อความที่ส่งไปแล้วเดือนนี้
     * @link https://developers.line.biz/en/reference/messaging-api/#get-consumption
     */
    public function quotaConsumption(): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', 'message/quota/consumption', [], $header);
    }

    /*
     * ดูจำนวน reply ที่ส่งสำเร็จในวันนั้น
     * @link https://developers.line.biz/en/reference/messaging-api/#get-number-of-reply-messages
     */
    public function deliveryReply(int|string $date): array
    {
        $data = [
            'query' => [
                'date' => $date,
            ],
        ];

        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', 'message/delivery/reply', $data, $header);
    }

    /*
     * ดูจำนวน push ที่ส่งสำเร็จในวันนั้น
     * @link https://developers.line.biz/en/reference/messaging-api/#get-number-of-push-messages
     */
    public function deliveryPush(int|string $date): array
    {
        $data = [
            'query' => [
                'date' => $date,
            ],
        ];

        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', 'message/delivery/push', $data, $header);
    }

    /*
     * ดูจำนวน multicast ที่ส่งสำเร็จในวันนั้น
     * @link https://developers.line.biz/en/reference/messaging-api/#get-number-of-multicast-messages
     */
    public function deliveryMulticast(int|string $date): array
    {
        $data = [
            'query' => [
                'date' => $date,
            ],
        ];

        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', 'message/delivery/multicast', $data, $header);
    }

    /*
     * ดูจำนวน broadcast ที่ส่งสำเร็จในวันนั้น
     * @link https://developers.line.biz/en/reference/messaging-api/#get-number-of-broadcast-messages
     */
    public function deliveryBroadcast(int|string $date): array
    {
        $data = [
            'query' => [
                'date' => $date,
            ],
        ];

        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', 'message/delivery/broadcast', $data, $header);
    }

    /*
     * ตรวจโครงสร้างข้อความสำหรับ reply
     * @link https://developers.line.biz/en/reference/messaging-api/#validate-message-objects-of-reply-message
     */
    public function validateReply(array $messages): array
    {
        $data = [
            'json' => [
                'messages' => $messages,
            ],
        ];

        $header = [
            'Content-Type' => 'application/json',
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('post', 'message/validate/reply', $data, $header);
    }

    /*
     * ตรวจโครงสร้างข้อความสำหรับ push
     * @link https://developers.line.biz/en/reference/messaging-api/#validate-message-objects-of-push-message
     */
    public function validatePush(array $messages): array
    {
        $data = [
            'json' => [
                'messages' => $messages,
            ],
        ];

        $header = [
            'Content-Type' => 'application/json',
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('post', 'message/validate/push', $data, $header);
    }

    /*
     * ตรวจโครงสร้างข้อความสำหรับ multicast
     * @link https://developers.line.biz/en/reference/messaging-api/#validate-message-objects-of-multicast-message
     */
    public function validateMulticast(array $messages): array
    {
        $data = [
            'json' => [
                'messages' => $messages,
            ],
        ];

        $header = [
            'Content-Type' => 'application/json',
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('post', 'message/validate/multicast', $data, $header);
    }

    /*
     * ตรวจโครงสร้างข้อความสำหรับ narrowcast
     * @link https://developers.line.biz/en/reference/messaging-api/#validate-message-objects-of-narrowcast-message
     */
    public function validateNarrowcast(array $messages): array
    {
        $data = [
            'json' => [
                'messages' => $messages,
            ],
        ];

        $header = [
            'Content-Type' => 'application/json',
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('post', 'message/validate/narrowcast', $data, $header);
    }

    /*
     * ตรวจโครงสร้างข้อความสำหรับ broadcast
     * @link https://developers.line.biz/en/reference/messaging-api/#validate-message-objects-of-broadcast-message
     */
    public function validateBroadcast(array $messages): array
    {
        $data = [
            'json' => [
                'messages' => $messages,
            ],
        ];

        $header = [
            'Content-Type' => 'application/json',
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('post', 'message/validate/broadcast', $data, $header);
    }

    /*
     * ดูจำนวนหน่วย aggregation ที่ใช้เดือนนี้
     * @link https://developers.line.biz/en/reference/messaging-api/#get-number-of-units-used-this-month
     */
    public function aggregationInfo(): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', 'message/aggregation/info', [], $header);
    }

    /*
     * ดึงรายชื่อหน่วย aggregation ที่ใช้เดือนนี้
     * @link https://developers.line.biz/en/reference/messaging-api/#get-name-list-of-units-used-this-month
     */
    public function aggregationList(int|string $limit = 0, string $start = ''): array
    {
        $query = [];

        if (!empty($limit)) {
            $query['limit'] = $limit;
        }

        if ($start !== '') {
            $query['start'] = $start;
        }

        $data = [];
        if ($query !== []) {
            $data['query'] = $query;
        }

        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', 'message/aggregation/list', $data, $header);
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
