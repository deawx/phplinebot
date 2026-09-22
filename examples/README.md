# ตัวอย่างการใช้งาน

ตั้งค่า Channel ก่อนรัน (อย่างน้อยสามตัวแรก):

```env
LINE_CHANNEL_ID=
LINE_CHANNEL_SECRET=
LINE_CHANNEL_ACCESS_TOKEN=
LINE_WEBHOOK_URL=https://example.com/webhook-handler.php
LINE_USER_ID=
LINE_GROUP_ID=
LINE_ROOM_ID=
LINE_MESSAGE_ID=
LINE_DATE=20260919
```

รันจากโฟลเดอร์แพ็กเกจหลัง `composer install` หรือจากโปรเจกต์ที่ลง `deawx/linebot` แล้ว

```bash
php examples/info.php
```

คำสั่งที่ส่งหาทุกคน / ออกจากกลุ่ม / เพิกถอน token จะไม่ทำงานจนกว่าจะตั้งธงเอง เช่น `LINE_ALLOW_BROADCAST=1`

| ไฟล์ | เรื่อง |
|---|---|
| `webhook.php` | ตั้ง URL webhook ดูค่า ทดสอบ |
| `webhook-handler.php` | รับข้อความจาก LINE แล้ว reply กลับ |
| `info.php` | ข้อมูลบัญชีทางการ |
| `users.php` | โปรไฟล์และรายชื่อเพื่อน |
| `message.php` | โควตา ตรวจข้อความ push |
| `content.php` | ดาวน์โหลดรูป/วิดีโอจาก messageId |
| `group.php` | กลุ่ม |
| `room.php` | ห้องแชทหลายคน |
| `richmenu.php` | ริชเมนู |
| `audience.php` | กลุ่มผู้รับ narrowcast |
| `insight.php` | สถิติ |
| `membership.php` | แผนสมาชิก |
| `coupon.php` | คูปอง |
| `account-link.php` | ผูกบัญชี |
| `oauth.php` | Channel Access Token อายุสั้น |
| `oauth2.php` | Channel Access Token v2.1 / v3 |
