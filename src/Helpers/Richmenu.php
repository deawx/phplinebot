<?php

namespace Cyberthai\Linebot\Helpers;

use Cyberthai\Linebot\Provider\LineProviderInterface;

class Richmenu
{
    /*
     * สร้างตัวช่วยจัดการริชเมนู
     */
    public function __construct(public LineProviderInterface $provider)
    {
        //
    }

    /*
     * สร้างริชเมนู
     * @link https://developers.line.biz/en/reference/messaging-api/#create-rich-menu
     */
    public function create(array $content): array
    {
        $data = [
            'json' => $content,
        ];

        $header = [
            'Content-Type' => 'application/json',
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request(1, 'post', 'richmenu', $data, $header);
    }

    /*
     * ตรวจโครงสร้างอ็อบเจ็กต์ริชเมนู
     * @link https://developers.line.biz/en/reference/messaging-api/#validate-rich-menu-object
     */
    public function validate(array $content): array
    {
        $data = [
            'json' => $content,
        ];

        $header = [
            'Content-Type' => 'application/json',
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request(1, 'post', 'richmenu/validate', $data, $header);
    }

    /*
     * อัปโหลดรูปริชเมนู (JPEG หรือ PNG)
     * @link https://developers.line.biz/en/reference/messaging-api/#upload-rich-menu-image
     */
    public function uploadImg(string $richMenuId, string $img, string $contentType = ''): array
    {
        if (is_file($img)) {
            if ($contentType === '') {
                $contentType = $this->imageContentType($img);
            }

            $img = (string) file_get_contents($img);
        }

        if ($contentType === '') {
            $contentType = 'image/jpeg';
        }

        $data = [
            'body' => $img,
        ];

        $header = [
            'Content-Type' => $contentType,
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request(2, 'post', "richmenu/{$richMenuId}/content", $data, $header);
    }

    /*
     * ดาวน์โหลดรูปริชเมนู
     * @link https://developers.line.biz/en/reference/messaging-api/#download-rich-menu-image
     */
    public function downloadImg(string|int $richMenuId): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request(2, 'get', "richmenu/{$richMenuId}/content", [], $header);
    }

    /*
     * ดึงรายการริชเมนู
     * @link https://developers.line.biz/en/reference/messaging-api/#get-rich-menu-list
     */
    public function getList(): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request(1, 'get', 'richmenu/list', [], $header);
    }

    /*
     * ดูริชเมนูจาก ID
     * @link https://developers.line.biz/en/reference/messaging-api/#get-rich-menu
     */
    public function get(string|int $richMenuId): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request(1, 'get', "richmenu/{$richMenuId}", [], $header);
    }

