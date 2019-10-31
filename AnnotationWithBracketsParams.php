<?php
namespace CodingLiki\AnnotationReader;

class AnnotationWithBracketsParams
{
    public $annotationString;
    public $annotationName = [];

    /**
     * @var ExtendedReflectionClass
     */
    public $class;

    /**
     * @var BracketsParser
     */
    public $bracketsParamsParser;

    public function __construct(string $annotationString, ExtendedReflectionClass $class )
    {
        $this->annotationString = $annotationString;
        $this->class = $class;
        $this->bracketsParamsParser = new BracketsParser($annotationString);

        preg_match('/\@(?<name>\w+) +/',$annotationString, $this->annotationName);
        $this->annotationName = $this->annotationName['name'] ?? "";
    }
}
