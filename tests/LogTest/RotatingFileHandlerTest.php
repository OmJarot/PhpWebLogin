<?php

namespace Php\PhpWebLogin\LogTest;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

class RotatingFileHandlerTest extends TestCase {

    public function testRotating(): void {
        $logger = new Logger(RotatingFileHandlerTest::class);
        $logger->pushHandler(new StreamHandler("php://stderr"));
        $logger->pushHandler(new RotatingFileHandler(__DIR__ . "/../app.log", 10, Level::Info));// max file nya adalah 10, akan mengganti file setiap harinya

        $logger->info("Test Rotating File Handler1");
        $logger->info("Test Rotating File Handler2");
        $logger->info("Test Rotating File Handler3");

        self::assertNotNull($logger);
    }


}