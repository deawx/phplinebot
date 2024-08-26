<?php

declare(strict_types=1);

namespace cyberthai\Linebot\Helpers;

use cyberthai\Linebot\Provider\LineProviderInterface;

/*
     * Richh menu Manager
     * https://developers.line.biz/en/reference/messaging-api/#rich-menu
     */

class Richmenu {
    public function __construct(public LineProviderInterface $provider) {
    }

    /*
     * Create rich menu
     * https://developers.line.biz/en/reference/messaging-api/#create-rich-menu
     * return richMenuId
     */
    public function create(array $content): array {
        $data = [
            'json' => $content
        ];

        $header = [
            'Content-Type' => 'application/json',
            'Authorization' => $this->provider->authorization
        ];

        return $this->request(1, 'post', 'richmenu', $data, $header);
    }

    /*
     * Validate rich menu object
     * https://developers.line.biz/en/reference/messaging-api/#validate-rich-menu-object
     * Before creating a rich menu
     */
    public function validate(array $content): array {
        $data = [
            'json' => $content
        ];

        $header = [
            'Content-Type' => 'application/json',
            'Authorization' => $this->provider->authorization
        ];

        return $this->request(1, 'post', 'richmenu/validate', $data, $header);
    }

    /*
     * Upload rich menu image
     * https://developers.line.biz/en/reference/messaging-api/#upload-rich-menu-image
     */
    public function uploadimage(string $richMenuId, string $img): array {
        $data = [
            'body' => $img
        ];

        $header = [
            'Content-Type' => 'image/jpeg',
            'Authorization' => $this->provider->authorization
        ];

        return $this->request(2, 'post', "richmenu/{$richMenuId}/content", $data, $header);
    }

    /*
     * Download rich menu image
     * https://developers.line.biz/en/reference/messaging-api/#download-rich-menu-image
     */
    public function downloadimage(string|int $richMenuId): array {
        $header = [
            'Authorization' => $this->provider->authorization
        ];

        return $this->request(2, 'get', "richmenu/{$richMenuId}/content", $data ?? [], $header);
    }

    /*
     * Get rich menu list
     * ดึงรายการ rich menu list ทั้งหมด
     * https://developers.line.biz/en/reference/messaging-api/#get-rich-menu-list
     */
    public function getList(): array {
        $header = [
            'Authorization' => $this->provider->authorization
        ];

        return $this->request(1, 'get', 'richmenu/list', $data ?? [], $header);
    }

    /*
     * Get rich menu
     * ดึงรายการ rich menu ทีละรายการ
     * https://developers.line.biz/en/reference/messaging-api/#get-rich-menu
     */
    public function get(string|int $richMenuId): array {
        $header = [
            'Authorization' => $this->provider->authorization
        ];

        return $this->request(1, 'get', "richmenu/{$richMenuId}", $data ?? [], $header);
    }

    /*
     * Delete rich menu
     * https://developers.line.biz/en/reference/messaging-api/#delete-rich-menu
     */
    public function delete(string|int $richMenuId): array {
        $header = [
            'Authorization' => $this->provider->authorization
        ];

        return $this->request(1, 'delete', "richmenu/{$richMenuId}", $data ?? [], $header);
    }

    /*
     * Set default rich menu
     * ตั้งค่า default richmenu ให้กับผู้ใช้งานบอทเรา
     * https://developers.line.biz/en/reference/messaging-api/#set-default-rich-menu
     */
    public function setdefault(string|int $richMenuId): array {
        $header = [
            'Authorization' => $this->provider->authorization
        ];

        return $this->request(1, 'post', "user/all/richmenu/{$richMenuId}", $data ?? [], $header);
    }

    /*
     * Get default rich menu ID
     * เรียกดูการตั้งค่า default richmenu ให้กับผู้ใช้งานบอทเรา
     * https://developers.line.biz/en/reference/messaging-api/#get-default-rich-menu-id
     */
    public function getdefault(): array {
        $header = [
            'Authorization' => $this->provider->authorization
        ];

        return $this->request(1, 'get', 'user/all/richmenu', [], $header);
    }

    /*
     * Clear default rich menu
     * ยกเลิกการตั้งค่า default richmenu ให้กับผู้ใช้งานบอทเรา
     * https://developers.line.biz/en/reference/messaging-api/#clear-default-rich-menu
     */
    public function canceldefault(): array {
        $header = [
            'Authorization' => $this->provider->authorization
        ];

        return $this->request(1, 'delete', 'user/all/richmenu', $data ?? [], $header);
    }



    /**
     * Per-user rich menu
     * สำหรับจัดการ Richmenu ของแต่ละ User
     * ประยุกต์กับการ login แล้วให้เปลี่ยน richmenu
     * https://developers.line.biz/en/reference/messaging-api/#per-user-rich-menu
     */

    /*
     * Link rich menu to user
     * สำหรับ Set richmenu ให้แต่ทีละคน
     * https://developers.line.biz/en/reference/messaging-api/#link-rich-menu-to-user
     */
    public function userlink(string $userId, string $richMenuId): array {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request(1, 'post', "user/{$userId}/richmenu/{$richMenuId}", $data ?? [], $header);
    }

    /*
     * Link rich menu to multiple users
     * สำหรับ Set richmenu ให้แต่ทีละหลายๆ คน
     * https://developers.line.biz/en/reference/messaging-api/#link-rich-menu-to-users
     */
    public function userslink(array $userIds, string $richMenuId): array {
        $data = [
            'json' => [
                'richMenuId' => $richMenuId,
                'userIds' => $userIds
            ]
        ];

        $header = [
            'Authorization' => $this->provider->authorization,
            'Content-Type' => 'application/json'
        ];

        return $this->request(1, 'post', 'richmenu/bulk/link', $data, $header);
    }

