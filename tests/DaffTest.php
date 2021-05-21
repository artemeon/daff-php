<?php

class DaffTest extends \PHPUnit\Framework\TestCase
{
    public function testDaff()
    {
        $this->assertTrue(class_exists(\coopy\Coopy::class));
    }
}

