<?php

namespace SimpleAnnotation;

use SimpleAnnotation\Concerns\ParserInterface;
use SimpleAnnotation\Concerns\ParsedAnnotationInterface;
use String\Sanitizer;
use String\StringManipulator;

/**
 * The class responsible for parsing the annotations.
 *
 * @package SimpleAnnotation
 */
final class AnnotationParser implements ParserInterface
{
    /**
     * The regexp pattern to split the tags from values in annotations.
     * @var string
     */
    private string $tagsPattern = '/@([a-z]+[a-z0-9_]*)(.*)\s{1,}/i';

    /**
     * The regexp pattern to identify numbers, integer or double.
     * @var string
     */
    private string $numberPattern = '/^[-]?[0-9]+([\.][0-9]+)?$/';

    /** @var ParsedAnnotationInterface */
    private ParsedAnnotationInterface $annotationParsed;

    /**
     * AnnotationParser constructor.
     */
    public function __construct()
    {
        $this->initializeAnnotationParsed();
    }

    /**
     * Initializes the AnnotationParsed object.
     */
    private function initializeAnnotationParsed()
    {
        $this->annotationParsed = new AnnotationParsed();
    }

    /**
     * The method that coordinates the annotation parsing.
     *
     * @param string $docBlock
     * @return ParsedAnnotationInterface
     */
    public function parse(string $docBlock): ParsedAnnotationInterface
    {
        // Have to initialize every parse to clean the previous parsed values, to not "add" values that don't exist.
        $this->initializeAnnotationParsed();

        // Separate the annotations tags (position 1) and it's values (position 2)
        $matches = [];
        preg_match_all($this->tagsPattern, $docBlock, $matches);

        // Iterate then and parse each one
        foreach ($matches[1] as $key => $value) {
            $this->annotationParsed->{$value} = $this->parseAnnotationValue(trim($matches[2][$key]));
        }

        return $this->annotationParsed;
    }

    /**
     * The method that effectively parse the annotation values.
     *
     * @param string $value
     * @return bool|string|double|array|object|null
     */
    private function parseAnnotationValue(string $value)
    {
        // The annotation just exists (true value)
        if (empty($value)) {
            return true;
        }

        $firstLetter = $value[0];
        $lastLetter = StringManipulator::getLastLetter($value);

        if ($firstLetter === '[' && $lastLetter === ']') { // ARRAY
            $value = $this->parseArray($value);
        } elseif ($firstLetter === '{' && $lastLetter === '}') { // JSON / OBJECT
            $value = $this->parseJson($value);
        } else {
            if (preg_match($this->numberPattern, $value)) { // NUMERIC
                $value = $this->parseNumeric($value);
            } else { // BOOL, NULL and STRING
                $value = $this->parseBoolNullAndString($value);
            }
        }

        return $value;
    }

    /**
     * Parse a string representation of an one dimensional array into an array.
     *
     * @param string $value
     * @return array
     */
    private function parseArray(string $value): array
    {
        // Mount the array
        $value = explode(',', Sanitizer::removeFirstAndLastCharacters($value));

        // Sanitize the array values
        $value = array_map(function($v) {
            $v = trim($v);

            if ($v[0] === '"' || $v[0] === "'") {
                $v = Sanitizer::removeFirstCharacter($v);
            }

            if (StringManipulator::getLastLetter($v) === '"' || StringManipulator::getLastLetter($v) === "'") {
                $v = Sanitizer::removeLastCharacter($v);
            }

            return $v;
        }, $value);

        return $value;
    }

    /**
     * Parse a json string into an object.
     *
     * @param string $value
     * @return object
     */
    private function parseJson(string $value)
    {
        return json_decode($value);
    }

    /**
     * Parse a numeric string into an int or a float number.
     *
     * @param string $value
     * @return int|float
     */
    private function parseNumeric(string $value)
    {
        return ($value + 0);
    }

    /**
     * Parse a string into a bool, null or a string without quotation marks.
     *
     * @param string $value
     * @return bool|string|null
     */
    private function parseBoolNullAndString(string $value)
    {
        switch (mb_strtolower($value)) {
            case 'true':
                $value = true;
                break;
            case 'false':
                $value = false;
                break;
            case 'null':
                $value = null;
                break;
            default: // STRING
                $value = $this->parseString($value);
                break;
        }

        return $value;
    }

    /**
     * Remove the quotation marks of a string.
     *
     * @param string $value
     * @return string
     */
    private function parseString(string $value): string
    {
        $firstLetter = $value[0];
        $lastLetter = StringManipulator::getLastLetter($value);

        // Remove explicit quotation marks for strings
        if ($firstLetter === "'" || $firstLetter === '"') {
            $value = Sanitizer::removeFirstCharacter($value);
        }

        if ($lastLetter === "'" || $lastLetter === '"') {
            $value = Sanitizer::removeLastCharacter($value);
        }

        return $value;
    }
}
