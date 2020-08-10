<?php

namespace Tests\TestSources;

/**
 * Class AnnotatedClass
 * @package Tests\TestSources
 * @thisClassIsAnnotated
 * @invalidClass false
 * @anotherValue null
 */
class AnnotatedClass
{
    /** @var string */
    public $propertyOne;

    /** @var bool */
    public $propertyTwo;

    /**
     * @param int $paramOne
     * @param float $paramTwo
     * @param bool $paramThree
     */
    public function methodOne(int $paramOne, float $paramTwo, bool $paramThree)
    {
        // Nothing
    }

    /**
     * @return string
     */
    public function methodTwo()
    {
        return 'Hello world.';
    }
}
