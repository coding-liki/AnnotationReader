<?php
namespace CodingLiki\AnnotationReader;

use ReflectionMethod;

abstract class AbstractAnnotation
{
    /**
     * @var BracketsParser
     */
    public $bracketsParser;
    
    /**
     * @var ExtendedReflectionClass
     */
    public $annotationClass;
    
    /**
     * @var ReflectionMethod
     */
    public $annotationMethod;

    public function __construct(AnnotationWithBracketsParams $annotation)
    {
        $this->bracketsParser = $annotation->bracketsParamsParser;
        $this->annotationClass = $annotation->class;
        $this->annotationMethod = $annotation->method;
        $this->process();
    }


    public abstract function process();
}
