# PHP Simple Annotation

[![Latest Stable Version](https://poser.pugx.org/jmacedo/php-simple-annotation/v)](//packagist.org/packages/jmacedo/php-simple-annotation) [![Total Downloads](https://poser.pugx.org/jmacedo/php-simple-annotation/downloads)](//packagist.org/packages/jmacedo/php-simple-annotation) [![Latest Unstable Version](https://poser.pugx.org/jmacedo/php-simple-annotation/v/unstable)](//packagist.org/packages/jmacedo/php-simple-annotation) [![License](https://poser.pugx.org/jmacedo/php-simple-annotation/license)](//packagist.org/packages/jmacedo/php-simple-annotation) [![Build Status](https://travis-ci.com/jmacedo/php-simple-annotation.svg?branch=master)](https://travis-ci.com/jmacedo/php-simple-annotation)

Yet another PHP annotation implementation. A simple and direct implementation without any complexity.

## Requirements

- PHP >= 7.4.*
- ext-json 
- ext-mbstring

## Installation

```bash
composer require jmacedo/php-simple-annotation
```

## Usage

Consider the following class for the purpose  of this documentation:

```php
namespace Examples;

/**
 * Class MyAnnotatedClass
 *
 * @package Examples
 * @metadata {"PI": 3.14, "name": "The name", "genre": "The genre", "age": "The age"}
 * @PI 3.14
 * @list ['item 1', 'item 2', 'item 3']
 * @uselessClass true
 */
class MyAnnotatedClass
{
    /**
     * @value 3.14
     * @var double
     * @description Math constant
     */
    const PI = 3.14;

    /** @var string */
    public $name;

    /** @var string */
    protected $genre;

    /** @var int */
    private $age;

    /**
     * MyAnnotatedClass constructor.
     *
     * @param string $name
     * @param string $genre
     * @param int $age
     */
    public function __construct(string $name = '', string $genre = '', int $age = 0)
    {
        $this->name = $name;
        $this->genre = $genre;
        $this->age = $age;
    }

    /**
     * A method that says "Hello!".
     *
     * @methodName sayHello
     * @emptyAnnotation
     */
    public function sayHello()
    {
        echo 'Hello!';
    }
}
```

You can instantiate the Annotation class in two ways:

- First:

```php
use SimpleAnnotation\Annotation;
use Examples\MyAnnotatedClass;

$annotation = new Annotation(MyAnnotatedClass::class);
// Or
$annotation = new Annotation('Examples\MyAnnotatedClass');
```

- Second:

```php
use SimpleAnnotation\Annotation;
use Examples\MyAnnotatedClass;

$myAnnotatedClass = new MyAnnotatedClass();
$annotation = new Annotation($myAnnotatedClass);
```

To get class annotations:

```php
$classAnnotations = $annotation->getClassAnnotations();

var_dump($classAnnotations);
```

```
object(SimpleAnnotation\AnnotationParsed)[6]
  private 'properties' => 
    array (size=6)
      'package' => string 'Examples' (length=8)
      'metadata' => 
        object(stdClass)[5]
          public 'PI' => float 3.14
          public 'name' => string 'The name' (length=8)
          public 'genre' => string 'The genre' (length=9)
          public 'age' => string 'The age' (length=7)
      'PI' => float 3.14
      'list' => 
        array (size=3)
          0 => string 'item 1' (length=6)
          1 => string 'item 2' (length=6)
          2 => string 'item 3' (length=6)
      'uselessClass' => boolean true
      'anotherStringValue' => string 'my string' (length=9)
```

The ```AnnotationParsed``` class implements ```__get``` and ```__set``` magic methods, so to access the annotations keys, just access the properties:

```php
echo "PI value is:" . $classAnnotations->PI;
echo "PI is also available in: " . $classAnnotations->metadata->PI;
```

To get methods annotations:

```php
$methodsAnnotations = $annotation->getMethodsAnnotations();

var_dump($methodsAnnotations);
```

```
array (size=2)
  '__construct' => 
    object(SimpleAnnotation\AnnotationParsed)[9]
      private 'properties' => 
        array (size=1)
          'param' => 
            array (size=3)
              0 => string 'string $name' (length=12)
              1 => string 'string $genre' (length=13)
              2 => string 'int $age' (length=8)
  'sayHello' => 
    object(SimpleAnnotation\AnnotationParsed)[10]
      private 'properties' => 
        array (size=2)
          'methodName' => string 'sayHello' (length=8)
          'emptyAnnotation' => boolean true
```

For a specific method:

```php
$sayHelloAnnotations = $annotation->getMethodAnnotations('sayHello');

var_dump($sayHelloAnnotations);
```

```
object(SimpleAnnotation\AnnotationParsed)[11]
  private 'properties' => 
    array (size=2)
      'methodName' => string 'sayHello' (length=8)
      'emptyAnnotation' => boolean true
```

To get properties annotations:

```php
$propertiesAnnotations = $annotation->getPropertiesAnnotations();

var_dump($propertiesAnnotations);
```

```
array (size=3)
  'name' => 
    object(SimpleAnnotation\AnnotationParsed)[12]
      private 'properties' => 
        array (size=1)
          'var' => string 'string' (length=6)
  'genre' => 
    object(SimpleAnnotation\AnnotationParsed)[13]
      private 'properties' => 
        array (size=1)
          'var' => string 'string' (length=6)
  'age' => 
    object(SimpleAnnotation\AnnotationParsed)[14]
      private 'properties' => 
        array (size=1)
          'var' => string 'int' (length=3)
```

For a specific property:

```php
$nameAnnotations = $annotation->getPropertyAnnotations('name');

var_dump($nameAnnotations);
```

```
object(SimpleAnnotation\AnnotationParsed)[7]
  private 'properties' => 
    array (size=1)
      'var' => string 'string' (length=6)
```

## And that's it

Just instantiate the ```SimpleAnnotation\Annotation``` class, call the methods and use the annotations values of the class you want to.

## Cache

By default, there is no cache in use, but there is a cache handler implemented that can be used, if you want.

This cache handler uses a file approach and had good results in some simple benchmarks I have made:

```php
$annotation = new Annotation(MyAnnotatedClass::class);
$start = microtime(true);
for ($i = 0; $i < $numberOfTimes; $i++) {
    $classAnnotation = $annotation->getClassAnnotations();
    $methodsAnnotations = $annotation->getMethodsAnnotations();
    $propertiesAnnotations = $annotation->getPropertiesAnnotations();
}
$end = microtime(true);
echo 'Time elapsed without cache: ' . ($end - $start) . "\n";

$cache = new SimpleAnnotation\Concerns\Cache\FileCache(__DIR__ . DIRECTORY_SEPARATOR . 'test.txt');
$annotation2 = new Annotation(MyAnnotatedClass::class, $cache);
$start2 = microtime(true);
for ($i = 0; $i < $numberOfTimes; $i++) {
    $classAnnotation = $annotation2->getClassAnnotations();
    $methodsAnnotations = $annotation2->getMethodsAnnotations();
    $propertiesAnnotations = $annotation2->getPropertiesAnnotations();
}
$end2 = microtime(true);
echo 'Time elapsed with cache: ' . ($end2 - $start2) . "\n";
```

For ```$numberOfTimes=100```:

```bash
Time elapsed without cache: 0.011738777160645
Time elapsed with cache: 0.00053501129150391
```

For ```$numberOfTimes=10000```:

```bash
Time elapsed without cache: 1.1012320518494
Time elapsed with cache: 0.055597066879272
```

For ```$numberOfTimes=100000```:

```bash
Time elapsed without cache: 11.216305971146
Time elapsed with cache: 0.53948402404785
```

The concrete implementation of ```\SimpleAnnotation\Concerns\ParsedAnnotation``` must implement the interface ```\JsonSerializable``` to the ```SimpleAnnotation\Concerns\Cache\FileCache``` implementation work well.

If you use the default implementation of this library, you don't have to worry about it.