<?php

// ทดสอบดูรายการ audience สำหรับ narrowcast
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/bootstrap.php';

exampleRun(function (Cyberthai\Linebot\Line $line): void {
    $audienceGroupId = getenv('LINE_AUDIENCE_GROUP_ID') ?: '';

    exampleDump('getList()', $line->audience()->getList());
    exampleDump('getSharedList()', $line->audience()->getSharedList());

    if ($audienceGroupId !== '') {
        exampleDump('get()', $line->audience()->get($audienceGroupId));
        exampleDump('getShared()', $line->audience()->getShared($audienceGroupId));
    }
});