    /*
     * Get rich menu ID of user
     * ดูว่า user ใช้ richmenu ตัวใหน
     * https://developers.line.biz/en/reference/messaging-api/#get-rich-menu-id-of-user
     */
    public function getuserlink(array $userId): array {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request(1, 'get', "user/{$userId}/richmenu", $data ?? [], $header);
    }

    /*
     * Unlink rich menu from user
     * ลบ rich menu ออกจาก user ทีละคน
     * https://developers.line.biz/en/reference/messaging-api/#unlink-rich-menu-from-user
     */
    public function deleteuserlink(string $userId): array {
        $header = [
            'Authorization' => $this->provider->authorization,
            'Content-Type' => 'application/json'
        ];

        return $this->request(1, 'delete', "user/{$userId}/richmenu", $data ?? [], $header);
    }

    /*
     * Unlink rich menus from multiple users
     * ลบ rich menu ออกจาก user ทีละหลาย ๆ คนพร้อมกัน
     * https://developers.line.biz/en/reference/messaging-api/#unlink-rich-menu-from-users
     */
    public function deleteuserslink(array $userIds): array {
        $data = [
            'json' => [
                'userIds' => $userIds
            ]
        ];

        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request(1, 'post', 'richmenu/bulk/unlink', $data, $header);
    }

    /*
     * Replace or unlink the linked rich menus in batches
     * https://developers.line.biz/en/reference/messaging-api/#batch-control-rich-menus-of-users
     */
    public function replaceBatch(array $operations, string $resumeRequestKey = ''): array {
        $data = [
            'json' => [
                'operations' => $operations,
            ]
        ];

        if (!empty($resumeRequestKey)) {
            $data['json']['resumeRequestKey'] = $resumeRequestKey;
        }

        $header = [
            'Authorization' => $this->provider->authorization,
            'Content-Type' => 'application/json'
        ];

        return $this->request(1, 'post', 'richmenu/batch', $data, $header);
    }

    /*
     * Get the status of rich menu batch control
     * https://developers.line.biz/en/reference/messaging-api/#get-batch-control-rich-menus-progress-status
     */
    public function progressBatch(array $userIds): array {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request(1, 'post', 'richmenu/progress/batch', $data ?? [], $header);
    }

    /*
     * Validate a request of rich menu batch control
     * https://developers.line.biz/en/reference/messaging-api/#validate-batch-control-rich-menus-request
     */
    public function validateBatch(array $operations, string $resumeRequestKey = ''): array {
        $data = [
            'json' => [
                'operations' => $operations,
            ]
        ];

        if (!empty($resumeRequestKey)) {
            $data['json']['resumeRequestKey'] = $resumeRequestKey;
        }

        $header = [
            'Authorization' => $this->provider->authorization,
            'Content-Type' => 'application/json'
        ];

        return $this->request(1, 'post', 'richmenu/validate/batch', $data, $header);
    }



    /*
     * Rich menu alias SECTION
     */
    /*
     * Create rich menu alias
     * https://developers.line.biz/en/reference/messaging-api/#create-rich-menu-alias
     */
    public function createalias(string $richMenuAliasId, string $richMenuId): array {
        $data = [
            'json' => [
                'richMenuAliasId' => $richMenuAliasId,
                'richMenuId' => $richMenuId
            ]
        ];

        $header = [
            'Authorization' => $this->provider->authorization,
            'Content-Type' => 'application/json'
        ];

        return $this->request(1, 'post', 'richmenu/alias', $data, $header);
    }

    /*
     * Delete rich menu alias
     * https://developers.line.biz/en/reference/messaging-api/#delete-rich-menu-alias
     */
    public function deletealias(string $richMenuAliasId): array {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request(1, 'delete', "richmenu/alias/{$richMenuAliasId}", $data ?? [], $header);
    }

    /*
     * Update rich menu alias
     * https://developers.line.biz/en/reference/messaging-api/#update-rich-menu-alias
     */
    public function updatealias(string $richMenuAliasId, string $richMenuId): array {
        $data = [
            'json' => [
                'richMenuId' => $richMenuId
            ]
        ];

        $header = [
            'Authorization' => $this->provider->authorization,
            'Content-Type' => 'application/json'
        ];

        return $this->request(1, 'post', "richmenu/alias/{$richMenuAliasId}", $data, $header);
    }

    /*
     * Get rich menu alias information
     * https://developers.line.biz/en/reference/messaging-api/#get-rich-menu-alias-by-id
     */
    public function getalias(string $richMenuAliasId): array {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request(1, 'get', "richmenu/alias/{$richMenuAliasId}", $data ?? [], $header);
    }

    /*
     * Get list of rich menu alias
     * https://developers.line.biz/en/reference/messaging-api/#get-rich-menu-alias-list
     */
    public function getaliaslist(): array {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request(1, 'get', 'richmenu/alias/list', $data ?? [], $header);
    }

    /*
     * Provider Request API
     */
    public function request(
        int $type,
        string $method,
        string $urlPath,
        array $data = [],
        array $header = []
    ): array {
        return $this->provider->request(
            $type == 1 ? 'bot' : 'data',
            $urlPath,
            strtoupper($method),
            $header,
            $data
        );
    }
}