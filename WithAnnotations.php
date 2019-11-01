<?php

namespace CodingLiki\AnnotationReader;

abstract class WithAnnotations
{
    public function __construct() {
        $annotationsReader = new AnnotationReader();
        $annotationsReader->parseClassAnnotations(static::class);
    }
}
