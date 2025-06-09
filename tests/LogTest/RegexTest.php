<?php

namespace Php\PhpWebLogin\LogTest;

use PHPUnit\Framework\TestCase;

class RegexTest extends TestCase {

    public function testRegex(): void {
        $path = "/products/12345/categories/abcde";//akan di cari dari sini

        $pattern = "#^/products/([0-9a-zA-Z]*)/categories/([0-9a-zA-Z]*)$#";//ini yang akan dicari

        $result = preg_match($pattern, $path, $variables);//regex. hasilnya akan di simpant di variables, jika ada makan akan true/1

        self::assertEquals(1, $result);
        var_dump($variables);

        array_shift($variables);//menghapus paling pertama
        var_dump($variables);
    }

}