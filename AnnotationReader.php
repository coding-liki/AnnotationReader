<?php
namespace CodingLiki\AnnotationReader;

use ReflectionMethod;

class AnnotationReader
{
    public function parseClassAnnotations($className)
    {
        
        $controllerReflection = new ExtendedReflectionClass($className);
        $methods_reflection = $controllerReflection->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach($methods_reflection as $method){
            $this->parseMethodDocBlock($method, $controllerReflection);
        }
    }

    public function parseMethodDocBlock(ReflectionMethod $method, ExtendedReflectionClass $class)
    {
        $docBlock = $method->getDocComment();
        if(empty($docBlock)){
            return;
        }
        $docBlock = $this->normalizeDocBlock($docBlock);
        $this->getAnnotationStringsWithBracketsParams($docBlock, $class, $method);
    }
    public function getAnnotationStringsWithBracketsParams(string $docBlock,ExtendedReflectionClass $class,ReflectionMethod $method)
    {
        $annotationStrings = explode("\n", trim($docBlock));
        $notEmpty = array_filter($annotationStrings, function($var){return !empty(trim($var));});

        $annotationObjects = array_map(function($string) use($class, $method){
            return new AnnotationWithBracketsParams($string, $class, $method);
        }, $notEmpty);

        foreach($annotationObjects as $annotation){
            $this->checkAnnotationUserClass($annotation);
        }
    }
    public function normalizeDocBlock(string $docBlock) : string 
    {
        $docBlock = preg_replace('/\n +\* */', " ", $docBlock);
        $docBlock = preg_replace('/\/\*\*(.*)\//', "$1", $docBlock);
        $docBlock = trim($docBlock);
        $docBlock = preg_replace('|\s+|', ' ', $docBlock );
        $docBlock = BracketsParser::encodeInQuotes($docBlock);
        $docBlock = preg_replace('/(\@\w+)/', "\n$1", $docBlock);
        $docBlock = str_replace(" = ", "=", $docBlock);

        return $docBlock;
    }

    public function findInUsesByName(string $className, array $uses) : ?string
    {
        foreach($uses as $use){
            if(strpos($use['class'], $className) !== false || strpos($use['as'], $className) !== false){
                return $use['class'];
            }
        }
        return null;
    }
    public function checkAnnotationUserClass(AnnotationWithBracketsParams $annotation)
    {
        $classUses = $annotation->class->getUseStatements();

        $classNameWithNamespace = $this->findInUsesByName($annotation->annotationName, $classUses);
        if($classNameWithNamespace != null){
            $newObj = new $classNameWithNamespace($annotation);
            if(!$newObj instanceof AbstractAnnotation){
                echo "not good class\n";
            }
        }
        // include_once($filePath);
        // $className = $annotation->annotationName;
        // $annotationuserClass = new $className();
    }

}
