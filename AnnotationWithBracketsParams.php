<?php
namespace CodingLiki\AnnotationReader;

use ReflectionMethod;

class AnnotationWithBracketsParams
{
    public $annotationString;
    public $annotationName = [];

    /**
     * @var ExtendedReflectionClass
     */
    public $class;

    /**
     * @var ReflectionMethod
     */
    public $method;
    /**
     * @var BracketsParser
     */
    public $bracketsParamsParser;

    public function __construct(string $annotationString, ExtendedReflectionClass $class, ReflectionMethod $method)
    {
        $this->annotationString = $annotationString;
        $this->class = $class;
        $this->method = $method;
        $this->bracketsParamsParser = new BracketsParser($annotationString);

        preg_match('/\@(?<name>\w+) +/',$annotationString, $this->annotationName);
        $this->annotationName = $this->annotationName['name'] ?? "";
    }
}
