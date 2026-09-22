[<img src="cyberthai.png" alt="Cyberthai" width="180">](https://www.cyberthai.net)

# deawx/linebot

ไลบรารี PHP สำหรับเรียก [LINE Messaging API](https://developers.line.biz/en/reference/messaging-api/)

การเรียก HTTP ทั้งหมดใช้ **[guzzlehttp/guzzle](https://github.com/guzzle/guzzle) ^7 หรือ ^8** Composer ของแอปจะเลือกสายที่เข้ากับแพ็กเกจอื่นได้ เมื่อ LINE ตอบ 4xx/5xx Guzzle จะโยน `GuzzleHttp\Exception\ClientException` หรือ `ServerException`

ยังไม่ได้ยิง API จริงทุกเมธอดในเครื่องนี้ ให้ทดสอบกับ Channel ของคุณเองก่อนขึ้นโปรดักชัน

## ความต้องการ

- PHP 8.0 ขึ้นไป
- Composer
- Channel ของ Messaging API: Channel ID, Channel secret, Channel access token

## ติดตั้ง

```bash
composer require deawx/linebot
```

Composer จะดึงแพ็กเกจจาก [Packagist](https://packagist.org/packages/deawx/linebot) และลง `guzzlehttp/guzzle` (^7 หรือ ^8) ให้เอง

ตัวอย่างโค้ดอยู่ที่โฟลเดอร์ [`examples/`](examples/) บน GitHub (ไม่ติดตอน `composer require`)

## ค่าคอนฟิกและ `.env`

ไลบรารี**ไม่ได้**ติดตั้ง `vlucas/phpdotenv` เพราะการอ่านไฟล์ `.env` เป็นหน้าที่ของแอป

`getenv()` เป็นฟังก์ชันของ PHP ใช้ได้เลยถ้าเซ็ต environment ที่เว็บเซิร์ฟเวอร์ / Docker / ระบบปฏิบัติการแล้ว

ถ้าต้องการไฟล์ `.env` ให้ติดตั้ง dotenv **ในแอป**:

```bash
composer require vlucas/phpdotenv
```

```php
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use Cyberthai\Linebot\Line;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$line = new Line([
    'channelId' => $_ENV['LINE_CHANNEL_ID'],
    'clientSecret' => $_ENV['LINE_CHANNEL_SECRET'],
    'channelAccessToken' => $_ENV['LINE_CHANNEL_ACCESS_TOKEN'],
]);
```

ตัวอย่าง `.env` (อย่า commit ไฟล์นี้):

```env
LINE_CHANNEL_ID=
LINE_CHANNEL_SECRET=
LINE_CHANNEL_ACCESS_TOKEN=
```

ใส่ค่าตรงๆ ก็ได้ ใช้ `clientId` แทน `channelId` ได้เช่นกัน ถ้าค่าใดว่างตอนสร้าง จะโยน `Cyberthai\Linebot\Exceptions\LineInitializeException`

## เริ่มต้นใช้งาน

```php
require __DIR__ . '/vendor/autoload.php';

use Cyberthai\Linebot\Line;

$line = new Line([
    'channelId' => getenv('LINE_CHANNEL_ID'),
    'clientSecret' => getenv('LINE_CHANNEL_SECRET'),
    'channelAccessToken' => getenv('LINE_CHANNEL_ACCESS_TOKEN'),
]);
```

ในตัวอย่างด้านล่างใช้ข้อความแบบนี้:

```php
$text = [['type' => 'text', 'text' => 'สวัสดี']];
```

---

## สารบัญหมวด API

| หมวด | จุดเข้า |
|---|---|
| [บัญชีทางการ](#หมวดบัญชีทางการ) | `$line->info()` |
| [Webhook](#หมวดwebhook) | `$line->webhook()` |
| [เนื้อหาจากผู้ใช้](#หมวดเนื้อหาจากผู้ใช้) | `$line->content()` |
| [ส่งข้อความ](#หมวดส่งข้อความ) | `$line->message()` |
| [ผู้ใช้งาน](#หมวดผู้ใช้งาน) | `$line->users()` |
| [กลุ่ม](#หมวดกลุ่ม) | `$line->group()` |
| [ห้องแชทหลายคน](#หมวดห้องแชทหลายคน) | `$line->room()` |
| [ริชเมนู](#หมวดริชเมนู) | `$line->richmenu()` |
| [Audience](#หมวดaudience) | `$line->audience()` |
| [สถิติ Insight](#หมวดสถิติ-insight) | `$line->insight()` |
| [แผนสมาชิก](#หมวดแผนสมาชิก) | `$line->membership()` |
| [คูปอง](#หมวดคูปอง) | `$line->coupon()` |
| [ผูกบัญชี](#หมวดผูกบัญชี) | `$line->accountLink()` |
| [Channel Access Token](#หมวดchannel-access-token) | `$line->oauth()` / `$line->oauth2()` |

---

## หมวดบัญชีทางการ

`$line->info()`

ดึงข้อมูลบัญชีทางการ เช่น ชื่อ รูป และโหมดแชท

```php
$line->info();
```

---

## หมวด Webhook

`$line->webhook()`

ตั้งค่า ดู และทดสอบ webhook ของช่อง

เมธอดในหมวดนี้:

```php
$line->webhook();
$line->webhook()->set($url);
$line->webhook()->get();
$line->webhook()->test();
$line->webhook()->test($url);
```

### `$line->webhook()->set($url)`

ตั้ง URL webhook ต้องเป็น HTTPS

```php
$line->webhook()->set('https://example.com/webhook');
```

### `$line->webhook()->get()`

ดู URL และสถานะที่ตั้งไว้

```php
$line->webhook()->get();
```

### `$line->webhook()->test($url = '')`

ทดสอบว่า LINE ยิง webhook ถึงเซิร์ฟเวอร์ได้หรือไม่ ไม่ส่ง URL จะทดสอบค่าที่ตั้งไว้แล้ว

```php
$line->webhook()->test();
$line->webhook()->test('https://example.com/webhook');
```

---

## หมวดเนื้อหาจากผู้ใช้

`$line->content()`

ดาวน์โหลดไฟล์ที่ผู้ใช้ส่งมา ยิงไป `https://api-data.line.me`

เมธอดในหมวดนี้:

```php
$line->content();
$line->content()->get($messageId);
$line->content()->transcoding($messageId);
$line->content()->preview($messageId);
```

### `$line->content()->get($messageId)`

ดาวน์โหลดรูป วิดีโอ หรือเสียง คืนอาร์เรย์ที่มี `content` (ไบนารี) และ `contentType`

```php
$file = $line->content()->get($messageId);
file_put_contents('photo.jpg', $file['content']);
```

### `$line->content()->transcoding($messageId)`

ตรวจว่าวิดีโอหรือเสียงพร้อมดาวน์โหลดหรือยัง

```php
$line->content()->transcoding($messageId);
```

### `$line->content()->preview($messageId)`

ดาวน์โหลดรูปตัวอย่างของรูปหรือวิดีโอ

```php
$line->content()->preview($messageId);
```

---

## หมวดส่งข้อความ

`$line->message()`

ส่งข้อความ ดูโควตา ตรวจโครงสร้างข้อความ และจัดการแชท

เมธอดในหมวดนี้:

```php
$line->message();
$line->message()->reply($replyToken, $text);
$line->message()->push($userId, $text);
$line->message()->multicast([$userId1, $userId2], $text);
$line->message()->narrowcast($text, $recipient);
$line->message()->progressNarrowcast($requestId);
$line->message()->broadcast($text);
$line->message()->loading($userId, 5);
$line->message()->markAsRead($markAsReadToken);
$line->message()->quota();
$line->message()->quotaConsumption();
$line->message()->deliveryReply('20260919');
$line->message()->deliveryPush('20260919');
$line->message()->deliveryMulticast('20260919');
$line->message()->deliveryBroadcast('20260919');
$line->message()->validateReply($text);
$line->message()->validatePush($text);
$line->message()->validateMulticast($text);
$line->message()->validateNarrowcast($text);
$line->message()->validateBroadcast($text);
$line->message()->aggregationInfo();
$line->message()->aggregationList();
```

### `$line->message()->reply($replyToken, $messages, $notificationDisabled = false)`

ตอบกลับด้วย `replyToken` จาก webhook ใช้ได้ครั้งเดียวภายในเวลาที่ LINE กำหนด

```php
$line->message()->reply($replyToken, $text);
$line->message()->reply($replyToken, $text, true);
```

### `$line->message()->push($to, $messages, $notificationDisabled = false, $retryKey = '')`

ส่งหา user / group / room ได้ทุกเมื่อ `$retryKey` เป็น UUID สำหรับส่งซ้ำ

```php
$line->message()->push($userId, $text);
$line->message()->push($userId, $text, false, $uuid);
```

### `$line->message()->multicast($userIds, $messages, $notificationDisabled = false, $retryKey = '')`

ส่งข้อความชุดเดียวกันหาหลาย user ID (ส่งกลุ่มหรือห้องไม่ได้)

```php
$line->message()->multicast([$userId1, $userId2], $text);
```

### `$line->message()->narrowcast($messages, $recipient = [], $demographic = [], $limit = 0, $notificationDisabled = false, $retryKey = '')`

ส่งตาม audience หรือตัวกรองประชากร

```php
$line->message()->narrowcast($text, [
    'type' => 'audience',
    'audienceGroupId' => 1234567890123,
]);
```

### `$line->message()->progressNarrowcast($requestId)`

ดูสถานะการส่ง narrowcast ใช้ `requestId` จาก header `X-Line-Request-Id`

```php
$line->message()->progressNarrowcast($requestId);
```

### `$line->message()->broadcast($messages, $notificationDisabled = false, $retryKey = '')`

ส่งหาเพื่อนทั้งหมดของบัญชีทางการ

```php
$line->message()->broadcast($text);
```

### `$line->message()->loading($chatId, $loadingSeconds = 0)`

แสดงแอนิเมชันกำลังพิมพ์ในแชท 1 ต่อ 1 วินาทีต้องเป็นพหุคูณของ 5 ช่วง 5–60

```php
$line->message()->loading($userId, 5);
```

### `$line->message()->markAsRead($markAsReadToken)`

ทำเครื่องหมายว่าอ่านข้อความผู้ใช้แล้ว ใช้ token จาก webhook

```php
$line->message()->markAsRead($markAsReadToken);
```

### `$line->message()->quota()`

ดูเพดานส่งข้อความเดือนนี้

```php
$line->message()->quota();
```

### `$line->message()->quotaConsumption()`

ดูจำนวนข้อความที่ส่งไปแล้วเดือนนี้

```php
$line->message()->quotaConsumption();
```

### `$line->message()->deliveryReply($date)`

จำนวน reply ที่ส่งสำเร็จในวันนั้น `date` เป็น `yyyyMMdd`

```php
$line->message()->deliveryReply('20260919');
```

### `$line->message()->deliveryPush($date)`

```php
$line->message()->deliveryPush('20260919');
```

### `$line->message()->deliveryMulticast($date)`

```php
$line->message()->deliveryMulticast('20260919');
```

### `$line->message()->deliveryBroadcast($date)`

```php
$line->message()->deliveryBroadcast('20260919');
```

### `$line->message()->validateReply($messages)`

ตรวจโครงสร้างข้อความสำหรับ reply ก่อนส่งจริง

```php
$line->message()->validateReply($text);
```

### `$line->message()->validatePush($messages)`

```php
$line->message()->validatePush($text);
```

### `$line->message()->validateMulticast($messages)`

```php
$line->message()->validateMulticast($text);
```

### `$line->message()->validateNarrowcast($messages)`

```php
$line->message()->validateNarrowcast($text);
```

### `$line->message()->validateBroadcast($messages)`

```php
$line->message()->validateBroadcast($text);
```

### `$line->message()->aggregationInfo()`

ดูจำนวนหน่วย aggregation ที่ใช้เดือนนี้

```php
$line->message()->aggregationInfo();
```

### `$line->message()->aggregationList($limit = 0, $start = '')`

ดึงรายชื่อหน่วย aggregation เดือนนี้ ใช้ `$start` จาก `next` เพื่อดึงหน้าถัดไป

```php
$line->message()->aggregationList();
$line->message()->aggregationList(100, $next);
```

---

## หมวดผู้ใช้งาน

`$line->users()`

ดึงโปรไฟล์ผู้ใช้ที่เพิ่มเพื่อนแล้ว และรายการ user ID ของเพื่อน

เมธอดในหมวดนี้:

```php
$line->users();
$line->users()->profile($userId);
$line->users()->followers();
$line->users()->followers($next, 1000);
```

### `$line->users()->profile($userId)`

ดึงชื่อ รูป และสถานะของผู้ใช้ที่เพิ่มเพื่อนแล้ว ใช้กับคนที่บล็อกไม่ได้

```php
$line->users()->profile($userId);
```

### `$line->users()->followers($start = '', $limit = 0)`

ดึงรายการ user ID ของเพื่อน ไม่ส่งพารามิเตอร์ LINE จะใช้ค่าเริ่มต้น (limit 300) ถ้ามีหน้าถัดไปให้ส่งค่า `next` จากรอบก่อนเป็น `$start` `limit` สูงสุด 1000

```php
$line->users()->followers();
$line->users()->followers($next, 1000);
```

---

## หมวดกลุ่ม

`$line->group()`

ข้อมูลกลุ่มที่บอทเป็นสมาชิกอยู่

เมธอดในหมวดนี้:

```php
$line->group();
$line->group()->summary($groupId);
$line->group()->membersCount($groupId);
$line->group()->membersIds($groupId);
$line->group()->membersIds($groupId, $next);
$line->group()->member($groupId, $userId);
$line->group()->leave($groupId);
```

### `$line->group()->summary($groupId)`

ดูชื่อและรูปกลุ่ม

```php
$line->group()->summary($groupId);
```

### `$line->group()->membersCount($groupId)`

นับจำนวนสมาชิกในกลุ่ม

```php
$line->group()->membersCount($groupId);
```

### `$line->group()->membersIds($groupId, $start = '')`

ดึง user ID ของสมาชิก ถ้าเกิน 100 คนใช้ `$start` จาก `next`

```php
$line->group()->membersIds($groupId);
$line->group()->membersIds($groupId, $next);
```

### `$line->group()->member($groupId, $userId)`

ดึงโปรไฟล์สมาชิกในกลุ่ม

```php
$line->group()->member($groupId, $userId);
```

### `$line->group()->leave($groupId)`

ให้บอทออกจากกลุ่ม

```php
$line->group()->leave($groupId);
```

---

## หมวดห้องแชทหลายคน

`$line->room()`

ห้องแชทหลายคนที่บอทอยู่ ไม่มี API สรุปห้องแบบกลุ่ม

เมธอดในหมวดนี้:

```php
$line->room();
$line->room()->memberCount($roomId);
$line->room()->memberIds($roomId);
$line->room()->memberIds($roomId, $next);
$line->room()->member($roomId, $userId);
$line->room()->leave($roomId);
```

### `$line->room()->memberCount($roomId)`

นับจำนวนสมาชิกในห้อง

```php
$line->room()->memberCount($roomId);
```

### `$line->room()->memberIds($roomId, $start = '')`

ดึง user ID ของสมาชิก ถ้าเกิน 100 คนใช้ `$start` จาก `next`

```php
$line->room()->memberIds($roomId);
$line->room()->memberIds($roomId, $next);
```

### `$line->room()->member($roomId, $userId)`

ดึงโปรไฟล์สมาชิกในห้อง

```php
$line->room()->member($roomId, $userId);
```

### `$line->room()->leave($roomId)`

ให้บอทออกจากห้อง

```php
$line->room()->leave($roomId);
```

---

## หมวดริชเมนู

`$line->richmenu()`

สร้าง ผูก และจัดการริชเมนู อัปโหลด/ดาวน์โหลดรูปใช้ `api-data.line.me`

เมธอดในหมวดนี้:

```php
$line->richmenu();
$line->richmenu()->create($richMenuObject);
$line->richmenu()->validate($richMenuObject);
$line->richmenu()->uploadImg($richMenuId, $path);
$line->richmenu()->downloadImg($richMenuId);
$line->richmenu()->getList();
$line->richmenu()->get($richMenuId);
$line->richmenu()->delete($richMenuId);
$line->richmenu()->setDefault($richMenuId);
$line->richmenu()->getDefaultList();
$line->richmenu()->cancelDefault();
$line->richmenu()->createAlias($aliasId, $richMenuId);
$line->richmenu()->updateAlias($aliasId, $richMenuId);
$line->richmenu()->getAlias($aliasId);
$line->richmenu()->getAliasList();
$line->richmenu()->deleteAlias($aliasId);
$line->richmenu()->userLink($userId, $richMenuId);
$line->richmenu()->usersLink([$userId1, $userId2], $richMenuId);
$line->richmenu()->getByUserId($userId);
$line->richmenu()->deleteByUserId($userId);
$line->richmenu()->deleteByUserIds([$userId1, $userId2]);
$line->richmenu()->validateBatch($operations);
$line->richmenu()->replaceBatch($operations);
$line->richmenu()->progressBatch($requestId);
```

### `$line->richmenu()->create($content)`

สร้างริชเมนูจากอ็อบเจ็กต์ตามเอกสาร LINE คืน `richMenuId`

```php
$line->richmenu()->create($richMenuObject);
```

### `$line->richmenu()->validate($content)`

ตรวจอ็อบเจ็กต์ก่อนสร้าง

```php
$line->richmenu()->validate($richMenuObject);
```

### `$line->richmenu()->uploadImg($richMenuId, $img, $contentType = '')`

อัปโหลดรูป JPEG หรือ PNG `$img` เป็นพาธไฟล์หรือไบนารี ถ้าเป็น `.png` จะส่ง `image/png` ให้

```php
$line->richmenu()->uploadImg($richMenuId, '/path/to/menu.png');
```

### `$line->richmenu()->downloadImg($richMenuId)`

ดาวน์โหลดรูปริชเมนู คืน `content` และ `contentType`

```php
$line->richmenu()->downloadImg($richMenuId);
```

### `$line->richmenu()->getList()`

ดึงรายการริชเมนูทั้งหมดของช่อง

```php
$line->richmenu()->getList();
```

### `$line->richmenu()->get($richMenuId)`

ดูริชเมนูจาก ID

```php
$line->richmenu()->get($richMenuId);
```

### `$line->richmenu()->delete($richMenuId)`

ลบริชเมนู

```php
$line->richmenu()->delete($richMenuId);
```

### `$line->richmenu()->setDefault($richMenuId)`

ตั้งริชเมนูเริ่มต้นของบอท

```php
$line->richmenu()->setDefault($richMenuId);
```

### `$line->richmenu()->getDefaultList()`

ดู ID ริชเมนูเริ่มต้น

```php
$line->richmenu()->getDefaultList();
```

### `$line->richmenu()->cancelDefault()`

ยกเลิกริชเมนูเริ่มต้น

```php
$line->richmenu()->cancelDefault();
```

### `$line->richmenu()->createAlias($richMenuAliasId, $richMenuId)`

สร้าง alias สำหรับสลับแท็บริชเมนู

```php
$line->richmenu()->createAlias('tab-a', $richMenuId);
```

### `$line->richmenu()->updateAlias($richMenuAliasId, $richMenuId)`

ให้ alias ชี้ไปริชเมนูอื่น

```php
$line->richmenu()->updateAlias('tab-a', $otherRichMenuId);
```

### `$line->richmenu()->getAlias($richMenuAliasId)`

ดูข้อมูล alias

```php
$line->richmenu()->getAlias('tab-a');
```

### `$line->richmenu()->getAliasList()`

ดึงรายการ alias ทั้งหมด

```php
$line->richmenu()->getAliasList();
```

### `$line->richmenu()->deleteAlias($richMenuAliasId)`

ลบ alias

```php
$line->richmenu()->deleteAlias('tab-a');
```

### `$line->richmenu()->userLink($userId, $richMenuId)`

ผูกริชเมนูกับผู้ใช้คนเดียว

```php
$line->richmenu()->userLink($userId, $richMenuId);
```

### `$line->richmenu()->usersLink($userIds, $richMenuId)`

ผูกริชเมนูกับผู้ใช้หลายคน

```php
$line->richmenu()->usersLink([$userId1, $userId2], $richMenuId);
```

### `$line->richmenu()->getByUserId($userId)`

ดูริชเมนูที่ผูกกับผู้ใช้อยู่

```php
$line->richmenu()->getByUserId($userId);
```

### `$line->richmenu()->deleteByUserId($userId)`

ถอดริชเมนูออกจากผู้ใช้คนเดียว

```php
$line->richmenu()->deleteByUserId($userId);
```

### `$line->richmenu()->deleteByUserIds($userIds)`

ถอดริชเมนูออกจากผู้ใช้หลายคน

```php
$line->richmenu()->deleteByUserIds([$userId1, $userId2]);
```

### `$line->richmenu()->validateBatch($operations, $resumeRequestKey = '')`

ตรวจคำขอเปลี่ยน/ถอดริชเมนูเป็นชุดก่อนส่งจริง

```php
$ops = [['type' => 'link', 'from' => $oldId, 'to' => $newId]];
$line->richmenu()->validateBatch($ops);
```

### `$line->richmenu()->replaceBatch($operations, $resumeRequestKey = '')`

เปลี่ยนหรือถอดริชเมนูเป็นชุด

```php
$line->richmenu()->replaceBatch($ops);
```

### `$line->richmenu()->progressBatch($requestId)`

ดูสถานะงาน batch จาก `requestId`

```php
$line->richmenu()->progressBatch($requestId);
```

---

## หมวด Audience

`$line->audience()`

สร้างและจัดการกลุ่มผู้รับสำหรับ narrowcast

เมธอดในหมวดนี้:

```php
$line->audience();
$line->audience()->upload($description, false, '', $audiences);
$line->audience()->uploadByFile($description, $path);
$line->audience()->add($audienceGroupId, $audiences);
$line->audience()->addByFile($audienceGroupId, $path);
$line->audience()->click($description, $requestId, $clickUrl);
$line->audience()->imp($description, $requestId);
$line->audience()->rename($audienceGroupId, $description);
$line->audience()->get($audienceGroupId);
$line->audience()->delete($audienceGroupId);
$line->audience()->getList();
$line->audience()->getShared($audienceGroupId);
$line->audience()->getSharedList();
```

### `$line->audience()->upload($description, $isIfaAudience = false, $uploadDescription = '', $audiences = [])`

สร้าง audience จาก JSON ของ user ID หรือ IFA

```php
$line->audience()->upload('แคมเปญ ก', false, '', [
    ['id' => $userId],
]);
```

### `$line->audience()->uploadByFile($description, $file, $isIfaAudience = false, $uploadDescription = '')`

สร้าง audience จากไฟล์ข้อความ หนึ่ง ID ต่อบรรทัด `$file` เป็นพาธหรือเนื้อไฟล์

```php
$line->audience()->uploadByFile('แคมเปญ ก', '/path/to/ids.txt');
```

### `$line->audience()->add($audienceGroupId, $audiences, $uploadDescription = '')`

เพิ่ม user ID หรือ IFA เข้า audience ที่มีอยู่ แบบ JSON

```php
$line->audience()->add($audienceGroupId, [['id' => $userId]]);
```

### `$line->audience()->addByFile($audienceGroupId, $file, $uploadDescription = '')`

เพิ่มรายชื่อจากไฟล์เข้า audience ที่มีอยู่

```php
$line->audience()->addByFile($audienceGroupId, '/path/to/ids.txt');
```

### `$line->audience()->click($description, $requestId, $clickUrl = '')`

สร้าง audience จากคนที่คลิกลิงก์ในข้อความ `$requestId` ของข้อความที่ส่งไป

```php
$line->audience()->click('คลิกลิงก์', $requestId, 'https://example.com');
```

### `$line->audience()->imp($description, $requestId)`

สร้าง audience จากคนที่เห็นข้อความ

```php
$line->audience()->imp('เห็นข้อความ', $requestId);
```

### `$line->audience()->rename($audienceGroupId, $description)`

เปลี่ยนชื่อ audience

```php
$line->audience()->rename($audienceGroupId, 'ชื่อใหม่');
```

### `$line->audience()->get($audienceGroupId)`

ดูข้อมูล audience

```php
$line->audience()->get($audienceGroupId);
```

### `$line->audience()->delete($audienceGroupId)`

ลบ audience

```php
$line->audience()->delete($audienceGroupId);
```

### `$line->audience()->getList($page = 1, $size = 20, $description = '', $status = '', $includesExternalPublicGroups = true, $createRoute = '')`

ดึงรายการ audience ของช่อง `size` สูงสุด 40

```php
$line->audience()->getList();
$line->audience()->getList(1, 20, '', 'READY');
```

### `$line->audience()->getShared($audienceGroupId)`

ดูข้อมูล audience ที่แชร์จาก Business Manager

```php
$line->audience()->getShared($audienceGroupId);
```

### `$line->audience()->getSharedList($page = 1, $size = 20, $description = '', $status = '', $createRoute = '', $includesOwnedAudienceGroups = false)`

ดึงรายการ audience ที่แชร์จาก Business Manager

```php
$line->audience()->getSharedList();
```

---

## หมวดสถิติ Insight

`$line->insight()`

วันที่ใช้รูปแบบ `yyyyMMdd`

เมธอดในหมวดนี้:

```php
$line->insight();
$line->insight()->messageDelivery('20260919');
$line->insight()->followers('20260919');
$line->insight()->demographic();
$line->insight()->messageEvent($requestId);
$line->insight()->messageEventAggregation('promo_a', '20260901', '20260919');
$line->insight()->richmenuSummary($richMenuId, '20260901', '20260919');
$line->insight()->richmenuDaily($richMenuId, '20260901', '20260919');
```

### `$line->insight()->messageDelivery($date)`

จำนวนข้อความที่ส่งสำเร็จในวันนั้น

```php
$line->insight()->messageDelivery('20260919');
```

### `$line->insight()->followers($date)`

จำนวนเพื่อนในวันนั้น

```php
$line->insight()->followers('20260919');
```

### `$line->insight()->demographic()`

ข้อมูลประชากรของเพื่อน

```php
$line->insight()->demographic();
```

### `$line->insight()->messageEvent($requestId)`

สถิติการโต้ตอบกับ narrowcast หรือ broadcast

```php
$line->insight()->messageEvent($requestId);
```

### `$line->insight()->messageEventAggregation($customAggregationUnit, $from, $to)`

สถิติต่อหน่วย aggregation ของ push / multicast

```php
$line->insight()->messageEventAggregation('promo_a', '20260901', '20260919');
```

### `$line->insight()->richmenuSummary($richMenuId, $from, $to)`

สถิติริชเมนูรวมในช่วงวันที่กำหนด

```php
$line->insight()->richmenuSummary($richMenuId, '20260901', '20260919');
```

### `$line->insight()->richmenuDaily($richMenuId, $from, $to)`

สถิติริชเมนูแยกตามวัน

```php
$line->insight()->richmenuDaily($richMenuId, '20260901', '20260919');
```

---

## หมวดแผนสมาชิก

`$line->membership()`

ฟีเจอร์ **Membership** ของบัญชีทางการ คือแผนที่ผู้ใช้สมัครจ่ายเงินกับเพจ ไม่ใช่รายชื่อสมาชิกในกลุ่มแชท

เมธอดในหมวดนี้:

```php
$line->membership();
$line->membership()->subscription($userId);
$line->membership()->getList();
$line->membership()->userIds($membershipId);
$line->membership()->userIds($membershipId, $next, 1000);
```

### `$line->membership()->subscription($userId)`

ดูว่าผู้ใช้อยู่แผนไหน และสถานะเป็นอย่างไร

```php
$line->membership()->subscription($userId);
```

### `$line->membership()->getList()`

ดึงรายการแผนที่บัญชีทางการเปิดไว้

```php
$line->membership()->getList();
```

### `$line->membership()->userIds($membershipId, $start = '', $limit = 0)`

ดึง user ID ของผู้ที่สมัครแผนนั้น `limit` สูงสุด 1000

```php
$line->membership()->userIds($membershipId);
$line->membership()->userIds($membershipId, $next, 1000);
```

---

## หมวดคูปอง

`$line->coupon()`

สร้าง ดู และปิดคูปอง

เมธอดในหมวดนี้:

```php
$line->coupon();
$line->coupon()->create($couponObject);
$line->coupon()->getList();
$line->coupon()->getList(['RUNNING']);
$line->coupon()->get($couponId);
$line->coupon()->close($couponId);
```

### `$line->coupon()->create($coupon)`

สร้างคูปองใหม่ `$coupon` เป็นอ็อบเจ็กต์ตามเอกสาร LINE

```php
$line->coupon()->create($couponObject);
```

### `$line->coupon()->getList($status = [], $start = '', $limit = 0)`

ดึงรายการคูปอง `$status` เช่น `DRAFT` `RUNNING` `CLOSED`

```php
$line->coupon()->getList();
$line->coupon()->getList(['RUNNING']);
```

### `$line->coupon()->get($couponId)`

ดูรายละเอียดคูปอง

```php
$line->coupon()->get($couponId);
```

### `$line->coupon()->close($couponId)`

ปิดคูปองไม่ให้ใช้ต่อ

```php
$line->coupon()->close($couponId);
```

---

## หมวดผูกบัญชี

`$line->accountLink()`

ออก token สำหรับผูกบัญชีบริการกับบัญชี LINE

เมธอดในหมวดนี้:

```php
$line->accountLink();
$line->accountLink()->token($userId);
```

### `$line->accountLink()->token($userId)`

ออก link token

```php
$line->accountLink()->token($userId);
```

---

## หมวด Channel Access Token

หมวดนี้**ไม่ใช่การล็อกอินผู้ใช้** (LINE Login คนละระบบ)

`$line->oauth()` และ `$line->oauth2()` ใช้โปรโตคอล OAuth 2.0 เพื่อ**ออก / ตรวจ / เพิกถอน Channel Access Token ของบอท** ให้แอปเรียก Messaging API ได้ โดยไม่ต้องคัดลอก token จาก Console ทุกครั้ง

เมธอดในหมวดนี้:

```php
$line->oauth();
$line->oauth()->accessToken();
$line->oauth()->verify($accessToken);
$line->oauth()->revoke($accessToken);

$line->oauth2();
$line->oauth2()->token($jwt);
$line->oauth2()->verify($accessToken);
$line->oauth2()->tokens($jwt);
$line->oauth2()->revoke($accessToken);
$line->oauth2()->tokenV3();
$line->oauth2()->tokenV3($jwt);
```

### `$line->oauth()->accessToken()`

ออก Channel Access Token อายุประมาณ 30 วัน (short-lived) ผ่าน `/v2/oauth`

```php
$line->oauth()->accessToken();
```

### `$line->oauth()->verify($accessToken)`

ตรวจว่า token อายุสั้นหรือยาวจาก Console ยังใช้ได้หรือไม่

```php
$line->oauth()->verify($accessToken);
```

### `$line->oauth()->revoke($accessToken)`

เพิกถอน token อายุสั้นหรือยาวจาก Console

```php
$line->oauth()->revoke($accessToken);
```

### `$line->oauth2()->token($jwt)`

ออก Channel Access Token รุ่น 2.1 ด้วย JWT

```php
$line->oauth2()->token($jwt);
```

### `$line->oauth2()->verify($accessToken)`

ตรวจว่า token รุ่น 2.1 ยังใช้ได้หรือไม่

```php
$line->oauth2()->verify($accessToken);
```

### `$line->oauth2()->tokens($jwt)`

ดึง key ID ของ token รุ่น 2.1 ที่ยังใช้ได้ทั้งหมด

```php
$line->oauth2()->tokens($jwt);
```

### `$line->oauth2()->revoke($accessToken)`

เพิกถอน token รุ่น 2.1

```php
$line->oauth2()->revoke($accessToken);
```

### `$line->oauth2()->tokenV3($jwt = '')`

ออก token แบบ stateless อายุ 15 นาที เพิกถอนไม่ได้ ไม่ส่ง JWT จะใช้ channel ID + secret จากตอนสร้าง `Line`

```php
$line->oauth2()->tokenV3();
$line->oauth2()->tokenV3($jwt);
```

---

Partner API (เช่น LINE Notification Message) ไม่ได้อยู่ในแพ็กเกจนี้

## ทดสอบกับช่องจริง

1. สร้าง Messaging API Channel ที่ [LINE Developers Console](https://developers.line.biz/)
2. ใส่ค่าใน environment หรือ `.env` ของแอป อย่า commit token
3. ตั้ง webhook เป็น HTTPS ที่เข้าถึงได้
4. ส่งข้อความจาก LINE แล้วเรียก `$line->message()->reply()` ด้วย `replyToken`
5. ลอง `$line->info()` และ `$line->users()->profile()` กับ user ID จาก webhook

## เอกสารอ้างอิง

- [Messaging API reference](https://developers.line.biz/en/reference/messaging-api/)
- [Channel access token](https://developers.line.biz/en/docs/basics/channel-access-token/)
- [Guzzle](https://docs.guzzlephp.org/en/stable/)

## License

MIT
