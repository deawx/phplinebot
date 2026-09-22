<?php

namespace Cyberthai\Linebot\Helpers;

use Cyberthai\Linebot\Provider\LineProviderInterface;

class Audience
{
    /*
     * สร้างตัวช่วยจัดการ audience
     */
    public function __construct(public LineProviderInterface $provider)
    {
        //
    }

    /*
     * สร้าง audience โดยอัปโหลด user ID หรือ IFA แบบ JSON
     * @link https://developers.line.biz/en/reference/messaging-api/#create-upload-audience-group
     */
    public function upload(
        string $description,
        bool $isIfaAudience = false,
        string $uploadDescription = '',
        array $audiences = [],
    ): array {
        $json = [
            'description' => $description,
            'isIfaAudience' => $isIfaAudience,
        ];

        if ($uploadDescription !== '') {
            $json['uploadDescription'] = $uploadDescription;
        }

        if ($audiences !== []) {
            $json['audiences'] = $audiences;
        }

        $data = [
            'json' => $json,
        ];

        $header = [
            'Content-Type' => 'application/json',
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('post', 'audienceGroup/upload', $data, $header);
    }

    /*
     * สร้าง audience โดยอัปโหลดไฟล์รายชื่อ
     * @link https://developers.line.biz/en/reference/messaging-api/#create-upload-audience-group-by-file
     */
    public function uploadByFile(
        string $description,
        string $file,
        bool $isIfaAudience = false,
        string $uploadDescription = '',
    ): array {
        $multipart = [
            [
                'name' => 'description',
                'contents' => $description,
            ],
            $this->filePart($file),
            [
                'name' => 'isIfaAudience',
                'contents' => $isIfaAudience ? 'true' : 'false',
            ],
        ];

        if ($uploadDescription !== '') {
            $multipart[] = [
                'name' => 'uploadDescription',
                'contents' => $uploadDescription,
            ];
        }

        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request(
            'post',
            'audienceGroup/upload/byFile',
            ['multipart' => $multipart],
            $header,
            'data',
        );
    }

    /*
     * เพิ่ม user ID หรือ IFA เข้า audience แบบ JSON
     * @link https://developers.line.biz/en/reference/messaging-api/#update-upload-audience-group
     */
    public function add(
        string|int $audienceGroupId,
        array $audiences,
        string $uploadDescription = '',
    ): array {
        $json = [
            'audienceGroupId' => $audienceGroupId,
            'audiences' => $audiences,
        ];

        if ($uploadDescription !== '') {
            $json['uploadDescription'] = $uploadDescription;
        }

        $data = [
            'json' => $json,
        ];

        $header = [
            'Content-Type' => 'application/json',
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('put', 'audienceGroup/upload', $data, $header);
    }

    /*
     * เพิ่ม user ID หรือ IFA เข้า audience จากไฟล์
     * @link https://developers.line.biz/en/reference/messaging-api/#update-upload-audience-group-by-file
     */
    public function addByFile(
        string|int $audienceGroupId,
        string $file,
        string $uploadDescription = '',
    ): array {
        $multipart = [
            [
                'name' => 'audienceGroupId',
                'contents' => (string) $audienceGroupId,
            ],
            $this->filePart($file),
        ];

        if ($uploadDescription !== '') {
            $multipart[] = [
                'name' => 'uploadDescription',
                'contents' => $uploadDescription,
            ];
        }

        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request(
            'put',
            'audienceGroup/upload/byFile',
            ['multipart' => $multipart],
            $header,
            'data',
        );
    }

    /*
     * สร้าง audience จากคนที่คลิกลิงก์ในข้อความ
     * @link https://developers.line.biz/en/reference/messaging-api/#create-click-audience-group
     */
    public function click(string $description, string $requestId, string $clickUrl = ''): array
    {
        $json = [
            'description' => $description,
            'requestId' => $requestId,
        ];

        if ($clickUrl !== '') {
            $json['clickUrl'] = $clickUrl;
        }

        $data = [
            'json' => $json,
        ];

        $header = [
            'Content-Type' => 'application/json',
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('post', 'audienceGroup/click', $data, $header);
    }

    /*
     * สร้าง audience จากคนที่เห็นข้อความ
     * @link https://developers.line.biz/en/reference/messaging-api/#create-imp-audience-group
     */
    public function imp(string $description, string $requestId): array
    {
        $data = [
            'json' => [
                'description' => $description,
                'requestId' => $requestId,
            ],
        ];

        $header = [
            'Content-Type' => 'application/json',
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('post', 'audienceGroup/imp', $data, $header);
    }

    /*
     * เปลี่ยนชื่อ audience
     * @link https://developers.line.biz/en/reference/messaging-api/#set-description-audience-group
     */
    public function rename(string|int $audienceGroupId, string $description): array
    {
        $data = [
            'json' => [
                'description' => $description,
            ],
        ];

        $header = [
            'Content-Type' => 'application/json',
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request(
            'put',
            "audienceGroup/{$audienceGroupId}/updateDescription",
            $data,
            $header,
        );
    }

    /*
     * ลบ audience
     * @link https://developers.line.biz/en/reference/messaging-api/#delete-audience-group
     */
    public function delete(string|int $audienceGroupId): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('delete', "audienceGroup/{$audienceGroupId}", [], $header);
    }

    /*
     * ดูข้อมูล audience
     * @link https://developers.line.biz/en/reference/messaging-api/#get-audience-group
     */
    public function get(string|int $audienceGroupId): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', "audienceGroup/{$audienceGroupId}", [], $header);
    }

    /*
     * ดึงรายการ audience ของช่อง
     * @link https://developers.line.biz/en/reference/messaging-api/#get-audience-groups
     */
    public function getList(
        int $page = 1,
        int $size = 20,
        string $description = '',
        string $status = '',
        bool $includesExternalPublicGroups = true,
        string $createRoute = '',
    ): array {
        $query = [
            'page' => $page,
            'size' => $size > 40 ? 40 : $size,
            'includesExternalPublicGroups' => $includesExternalPublicGroups ? 'true' : 'false',
        ];

        if ($description !== '') {
            $query['description'] = $description;
        }

        if ($status !== '') {
            $query['status'] = $status;
        }

        if ($createRoute !== '') {
            $query['createRoute'] = $createRoute;
        }

        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', 'audienceGroup/list', ['query' => $query], $header);
    }

    /*
     * ดูข้อมูล audience ที่แชร์จาก Business Manager
     * @link https://developers.line.biz/en/reference/messaging-api/#get-shared-audience
     */
    public function getShared(string|int $audienceGroupId): array
    {
        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', "audienceGroup/shared/{$audienceGroupId}", [], $header);
    }

    /*
     * ดึงรายการ audience ที่แชร์จาก Business Manager
     * @link https://developers.line.biz/en/reference/messaging-api/#get-shared-audience-list
     */
    public function getSharedList(
        int $page = 1,
        int $size = 20,
        string $description = '',
        string $status = '',
        string $createRoute = '',
        bool $includesOwnedAudienceGroups = false,
    ): array {
        $query = [
            'page' => $page,
            'size' => $size > 40 ? 40 : $size,
            'includesOwnedAudienceGroups' => $includesOwnedAudienceGroups ? 'true' : 'false',
        ];

        if ($description !== '') {
            $query['description'] = $description;
        }

        if ($status !== '') {
            $query['status'] = $status;
        }

        if ($createRoute !== '') {
            $query['createRoute'] = $createRoute;
        }

        $header = [
            'Authorization' => $this->provider->authorization,
        ];

        return $this->request('get', 'audienceGroup/shared/list', ['query' => $query], $header);
    }

    /*
     * ส่งคำขอไป Messaging API
     */
    public function request(
        string $method,
        string $urlPath,
        array $data = [],
        array $header = [],
        string $apiKind = 'bot',
    ): array {
        return $this->provider->request(
            $apiKind,
            $urlPath,
            strtoupper($method),
            $header,
            $data,
        );
    }

    /*
     * ประกอบไฟล์สำหรับอัปโหลดแบบ multipart
     */
    private function filePart(string $file): array
    {
        if (is_file($file)) {
            return [
                'name' => 'file',
                'contents' => fopen($file, 'r'),
                'filename' => basename($file),
                'headers' => [
                    'Content-Type' => 'text/plain',
                ],
            ];
        }

        return [
            'name' => 'file',
            'contents' => $file,
            'filename' => 'audience.txt',
            'headers' => [
                'Content-Type' => 'text/plain',
            ],
        ];
    }
}
