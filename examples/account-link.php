<?php

// ทดสอบออก link token สำหรับผูกบัญชีบริการกับ LINE
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/bootstrap.php';

exampleRun(function (Cyberthai\Linebot\Line $line): void {
    $userId = getenv('LINE_USER_ID') ?: '';

    if ($userId === '') {
        echo "ตั้ง LINE_USER_ID ก่อนรันไฟล์นี้", PHP_EOL;
        return;
    }

    exampleDump('token()', $line->accountLink()->token($userId));
});
