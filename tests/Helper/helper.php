<?php
namespace Php\PhpWebLogin\App{
    function header(string $value): void {//agar bisa lihat redirect
        echo $value;
    }
}
namespace Php\PhpWebLogin\Service{

    function setcookie(string $name, string $value, int $time , string $path): void {
        echo "$name: $value";
    }
}