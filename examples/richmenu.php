<?php

// ทดสอบดูรายการริชเมนู และผูกริชเมนูถ้ามี ID
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/bootstrap.php';

exampleRun(function (Cyberthai\Linebot\Line $line): void {
    $richMenuId = getenv('LINE_RICHMENU_ID') ?: '';
    $aliasId = getenv('LINE_RICHMENU_ALIAS_ID') ?: '';
    $userId = getenv('LINE_USER_ID') ?: '';

    // อ็อบเจ็กต์ตัวอย่างสำหรับตรวจโครงสร้างก่อนสร้างจริง
    $richMenuObject = [
        'size' => ['width' => 2500, 'height' => 1686],
        'selected' => false,
        'name' => 'example-menu',
        'chatBarText' => 'เมนู',
        'areas' => [
            [
                'bounds' => ['x' => 0, 'y' => 0, 'width' => 2500, 'height' => 1686],
                'action' => ['type' => 'message', 'text' => 'สวัสดี'],
            ],
        ],
    ];

    exampleDump('validate()', $line->richmenu()->validate($richMenuObject));
    exampleDump('getList()', $line->richmenu()->getList());
    exampleDump('getDefaultList()', $line->richmenu()->getDefaultList());
    exampleDump('getAliasList()', $line->richmenu()->getAliasList());

    if ($richMenuId !== '') {
        exampleDump('get()', $line->richmenu()->get($richMenuId));
    }

    if ($aliasId !== '') {
        exampleDump('getAlias()', $line->richmenu()->getAlias($aliasId));
    }

    if ($userId !== '') {
        exampleDump('getByUserId()', $line->richmenu()->getByUserId($userId));
    }

    $batchRequestId = getenv('LINE_RICHMENU_BATCH_REQUEST_ID') ?: '';
    if ($batchRequestId !== '') {
        exampleDump('progressBatch()', $line->richmenu()->progressBatch($batchRequestId));
    }
});
