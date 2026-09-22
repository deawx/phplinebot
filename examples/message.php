<?php

// ทดสอบส่งข้อความ ดูโควตา และตรวจโครงสร้างข้อความ
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/bootstrap.php';

exampleRun(function (Cyberthai\Linebot\Line $line): void {
    $text = [['type' => 'text', 'text' => 'สวัสดีจาก examples/message.php']];
    $userId = getenv('LINE_USER_ID') ?: '';
    $date = getenv('LINE_DATE') ?: date('Ymd');

    // ตรวจ JSON ข้อความก่อนส่งจริง
    exampleDump('validatePush()', $line->message()->validatePush($text));
    exampleDump('validateReply()', $line->message()->validateReply($text));
    exampleDump('validateMulticast()', $line->message()->validateMulticast($text));
    exampleDump('validateNarrowcast()', $line->message()->validateNarrowcast($text));
    exampleDump('validateBroadcast()', $line->message()->validateBroadcast($text));

    exampleDump('quota()', $line->message()->quota());
    exampleDump('quotaConsumption()', $line->message()->quotaConsumption());
    exampleDump('deliveryReply()', $line->message()->deliveryReply($date));
    exampleDump('deliveryPush()', $line->message()->deliveryPush($date));
    exampleDump('deliveryMulticast()', $line->message()->deliveryMulticast($date));
    exampleDump('deliveryBroadcast()', $line->message()->deliveryBroadcast($date));
    exampleDump('aggregationInfo()', $line->message()->aggregationInfo());
    exampleDump('aggregationList()', $line->message()->aggregationList());

    if ($userId !== '') {
        // แสดงกำลังพิมพ์ในแชท 1 ต่อ 1
        exampleDump('loading()', $line->message()->loading($userId, 5));
        exampleDump('push()', $line->message()->push($userId, $text));
        exampleDump('multicast()', $line->message()->multicast([$userId], $text));
    } else {
        echo "ข้าม push/multicast/loading เพราะยังไม่ได้ตั้ง LINE_USER_ID", PHP_EOL;
    }

    $markAsReadToken = getenv('LINE_MARK_AS_READ_TOKEN') ?: '';
    if ($markAsReadToken !== '') {
        exampleDump('markAsRead()', $line->message()->markAsRead($markAsReadToken));
    }

    $requestId = getenv('LINE_NARROWCAST_REQUEST_ID') ?: '';
    if ($requestId !== '') {
        exampleDump('progressNarrowcast()', $line->message()->progressNarrowcast($requestId));
    }

    // ส่งหาเพื่อนทั้งหมด ต้องตั้ง LINE_ALLOW_BROADCAST=1 เอง
    if (getenv('LINE_ALLOW_BROADCAST') === '1') {
        exampleDump('broadcast()', $line->message()->broadcast($text));
    }
});
