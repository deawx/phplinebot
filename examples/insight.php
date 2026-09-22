<?php

// ทดสอบสถิติ Insight วันที่ใช้รูปแบบ yyyyMMdd
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/bootstrap.php';

exampleRun(function (Cyberthai\Linebot\Line $line): void {
    $date = getenv('LINE_DATE') ?: date('Ymd');
    $from = getenv('LINE_DATE_FROM') ?: $date;
    $to = getenv('LINE_DATE_TO') ?: $date;
    $requestId = getenv('LINE_INSIGHT_REQUEST_ID') ?: '';
    $unit = getenv('LINE_AGGREGATION_UNIT') ?: '';
    $richMenuId = getenv('LINE_RICHMENU_ID') ?: '';

    exampleDump('messageDelivery()', $line->insight()->messageDelivery($date));
    exampleDump('followers()', $line->insight()->followers($date));
    exampleDump('demographic()', $line->insight()->demographic());

    if ($requestId !== '') {
        exampleDump('messageEvent()', $line->insight()->messageEvent($requestId));
    }

    if ($unit !== '') {
        exampleDump(
            'messageEventAggregation()',
            $line->insight()->messageEventAggregation($unit, $from, $to),
        );
    }

    if ($richMenuId !== '') {
        exampleDump('richmenuSummary()', $line->insight()->richmenuSummary($richMenuId, $from, $to));
        exampleDump('richmenuDaily()', $line->insight()->richmenuDaily($richMenuId, $from, $to));
    }
});
