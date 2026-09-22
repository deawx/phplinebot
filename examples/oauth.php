<?php

// ทดสอบออก / ตรวจ Channel Access Token อายุสั้น (ไม่ใช่ LINE Login)
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/bootstrap.php';

exampleRun(function (Cyberthai\Linebot\Line $line): void {
    $issued = $line->oauth()->accessToken();
    exampleDump('accessToken()', $issued);

    $accessToken = $issued['access_token'] ?? '';
    if ($accessToken === '') {
        return;
    }

    exampleDump('verify()', $line->oauth()->verify($accessToken));

    // เพิกถอน token ที่เพิ่งออก ต้องตั้ง LINE_ALLOW_REVOKE=1
    if (getenv('LINE_ALLOW_REVOKE') === '1') {
        exampleDump('revoke()', $line->oauth()->revoke($accessToken));
    }
});
