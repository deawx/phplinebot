<?php

// ทดสอบดูรายการคูปอง และดูรายละเอียดถ้ามี couponId
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/bootstrap.php';

exampleRun(function (Cyberthai\Linebot\Line $line): void {
    $couponId = getenv('LINE_COUPON_ID') ?: '';

    exampleDump('getList()', $line->coupon()->getList());
    exampleDump("getList(['RUNNING'])", $line->coupon()->getList(['RUNNING']));

    if ($couponId !== '') {
        exampleDump('get()', $line->coupon()->get($couponId));
    }
});
