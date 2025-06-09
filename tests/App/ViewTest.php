<?php

namespace Php\PhpWebLogin\App;

use PHPUnit\Framework\TestCase;

class ViewTest extends TestCase {

    public function testRender(): void {
        View::render("Home/index", ["PHP Login Management"]);

        //cek output
        $this->expectOutputRegex('[PHP Login Management]');
        $this->expectOutputRegex('[html]');
        $this->expectOutputRegex('[body]');
        $this->expectOutputRegex('[Login]');
        $this->expectOutputRegex('[Register]');
        $this->expectOutputRegex('[Login Management]');
        $this->expectOutputRegex('[Piter Pangaribuan]');
    }

}
