<?php

namespace Php\PhpWebLogin\LogTest;

use Monolog\Logger;
use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase {

    public function testLog(): void {
        $logger = new Logger("Piter");

        self::assertNotNull($logger);
    }

    public function testLogName(): void {
        $logger = new Logger(LoggerTest::class);//biasanya orang membuat log menggunakan nama class
        self::assertNotNull($logger);
    }


}