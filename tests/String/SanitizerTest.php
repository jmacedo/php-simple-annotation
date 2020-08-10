<?php

namespace Tests\String;

use PHPUnit\Framework\TestCase;
use String\Sanitizer;

class SanitizerTest extends TestCase
{
    public function testRemoveFirstAndLastCharacterWithSuccess()
    {
        $value = 'my value';
        $newValue = Sanitizer::removeFirstAndLastCharacters($value);

        $this->assertEquals('y valu', $newValue);
    }

    public function testRemoveFirstAndLastCharacterWithLessThen2Chars()
    {
        $value1 = '';
        $value2 = 'a';
        $value3 = 'ab';
        $newValue1 = Sanitizer::removeFirstAndLastCharacters($value1);
        $newValue2 = Sanitizer::removeFirstAndLastCharacters($value2);
        $newValue3 = Sanitizer::removeFirstAndLastCharacters($value3);

        $this->assertEquals('', $newValue1);
        $this->assertEquals('', $newValue2);
        $this->assertEquals('', $newValue3);
    }

    public function testRemoveFirstCharacterWithSuccess()
    {
        $value = 'my value';
        $newValue = Sanitizer::removeFirstCharacter($value);

        $this->assertEquals('y value', $newValue);
    }

    public function testRemoveFirstCharacterWithLessThen1Char()
    {
        $value1 = '';
        $value2 = 'a';
        $newValue1 = Sanitizer::removeFirstCharacter($value1);
        $newValue2 = Sanitizer::removeFirstCharacter($value2);

        $this->assertEquals('', $newValue1);
        $this->assertEquals('', $newValue2);
    }

    public function testRemoveLastCharacterWithSuccess()
    {
        $value = 'my value';
        $newValue = Sanitizer::removeLastCharacter($value);

        $this->assertEquals('my valu', $newValue);
    }

    public function testRemoveLastCharacterWithLessThen1Char()
    {
        $value1 = '';
        $value2 = 'a';
        $newValue1 = Sanitizer::removeLastCharacter($value1);
        $newValue2 = Sanitizer::removeLastCharacter($value2);

        $this->assertEquals('', $newValue1);
        $this->assertEquals('', $newValue2);
    }
}