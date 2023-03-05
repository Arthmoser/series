<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class ExempleTest extends TestCase
{
    public function testSomething(): void
    {
        $this->assertTrue(true);
        $this->assertTrue(2 == 2, '2 n\'est pas égale à 3');
    }
}
