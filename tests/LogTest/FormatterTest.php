<?php

namespace Php\PhpWebLogin\LogTest;

use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

class FormatterTest extends TestCase {

    public function testFormatter(): void {
        $logger = new Logger(FormatterTest::class);

        $handler = new StreamHandler("php://stderr");
        //untuk formatnya bisa menggunakan turunan FormatterInterface
        $handler->setFormatter(new JsonFormatter());//set menggunakan format json untuk lognya

        $logger->pushHandler($handler);

        $logger->info("Test formatter");
        $logger->info("Test formatter log");

        self::assertNotNull($logger);
    }


}