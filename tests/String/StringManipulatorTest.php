<?php

namespace Tests\String;

use PHPUnit\Framework\TestCase;
use String\StringManipulator;

class StringManipulatorTest extends TestCase
{
    public function testGetLastLetterWithSuccess()
    {
        $value = 'my value';
        $lastLetter = StringManipulator::getLastLetter($value);

        $this->assertEquals('e', $lastLetter);
    }
}