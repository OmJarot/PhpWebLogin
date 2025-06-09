<?php

namespace Php\PhpWebLogin\LogTest;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;

class HandlerTest extends TestCase {

    public function testHandler(): void {
        $logger = new Logger(HandlerTest::class);

        $logger->pushHandler(new StreamHandler("php://stderr"));//mengirim log ke console warnanya merah
//        $logger->pushHandler(new StreamHandler("php://stdout"));//mengirim log ke console
        $logger->pushHandler(new StreamHandler(__DIR__ . "/../application.log"));//mengirim log ke file
//        $logger->pushHandler(new SlackHandler());
//        $logger->pushHandler(new ElasticaHandler());

        self::assertNotNull($logger);

        assertEquals(2, sizeof($logger->getHandlers()));
    }


}