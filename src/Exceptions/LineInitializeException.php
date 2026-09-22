<?php

namespace Cyberthai\Linebot\Exceptions;

/*
 * โยนเมื่อสร้าง LineProvider แต่ channelId, clientSecret หรือ token ว่าง
 */
class LineInitializeException extends \Exception
{
}
