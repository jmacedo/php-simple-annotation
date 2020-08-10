<?php

namespace String;

/**
 * String sanitizer class.
 *
 * @package String
 */
final class Sanitizer
{
    /**
     * Remove the first and last character of a string.
     *
     * @param string $value
     * @return string
     */
    public static function removeFirstAndLastCharacters(string $value) : string
    {
        $valueLength = mb_strlen($value);

        return mb_substr($value, 1, $valueLength - 2);
    }

    /**
     * Remove the first character of a string.
     *
     * @param string $value
     * @return string
     */
    public static function removeFirstCharacter(string $value) : string
    {
        return mb_substr($value, 1);
    }

    /**
     * Remove the last character of a string.
     *
     * @param string $value
     * @return string
     */
    public static function removeLastCharacter(string $value) : string
    {
        return mb_substr($value, 0, -1);
    }
}
