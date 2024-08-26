<?php

declare(strict_types=1);

namespace cyberthai\Linebot\Helpers;

use cyberthai\Linebot\Provider\LineProviderInterface;

/*
 * SEND Messages
 * https://developers.line.biz/en/docs/messaging-api/sending-messages/
 */

class Message {

    public function __construct(public LineProviderInterface $provider) {
    }
    /*
     * Send reply message
     * ตอบกลับข้อความจาก event ที่ผู้ใช้ส่งมา (ข้อความตอบกลับ)
     * replyToken to จะได้รับจาก webhook event เท่านั้น
     * https://developers.line.biz/en/docs/messaging-api/sending-messages/#reply-messages
     */
    public function reply(string $to, array $messages, bool $notificationDisabled = false): array {
        $data = [
            'json' => [
                'replyToken'           => $to,
                'messages'             => $messages,
                'notificationDisabled' => $notificationDisabled,
            ],
        ];

        $header = [
            'Content-Type'  => 'application/json',
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('post', 'message/reply', $data, $header);
    }

    /*
     * Send push message ส่งข้อความแบบพุช
     * ส่งข้อความถึง userId, groupId, roomId
     * https://developers.line.biz/en/reference/messaging-api/#send-push-message
     */
    public function push(
        string       $to,
        array        $messages,
        bool         $notificationDisabled = false,
        string | int $retryKey = ''
    ): array {
        $data = [
            'json' => [
                'to'                   => $to,
                'messages'             => $messages,
                'notificationDisabled' => $notificationDisabled,
            ],
        ];

        $header = [
            'Content-Type'  => 'application/json',
            'Authorization' => $this->provider->authorization,
        ];

        if (!empty($retryKey)) {
            $header['X-Line-Retry-Key'] = $retryKey;
        }

        return $this->request('post', 'message/push', $data, $header);
    }

    /*
     * Send multicast message ส่งข้อความมัลติคาสต์
     * ส่งข้อความเดียวกันไปยัง ID ผู้ใช้หลายราย แต่ไม่สามารถส่งเข้าไปยังแชทกลุ่มห
     * https://developers.line.biz/en/reference/messaging-api/#send-multicast-message
     */
    public function multicast(
        array        $tos,
        array        $messages,
        bool         $notificationDisabled = false,
        string | int $retryKey = ''
    ): array {
        $data = [
            'json' => [
                'to'                   => $tos,
                'messages'             => $messages,
                'notificationDisabled' => $notificationDisabled,
            ],
        ];

        $header = [
            'Content-Type'  => 'application/json',
            'Authorization' => $this->provider->authorization,
        ];

        if (!empty($retryKey)) {
            $header['X-Line-Retry-Key'] = $retryKey;
        }

        return $this->request('post', 'message/multicast', $data, $header);
    }

    /*
     * Send narrowcast message
     * https://developers.line.biz/en/docs/messaging-api/sending-messages/#send-narrowcast-message
     */
    public function narrowcast(
        array        $messages,
        array        $recipient = [],
        array        $demographic = [],
        int          $limit = 100,
        bool         $notificationDisabled = false,
        string | int $retryKey = ''
    ): array {
        $data = [
            'json' => [
                'messages'             => $messages,
                'recipient'            => $recipient,
                'filter'               => [
                    'demographic' => $demographic,
                ],
                'limit'                => [
                    'max' => $limit,
                ],
                'notificationDisabled' => $notificationDisabled,
            ],
        ];

        $header = [
            'Content-Type'  => 'application/json',
            'Authorization' => $this->provider->authorization,
        ];

        if (!empty($retryKey)) {
            $header['X-Line-Retry-Key'] = $retryKey;
        }

        return $this->request('post', 'message/multicast', $data, $header);
    }

    /*
     * Get narrowcast message status
     * ตรวจสอบสถานะการส่งข้อความแบบ narrowcast
     * https://developers.line.biz/en/docs/messaging-api/sending-messages/#narrowcast-request-sample
     */
    public function progressnarrowcast(string | int $requestId): array {
        $data = [
            'query' => [
                'requestId' => $requestId,
            ],
        ];

        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', 'progress/narrowcast', $data, $header);
    }

    /*
     * Send broadcast message
     * ส่งข้อความเดียวกันไปยังผู้ใช้ทั้งหมดที่เป็นเพื่อนกับ บอท
     * https://developers.line.biz/en/reference/messaging-api/#send-broadcast-message
     */
    public function broadcast(array $messages, bool $notificationDisabled = false, string | int $retryKey = ''): array {
        $data = [
            'json' => [
                'messages'             => $messages,
                'notificationDisabled' => $notificationDisabled,
            ],
        ];

        $header = [
            'Content-Type'  => 'application/json',
            'Authorization' => $this->provider->authorization,
        ];

        if (!empty($retryKey)) {
            $header['X-Line-Retry-Key'] = $retryKey;
        }

        return $this->request('post', 'message/broadcast', $data);
    }

    /*
     * Get the target limit for sending messages this month
     * จำนวนที่ส่งได้
     * https://developers.line.biz/en/reference/messaging-api/#get-quota
     */
    public function quota(): array {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', 'message/quota', $data ?? [], $header);
    }

    /*
     * Get number of messages sent this month.
     * จำนวนที่ใช้ไปแล้ว
     * https://developers.line.biz/en/reference/messaging-api/#get-consumption
     */
    public function quotaconsumption(): array {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', 'message/quota/consumption', $data ?? [], $header);
    }

    /*
     * Get number of sent reply messages
     * https://developers.line.biz/en/reference/messaging-api/#get-number-of-reply-messages
     */
    public function deliveryreply(int | string $date): array {
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
     * Get number of sent push messages
     * https://developers.line.biz/en/reference/messaging-api/#get-number-of-push-messages
     */
    public function deliverypush(int | string $date): array {
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
     * Get number of sent multicast messages
     * https://developers.line.biz/en/reference/messaging-api/#get-number-of-multicast-messages
     */
    public function deliverymulticast(int | string $date): array {
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
     * Get number of sent broadcast messages
     * https://developers.line.biz/en/reference/messaging-api/#get-number-of-broadcast-messages
     */
    public function deliverybroadcast(int | string $date): array {
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
     * Validate message objects of a reply message
     * https://developers.line.biz/en/reference/messaging-api/#validate-message-objects-of-reply-message
     */
    public function validatereply(array $messages): array {
        $data = [
            'json' => [
                'messages' => $messages,
            ],
        ];

        $header = [
            'Content-Type'  => 'application/json',
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('post', 'message/validate/reply', $data, $header);
    }

    /*
     * Validate message objects of a push message
     * https://developers.line.biz/en/reference/messaging-api/#validate-message-objects-of-push-message
     */
    public function validatepush(array $messages): array {
        $data = [
            'json' => [
                'messages' => $messages,
            ],
        ];

        $header = [
            'Content-Type'  => 'application/json',
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('post', 'message/validate/push', $data, $header);
    }

    /*
     * Validate message objects of a multicast message
     * https://developers.line.biz/en/reference/messaging-api/#validate-message-objects-of-multicast-message
     */
    public function validatemulticast(array $messages): array {
        $data = [
            'json' => [
                'messages' => $messages,
            ],
        ];

        $header = [
            'Content-Type'  => 'application/json',
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('post', 'message/validate/multicast', $data, $header);
    }

    /*
     * Validate message objects of a narrowcast message
     * https://developers.line.biz/en/reference/messaging-api/#validate-message-objects-of-narrowcast-message
     */
    public function validatenarrowcast(array $messages): array {
        $data = [
            'json' => [
                'messages' => $messages,
            ],
        ];

        $header = [
            'Content-Type'  => 'application/json',
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('post', 'message/validate/narrowcast', $data, $header);
    }

    /*
     * Validate message objects of a broadcast message
     * https://developers.line.biz/en/reference/messaging-api/#validate-message-objects-of-broadcast-message
     */
    public function validatebroadcast(array $messages): array {
        $data = [
            'json' => [
                'messages' => $messages,
            ],
        ];

        $header = [
            'Content-Type'  => 'application/json',
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('post', 'message/validate/broadcast', $data, $header);
    }

    /*
     * Get number of units used this month
     */
    public function aggregationInfo(): array {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', 'message/aggregation/info', $data ?? [], $header);
    }

    /*
     * Get name list of units used this month
     * https://developers.line.biz/en/reference/messaging-api/#get-a-list-of-unit-names-assigned-during-this-month
     */
    public function aggregationlist(int | string $limit = 100, int | string $start = 0): array {
        $data = [
            'query' => [
                'limit' => $limit,
                'start' => $start,
            ],
        ];

        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', 'message/aggregation/list', $data, $header);
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