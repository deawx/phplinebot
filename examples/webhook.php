<?php

// ตัวอย่างตั้ง URL webhook ของช่อง แล้วดูค่า / ทดสอบ
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/bootstrap.php';

use GuzzleHttp\Exception\GuzzleException;

try {
    $line = exampleLine();

    // URL ที่ LINE จะยิงเข้ามา ตั้งใน LINE_WEBHOOK_URL หรือแก้ด้านล่าง
    $url = getenv('LINE_WEBHOOK_URL') ?: 'https://example.com/webhook.php';

    // บันทึก endpoint ลงช่อง
    $result = $line->webhook()->set($url);
    var_dump($result);

    // อ่าน URL และสถานะที่ตั้งไว้
    $result = $line->webhook()->get();
    var_dump($result);

    // ให้ LINE ลองยิงมาที่ endpoint ที่ตั้งไว้
    $result = $line->webhook()->test();
    var_dump($result);
} catch (GuzzleException | Throwable $th) {
    echo $th->getMessage(), PHP_EOL;
}
