<?php

// โหลด autoload ของแพ็กเกจนี้เมื่อรันจากโฟลเดอร์ examples ในรีโป
$autoload = dirname(__DIR__) . '/vendor/autoload.php';

if (!is_file($autoload)) {
    // กรณีลงผ่าน Composer แล้วอยู่ที่ vendor/deawx/linebot/examples
    $autoload = dirname(__DIR__, 3) . '/autoload.php';
}

if (!is_file($autoload)) {
    fwrite(STDERR, "ยังไม่มี vendor/autoload.php ให้รัน composer require deawx/linebot ในโปรเจกต์ก่อน\n");
    exit(1);
}

require $autoload;
require_once __DIR__ . '/helpers.php';
