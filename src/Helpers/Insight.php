<?php

namespace Cyberthai\Linebot\Helpers;

use Cyberthai\Linebot\Provider\LineProviderInterface;

class Insight
{
    /*
     * สร้างตัวช่วยเรียก Insights API
     */
    public function __construct(public LineProviderInterface $provider)
    {
        //
    }

    /*
     * ดูจำนวนข้อความที่ส่งสำเร็จในวันที่กำหนด (yyyyMMdd)
     * @link https://developers.line.biz/en/reference/messaging-api/#get-number-of-delivery-messages
     */
    public function messageDelivery(string $date): array
    {
        $data = [
            'query' => [
                'date' => $date,
            ],
        ];

        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', 'insight/message/delivery', $data, $header);
    }

    /*
     * ดูจำนวนเพื่อนในวันที่กำหนด (yyyyMMdd)
     * @link https://developers.line.biz/en/reference/messaging-api/#get-number-of-followers
     */
    public function followers(string $date): array
    {
        $data = [
            'query' => [
                'date' => $date,
            ],
        ];

        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', 'insight/followers', $data, $header);
    }

    /*
     * ดูข้อมูลประชากรของเพื่อน
     * @link https://developers.line.biz/en/reference/messaging-api/#get-demographic
     */
    public function demographic(): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', 'insight/demographic', [], $header);
    }

    /*
     * ดูสถิติการโต้ตอบกับข้อความ (narrowcast / broadcast)
     * @link https://developers.line.biz/en/reference/messaging-api/#get-message-event
     */
    public function messageEvent(string $requestId): array
    {
        $data = [
            'query' => [
                'requestId' => $requestId,
            ],
        ];

        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', 'insight/message/event', $data, $header);
    }

    /*
     * ดูสถิติต่อหน่วย aggregation ของ push / multicast
     * @link https://developers.line.biz/en/reference/messaging-api/#get-statistics-per-unit
     */
    public function messageEventAggregation(string $customAggregationUnit, string $from, string $to): array
    {
        $data = [
            'query' => [
                'customAggregationUnit' => $customAggregationUnit,
                'from' => $from,
                'to' => $to,
            ],
        ];

        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', 'insight/message/event/aggregation', $data, $header);
    }

    /*
     * ดูสถิติริชเมนูรวมในช่วงวันที่กำหนด
     * @link https://developers.line.biz/en/reference/messaging-api/#get-rich-menu-insight-summary
     */
    public function richmenuSummary(string $richMenuId, string $from, string $to): array
    {
        $data = [
            'query' => [
                'from' => $from,
                'to' => $to,
            ],
        ];

        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', "insight/richmenu/{$richMenuId}/summary", $data, $header);
    }

    /*
     * ดูสถิติริชเมนูแยกตามวัน
     * @link https://developers.line.biz/en/reference/messaging-api/#get-rich-menu-insight-daily
     */
    public function richmenuDaily(string $richMenuId, string $from, string $to): array
    {
        $data = [
            'query' => [
                'from' => $from,
                'to' => $to,
            ],
        ];

        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', "insight/richmenu/{$richMenuId}/daily", $data, $header);
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
