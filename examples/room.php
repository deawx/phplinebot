<?php

// ทดสอบห้องแชทหลายคนที่บอทอยู่
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/bootstrap.php';

exampleRun(function (Cyberthai\Linebot\Line $line): void {
    $roomId = getenv('LINE_ROOM_ID') ?: '';
    $userId = getenv('LINE_USER_ID') ?: '';

    if ($roomId === '') {
        echo "ตั้ง LINE_ROOM_ID ก่อนรันไฟล์นี้", PHP_EOL;
        return;
    }

    exampleDump('memberCount()', $line->room()->memberCount($roomId));
    exampleDump('memberIds()', $line->room()->memberIds($roomId));

    if ($userId !== '') {
        exampleDump('member()', $line->room()->member($roomId, $userId));
    }

    if (getenv('LINE_ALLOW_LEAVE') === '1') {
        exampleDump('leave()', $line->room()->leave($roomId));
    }
});
