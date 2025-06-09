<?php

namespace Php\PhpWebLogin\LogTest;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\GitProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use PHPUnit\Framework\TestCase;

class ProcessorTest extends TestCase {

    public function testProcessor(): void {
        $logger = new Logger(ProcessorTest::class);

        $logger->pushHandler(new StreamHandler("php://stderr"));

        //processor akan selalu di panggil setiap log
        $logger->pushProcessor(function ($record){//record berisikan atribute lognya
            $record["extra"]["username"] = "Piter";//tambahkan ke processor
            $record["extra"]["piter"] = [
                "app" => "Php logging",
                "author" => "Piter Pangaribuan"
            ];
//            var_dump($record);//record berisikan atribute lognya
            return $record;
        });

        $logger->pushProcessor(new GitProcessor());//menambahkan processor git
        $logger->pushProcessor(new MemoryUsageProcessor());//menambahkan memory usage

        $logger->info("Test Processor", ["name", "ini adalah context"]);
        $logger->info("Test Processor");

        self::assertNotNull($logger);
    }




}