    /*
     * ลบริชเมนู
     * @link https://developers.line.biz/en/reference/messaging-api/#delete-rich-menu
     */
    public function delete(string|int $richMenuId): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request(1, 'delete', "richmenu/{$richMenuId}", [], $header);
    }

    /*
     * ตั้งริชเมนูเริ่มต้นของบอท
     * @link https://developers.line.biz/en/reference/messaging-api/#set-default-rich-menu
     */
    public function setDefault(string|int $richMenuId): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request(1, 'post', "user/all/richmenu/{$richMenuId}", [], $header);
    }

    /*
     * ดู ID ริชเมนูเริ่มต้น
     * @link https://developers.line.biz/en/reference/messaging-api/#get-default-rich-menu-id
     */
    public function getDefaultList(): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request(1, 'get', 'user/all/richmenu', [], $header);
    }

    /*
     * ยกเลิกริชเมนูเริ่มต้น
     * @link https://developers.line.biz/en/reference/messaging-api/#cancel-default-rich-menu
     */
    public function cancelDefault(): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request(1, 'delete', 'user/all/richmenu', [], $header);
    }

    /*
     * สร้างริชเมนู alias
     * @link https://developers.line.biz/en/reference/messaging-api/#create-rich-menu-alias
     */
    public function createAlias(string $richMenuAliasId, string $richMenuId): array
    {
        $data = [
            'json' => [
                'richMenuAliasId' => $richMenuAliasId,
                'richMenuId' => $richMenuId,
            ],
        ];

        $header = [
            'Authorization' => $this->provider->authorization,
            'Content-Type' => 'application/json',
        ];

        return $this->request(1, 'post', 'richmenu/alias', $data, $header);
    }

    /*
     * ลบริชเมนู alias
     * @link https://developers.line.biz/en/reference/messaging-api/#delete-rich-menu-alias
     */
    public function deleteAlias(string $richMenuAliasId): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request(1, 'delete', "richmenu/alias/{$richMenuAliasId}", [], $header);
    }

    /*
     * อัปเดต alias ให้ชี้ริชเมนูอื่น
     * @link https://developers.line.biz/en/reference/messaging-api/#update-rich-menu-alias
     */
    public function updateAlias(string $richMenuAliasId, string $richMenuId): array
    {
        $data = [
            'json' => [
                'richMenuId' => $richMenuId,
            ],
        ];

        $header = [
            'Authorization' => $this->provider->authorization,
            'Content-Type' => 'application/json',
        ];

        return $this->request(1, 'post', "richmenu/alias/{$richMenuAliasId}", $data, $header);
    }

    /*
     * ดูริชเมนูจาก ID alias information
     * @link https://developers.line.biz/en/reference/messaging-api/#get-rich-menu-alias-by-id
     */
    public function getAlias(string $richMenuAliasId): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request(1, 'get', "richmenu/alias/{$richMenuAliasId}", [], $header);
    }

    /*
     * ดึงรายการ alias ของริชเมนู
     * @link https://developers.line.biz/en/reference/messaging-api/#get-rich-menu-alias-list
     */
    public function getAliasList(): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request(1, 'get', 'richmenu/alias/list', [], $header);
    }

    /*
     * ผูกริชเมนูกับผู้ใช้คนเดียว
     * @link https://developers.line.biz/en/reference/messaging-api/#link-rich-menu-to-user
     */
    public function userLink(string $userId, string $richMenuId): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request(1, 'post', "user/{$userId}/richmenu/{$richMenuId}", [], $header);
    }

    /*
     * ผูกริชเมนูกับผู้ใช้หลายคน
     * @link https://developers.line.biz/en/reference/messaging-api/#link-rich-menu-to-users
     */
    public function usersLink(array $userIds, string $richMenuId): array
    {
        $data = [
            'json' => [
                'richMenuId' => $richMenuId,
                'userIds' => $userIds,
            ],
        ];

        $header = [
            'Authorization' => $this->provider->authorization,
            'Content-Type' => 'application/json',
        ];

        return $this->request(1, 'post', 'richmenu/bulk/link', $data, $header);
    }

    /*
     * ดูริชเมนูจาก ID ID of user
     * @link https://developers.line.biz/en/reference/messaging-api/#get-rich-menu-id-of-user
     */
    public function getByUserId(string $userId): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request(1, 'get', "user/{$userId}/richmenu", [], $header);
    }

    /*
     * ถอดริชเมนูออกจากผู้ใช้คนเดียว
     * @link https://developers.line.biz/en/reference/messaging-api/#unlink-rich-menu-from-user
     */
    public function deleteByUserId(string $userId): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request(1, 'delete', "user/{$userId}/richmenu", [], $header);
    }

    /*
     * ถอดริชเมนูออกจากผู้ใช้หลายคน
     * @link https://developers.line.biz/en/reference/messaging-api/#unlink-rich-menu-from-users
     */
    public function deleteByUserIds(array $userIds): array
    {
        $data = [
            'json' => [
                'userIds' => $userIds,
            ],
        ];

        $header = [
            'Authorization' => $this->provider->authorization,
            'Content-Type' => 'application/json',
        ];

        return $this->request(1, 'post', 'richmenu/bulk/unlink', $data, $header);
    }

    /*
     * เปลี่ยนหรือถอดริชเมนูเป็นชุด
     * @link https://developers.line.biz/en/reference/messaging-api/#batch-control-rich-menus-of-users
     */
    public function replaceBatch(array $operations, string $resumeRequestKey = ''): array
    {
        $json = [
            'operations' => $operations,
        ];

        if ($resumeRequestKey !== '') {
            $json['resumeRequestKey'] = $resumeRequestKey;
        }

        $data = [
            'json' => $json,
        ];

        $header = [
            'Authorization' => $this->provider->authorization,
            'Content-Type' => 'application/json',
        ];

        return $this->request(1, 'post', 'richmenu/batch', $data, $header);
    }

    /*
     * ดูสถานะงาน batch ของริชเมนู
     * @link https://developers.line.biz/en/reference/messaging-api/#get-batch-control-rich-menus-progress-status
     */
    public function progressBatch(string $requestId): array
    {
        $data = [
            'query' => [
                'requestId' => $requestId,
            ],
        ];

        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request(1, 'get', 'richmenu/progress/batch', $data, $header);
    }

    /*
     * ตรวจคำขอ batch ริชเมนูก่อนส่งจริง
     * @link https://developers.line.biz/en/reference/messaging-api/#validate-batch-control-rich-menus-request
     */
    public function validateBatch(array $operations, string $resumeRequestKey = ''): array
    {
        $json = [
            'operations' => $operations,
        ];

        if ($resumeRequestKey !== '') {
            $json['resumeRequestKey'] = $resumeRequestKey;
        }

        $data = [
            'json' => $json,
        ];

        $header = [
            'Authorization' => $this->provider->authorization,
            'Content-Type' => 'application/json',
        ];

        return $this->request(1, 'post', 'richmenu/validate/batch', $data, $header);
    }

    /*
     * ส่งคำขอไป Messaging API หรือ api-data
     */
    public function request(
        int $type,
        string $method,
        string $urlPath,
        array $data = [],
        array $header = [],
    ): array {
        return $this->provider->request(
            $type == 1 ? 'bot' : 'data',
            $urlPath,
            strtoupper($method),
            $header,
            $data,
        );
    }

    /*
     * เดา Content-Type ของรูปจากนามสกุลไฟล์
     */
    private function imageContentType(string $path): string
    {
        $extension = strtolower((string) pathinfo($path, PATHINFO_EXTENSION));

        return $extension === 'png' ? 'image/png' : 'image/jpeg';
    }
}
