<?php

namespace Cyberthai\Linebot;

use Cyberthai\Linebot\Provider\LineProvider;
use Cyberthai\Linebot\Helpers\Webhook;
use Cyberthai\Linebot\Helpers\Content;
use Cyberthai\Linebot\Helpers\Oauth2;
use Cyberthai\Linebot\Helpers\Oauth;
use Cyberthai\Linebot\Helpers\Message;
use Cyberthai\Linebot\Helpers\Audience;
use Cyberthai\Linebot\Helpers\Insight;
use Cyberthai\Linebot\Helpers\Users;
use Cyberthai\Linebot\Helpers\Room;
use Cyberthai\Linebot\Helpers\Group;
use Cyberthai\Linebot\Helpers\Richmenu;
use Cyberthai\Linebot\Helpers\Membership;
use Cyberthai\Linebot\Helpers\Coupon;
use Cyberthai\Linebot\Helpers\AccountLink;

class Line
{
    public LineProvider $provider;

    /*
     * สร้างไคลเอนต์ Messaging API
     */
    public function __construct(public array $config)
    {
        $this->provider = new LineProvider(
            $config['clientId'] ?? $config['channelId'] ?? '',
            $config['clientSecret'] ?? '',
            $config['channelAccessToken'] ?? '',
        );
    }

    /*
     * ตั้งค่า ดึงข้อมูล และทดสอบ webhook
     */
    public function webhook(): Webhook
    {
        return new Webhook($this->provider);
    }

    /*
     * ดาวน์โหลดเนื้อหาที่ผู้ใช้ส่งมา (รูป วิดีโอ เสียง)
     */
    public function content(): Content
    {
        return new Content($this->provider);
    }

    /*
     * ออก ตรวจสอบ และเพิกถอน channel access token รุ่น 2.1 / v3
     */
    public function oauth2(): Oauth2
    {
        return new Oauth2($this->provider);
    }

    /*
     * ออก ตรวจสอบ และเพิกถอน Channel Access Token ของบอท (ไม่ใช่ LINE Login)
     */
    public function oauth(): Oauth
    {
        return new Oauth($this->provider);
    }

    /*
     * ส่งข้อความ ดูโควตา และจัดการแชท
     */
    public function message(): Message
    {
        return new Message($this->provider);
    }

    /*
     * จัดการกลุ่มผู้รับ (audience) สำหรับ narrowcast
     */
    public function audience(): Audience
    {
        return new Audience($this->provider);
    }

    /*
     * ดูสถิติข้อความ เพื่อน และริชเมนู
     */
    public function insight(): Insight
    {
        return new Insight($this->provider);
    }

    /*
     * ดึงโปรไฟล์ผู้ใช้และรายชื่อเพื่อน
     */
    public function users(): Users
    {
        return new Users($this->provider);
    }

    /*
     * ข้อมูลห้องแชทหลายคนที่บอทอยู่
     */
    public function room(): Room
    {
        return new Room($this->provider);
    }

    /*
     * ข้อมูลกลุ่มที่บอทอยู่
     */
    public function group(): Group
    {
        return new Group($this->provider);
    }

    /*
     * สร้าง ผูก และจัดการริชเมนู
     */
    public function richmenu(): Richmenu
    {
        return new Richmenu($this->provider);
    }

    /*
     * ข้อมูลแผนสมาชิก (Membership) ของบัญชีทางการ
     */
    public function membership(): Membership
    {
        return new Membership($this->provider);
    }

    /*
     * สร้าง ดู และปิดคูปอง
     */
    public function coupon(): Coupon
    {
        return new Coupon($this->provider);
    }

    /*
     * ออก token สำหรับผูกบัญชีบริการกับ LINE
     */
    public function accountLink(): AccountLink
    {
        return new AccountLink($this->provider);
    }

    /*
     * ดึงข้อมูลบัญชีทางการ (บอท)
     * @link https://developers.line.biz/en/reference/messaging-api/#get-bot-info
     */
    public function info(): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->provider->request(
            'bot',
            'info',
            'GET',
            $header,
            [],
        );
    }
}
