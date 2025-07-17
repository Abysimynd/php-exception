<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Dependencies;

class DependenciesController {
    private DependenciesContainer $container;
    private object $dependencie;

    public function __construct( string $class, DependenciesContainer $container, mixed ...$args ) {
        $this->container = $container;
        $this->initDependencie( $class, ...$args );
    }

    public function getDependencie(): object {
        return $this->dependencie;
    }

    private function initDependencie( string $class, mixed ...$args ): void {
        $this->dependencie = $this->createObject( $class, ...$args );
        $this->injectContainer();
    }

    private function createObject( string $class, mixed ...$args ): object {
        if ( !empty( $args ) ) {
            return new $class( ...$args );
        }

        return new $class();
    }

    private function injectContainer(): void {
        $reflectionClass = new \ReflectionClass( $this->dependencie );
        $containerProperty = $this->requireContainer( $reflectionClass );

        if ( !$containerProperty ) {
            return;
        }

        $containerProperty = $reflectionClass->getProperty( $containerProperty );
        $containerProperty->setAccessible( true );
        $containerProperty->setValue( $this->dependencie, $this->container );
    }

    private function requireContainer( \ReflectionClass $reflectionClass ): false|string {
        $properties = $reflectionClass->getProperties();

        foreach ( $properties as $key => $property ) {
            /** @var \ReflectionProperty $property */
            $propertyType = $property->getType();

            if ( null != $propertyType ) {
                if ( $propertyType instanceof \ReflectionUnionType ) {
                    continue;
                }

                if ( DependenciesContainer::class == $propertyType->getName() ) {
                    return $property->getName();
                }
            }
        }

        return false;
    }
}
