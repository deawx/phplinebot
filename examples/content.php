<?php

// ทดสอบดาวน์โหลดไฟล์ที่ผู้ใช้ส่งมา (ต้องมี messageId จาก webhook)
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/bootstrap.php';

exampleRun(function (Cyberthai\Linebot\Line $line): void {
    $messageId = getenv('LINE_MESSAGE_ID') ?: '';

    if ($messageId === '') {
        echo "ตั้ง LINE_MESSAGE_ID ก่อนรันไฟล์นี้", PHP_EOL;
        return;
    }

    exampleDump('transcoding()', $line->content()->transcoding($messageId));

    $file = $line->content()->get($messageId);
    echo 'get() contentType=', $file['contentType'] ?? '', PHP_EOL;
    echo 'get() ขนาดไบนารี=', strlen((string) ($file['content'] ?? '')), PHP_EOL;

    $preview = $line->content()->preview($messageId);
    echo 'preview() contentType=', $preview['contentType'] ?? '', PHP_EOL;
    echo 'preview() ขนาดไบนารี=', strlen((string) ($preview['content'] ?? '')), PHP_EOL;
});
