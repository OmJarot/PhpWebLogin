<?php

namespace Php\PhpWebLogin\LogTest;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

class ContextTest extends TestCase {

    public function testContext(): void {
        $logger = new Logger(ContextTest::class);

        $logger->pushHandler(new StreamHandler("php://stderr"));

        //mengirim context
        $logger->info("This is log message", ["username" => "Piter"]);
        $logger->info("Try login user", ["username" => "Piter"]);
        $logger->info("Success login user", ["username" => "Piter"]);

        self::assertNotNull($logger);
    }
}