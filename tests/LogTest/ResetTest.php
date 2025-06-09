<?php

namespace Php\PhpWebLogin\LogTest;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\GitProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use PHPUnit\Framework\TestCase;

class ResetTest extends TestCase {

    public function testProcessor(): void {
        $logger = new Logger(ResetTest::class);

        $logger->pushHandler(new StreamHandler("php://stderr"));

        //processor akan selalu di panggil setiap log
        $logger->pushProcessor(new GitProcessor());//menambahkan processor git
        $logger->pushProcessor(new MemoryUsageProcessor());//menambahkan memory usage

        for ($i = 0; $i < 10000; $i++) {
            $logger->info("Perulangan ke-$i");
            if ($i % 100 == 0){//setiap perulangan ke 100 akan reset processor dan handler
                $logger->reset();
            }
        }

        self::assertNotNull($logger);
    }
}