<?php

namespace Php\PhpWebLogin\LogTest;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

class LoggingTest extends TestCase {

    public function testLogging(): void {
        $logger = new Logger(LoggingTest::class);

        $logger->pushHandler(new StreamHandler("php://stderr"));//mengirim ke console warnanya merah
//        $logger->pushHandler(new StreamHandler("php://stdout"));//mengirim ke console
        $logger->pushHandler(new StreamHandler(__DIR__ . "/../application.log"));//mengirim ke file

        $logger->info("Selamat belajar php log");//log

        self::assertNotNull($logger);
    }

}