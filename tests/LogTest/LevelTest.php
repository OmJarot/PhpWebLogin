<?php

namespace Php\PhpWebLogin\LogTest;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

class LevelTest extends TestCase {

    public function testLevel(): void {
        $logger = new Logger(LevelTest::class);

        $logger->pushHandler(new StreamHandler("php://stderr"));
        $logger->pushHandler(new StreamHandler(__DIR__ . "/../error.log", Level::Warning));//hanya mengirim log warning ke atas

        $logger->debug("Level debug");
        $logger->info("Level info");
        $logger->notice("Level notice");
        $logger->warning("Level warning");
        $logger->error("Level error");
        $logger->critical("Level critical");
        $logger->emergency("Level emergency");

        self::assertNotNull($logger);
    }

}