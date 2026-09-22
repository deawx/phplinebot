<?php

use Cyberthai\Linebot\Line;

// สร้างไคลเอนต์จากค่า environment ของช่อง Messaging API
function exampleLine(): Line
{
    return new Line([
        'channelId' => getenv('LINE_CHANNEL_ID') ?: '',
        'clientSecret' => getenv('LINE_CHANNEL_SECRET') ?: '',
        'channelAccessToken' => getenv('LINE_CHANNEL_ACCESS_TOKEN') ?: '',
    ]);
}

// แสดงผลลัพธ์จาก LINE พร้อมชื่อขั้นตอน
function exampleDump(string $label, mixed $result): void
{
    echo $label, PHP_EOL;
    var_dump($result);
    echo PHP_EOL;
}

// ห่อ try/catch ให้ไฟล์ตัวอย่างไม่ต้องเขียนซ้ำ
function exampleRun(callable $callback): void
{
    try {
        $callback(exampleLine());
    } catch (Throwable $th) {
        echo $th->getMessage(), PHP_EOL;
    }
}
