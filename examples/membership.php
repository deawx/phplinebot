<?php

// ทดสอบแผนสมาชิก (Membership) ของบัญชีทางการ
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/bootstrap.php';

exampleRun(function (Cyberthai\Linebot\Line $line): void {
    $userId = getenv('LINE_USER_ID') ?: '';
    $membershipId = getenv('LINE_MEMBERSHIP_ID') ?: '';

    exampleDump('getList()', $line->membership()->getList());

    if ($userId !== '') {
        exampleDump('subscription()', $line->membership()->subscription($userId));
    }

    if ($membershipId !== '') {
        exampleDump('userIds()', $line->membership()->userIds($membershipId));
    }
});
