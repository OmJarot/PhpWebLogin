<?php

namespace Php\PhpWebLogin\Middleware;

interface Middleware {
//middleware seperti filter, yang akan di jalankan sebelum
    function before(): void;
    
}