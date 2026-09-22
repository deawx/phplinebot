<?php

// ทดสอบ Channel Access Token รุ่น 2.1 / v3 (ไม่ใช่ LINE Login)
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/bootstrap.php';

exampleRun(function (Cyberthai\Linebot\Line $line): void {
    $jwt = getenv('LINE_JWT') ?: '';
    $accessToken = getenv('LINE_OAUTH2_ACCESS_TOKEN') ?: '';

    // token แบบ stateless อายุ 15 นาที ใช้ channel ID + secret ได้เลย
    exampleDump('tokenV3()', $line->oauth2()->tokenV3());

    if ($jwt !== '') {
        exampleDump('token()', $line->oauth2()->token($jwt));
        exampleDump('tokens()', $line->oauth2()->tokens($jwt));
        exampleDump('tokenV3($jwt)', $line->oauth2()->tokenV3($jwt));
    } else {
        echo "ข้าม token()/tokens() ของ v2.1 เพราะยังไม่ได้ตั้ง LINE_JWT", PHP_EOL;
    }

    if ($accessToken !== '') {
        exampleDump('verify()', $line->oauth2()->verify($accessToken));

        if (getenv('LINE_ALLOW_REVOKE') === '1') {
            exampleDump('revoke()', $line->oauth2()->revoke($accessToken));
        }
    }
});
