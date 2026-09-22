<?php

namespace Cyberthai\Linebot\Helpers;

use Cyberthai\Linebot\Provider\LineProviderInterface;

class Coupon
{
    /*
     * สร้างตัวช่วยเรียก Coupon API
     */
    public function __construct(public LineProviderInterface $provider)
    {
        //
    }

    /*
     * สร้างคูปองใหม่
     * @link https://developers.line.biz/en/reference/messaging-api/#create-coupon
     */
    public function create(array $coupon): array
    {
        $header = [
            'Content-Type' => 'application/json',
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('post', 'coupon', ['json' => $coupon], $header);
    }

    /*
     * ดึงรายการคูปองแบบแบ่งหน้า
     * @link https://developers.line.biz/en/reference/messaging-api/#get-coupons-list
     */
    public function getList(array $status = [], string $start = '', int $limit = 0): array
    {
        $query = [];

        if ($status !== []) {
            $query['status'] = $status;
        }

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

        return $this->request('get', 'coupon', $data, $header);
    }

    /*
     * ดูรายละเอียดคูปอง
     * @link https://developers.line.biz/en/reference/messaging-api/#get-coupon
     */
    public function get(string $couponId): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', "coupon/{$couponId}", [], $header);
    }

    /*
     * ปิดคูปองไม่ให้ใช้ต่อ
     * @link https://developers.line.biz/en/reference/messaging-api/#discontinue-coupon
     */
    public function close(string $couponId): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('put', "coupon/{$couponId}/close", [], $header);
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
