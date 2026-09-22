<?php

// ทดสอบโปรไฟล์ผู้ใช้ที่เพิ่มเพื่อนแล้ว และรายการ user ID ของเพื่อน
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/bootstrap.php';

exampleRun(function (Cyberthai\Linebot\Line $line): void {
    $userId = getenv('LINE_USER_ID') ?: '';

    if ($userId !== '') {
        exampleDump('users()->profile()', $line->users()->profile($userId));
    } else {
        echo "ข้าม profile() เพราะยังไม่ได้ตั้ง LINE_USER_ID", PHP_EOL;
    }

    exampleDump('users()->followers()', $line->users()->followers());
});
