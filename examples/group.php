<?php

// ทดสอบข้อมูลกลุ่มที่บอทเป็นสมาชิกอยู่
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/bootstrap.php';

exampleRun(function (Cyberthai\Linebot\Line $line): void {
    $groupId = getenv('LINE_GROUP_ID') ?: '';
    $userId = getenv('LINE_USER_ID') ?: '';

    if ($groupId === '') {
        echo "ตั้ง LINE_GROUP_ID ก่อนรันไฟล์นี้", PHP_EOL;
        return;
    }

    exampleDump('summary()', $line->group()->summary($groupId));
    exampleDump('membersCount()', $line->group()->membersCount($groupId));
    exampleDump('membersIds()', $line->group()->membersIds($groupId));

    if ($userId !== '') {
        exampleDump('member()', $line->group()->member($groupId, $userId));
    }

    // ให้ออกจากกลุ่ม ต้องตั้ง LINE_ALLOW_LEAVE=1 เอง
    if (getenv('LINE_ALLOW_LEAVE') === '1') {
        exampleDump('leave()', $line->group()->leave($groupId));
    }
});
