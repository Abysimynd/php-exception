<?php

declare(strict_types=1);
use Dependencies\TestsContent\Class\ClassOne;
use Dependencies\TestsContent\Class\ClassThree;
use Dependencies\TestsContent\Class\ClassTwo;
use KeilielOliveira\Exception\Dependencies\DependenciesContainer;
use PHPUnit\Framework\TestCase;

final class DependenciesContainerTest extends TestCase {

    public function testIsDefiningDependencie(): void {
        $class = ClassOne::class;
        $container = new DependenciesContainer;
        $container->setDependecie($class);

        $reflectionClass = new ReflectionClass($container);
        $property = $reflectionClass->getProperty('dependencies');
        $property->setAccessible(true);

        $expected = [$class => new $class];
        $response = $property->getValue($container);
        $this->assertEquals($expected, $response);
    }

    public function testIsReturningObject(): void {
        $class = ClassOne::class;
        $container = new DependenciesContainer;
        $container->setDependecie($class);

        $expected = new $class;
        $response = $container->getDependencie($class);
        $this->assertEquals($expected, $response);
    }

    public function testIsInjectingDependencie(): void {
        $class = ClassTwo::class;
        $container = new DependenciesContainer;
        $container->setDependecie($class);

        $expected = $container;
        $response = $container->getDependencie($class)->container;
        $this->assertEquals($expected, $response);
    }

    public function testIsInjectingParameters(): void {
        $expected = 'Abacate';
        $class = ClassThree::class;
        $container = new DependenciesContainer;
        $container->setDependecie($class, $expected, 10);

        $response = $container->getDependencie($class)->str;
        $this->assertEquals($expected, $response);
    }
}
