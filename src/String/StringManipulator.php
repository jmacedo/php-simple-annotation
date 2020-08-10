<?php

namespace String;

/**
 * A class to manipulate strings.
 *
 * @package String
 */
final class StringManipulator
{
    /**
     * Retrieves the last letter of a string.
     *
     * @param string $value
     * @return string
     */
    public static function getLastLetter(string $value) : string
    {
        return $value[mb_strlen($value) - 1];
    }
}
