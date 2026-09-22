<?php

// ตัวอย่างรับ webhook จาก LINE ตรวจลายเซ็น แล้ว reply ข้อความเดิมกลับ
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/bootstrap.php';

use GuzzleHttp\Exception\GuzzleException;

$channelSecret = getenv('LINE_CHANNEL_SECRET') ?: '';

// ลายเซ็นที่ LINE ส่งมาใน header
$signature = $_SERVER['HTTP_X_LINE_SIGNATURE'] ?? '';

// เนื้อหาดิบของคำขอ ต้องใช้ตัวนี้ตอนคำนวณ HMAC ห้ามอ่าน JSON ก่อนแล้ว stringify ใหม่
$body = file_get_contents('php://input') ?: '';

$computed = base64_encode(hash_hmac('sha256', $body, $channelSecret, true));

// ไม่ตรงกับ Channel secret แปลว่าไม่ใช่ LINE หรือตั้งค่าผิด
if ($channelSecret === '' || $signature === '' || !hash_equals($computed, $signature)) {
    http_response_code(400);
    echo 'invalid signature';
    exit;
}

$payload = json_decode($body, true);

if (!is_array($payload)) {
    http_response_code(400);
    echo 'invalid json';
    exit;
}

try {
    $line = exampleLine();
    $events = $payload['events'] ?? [];

    foreach ($events as $event) {
        $replyToken = $event['replyToken'] ?? '';
        $type = $event['type'] ?? '';
        $messageType = $event['message']['type'] ?? '';
        $text = $event['message']['text'] ?? '';

        // ตัวอย่างนี้ตอบเฉพาะข้อความตัวอักษร
        if ($replyToken === '' || $type !== 'message' || $messageType !== 'text') {
            continue;
        }

        $line->message()->reply($replyToken, [
            ['type' => 'text', 'text' => $text],
        ]);
    }
} catch (GuzzleException | Throwable $th) {
    http_response_code(500);
    echo $th->getMessage();
    exit;
}

http_response_code(200);
echo 'OK';
