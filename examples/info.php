<?php

// ทดสอบดึงข้อมูลบัญชีทางการ (ชื่อ รูป โหมดแชท)
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/bootstrap.php';

use Cyberthai\Linebot\Line;

exampleRun(function (Line $line): void {
    exampleDump('info()', $line->info());
});
