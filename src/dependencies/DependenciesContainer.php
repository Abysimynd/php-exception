<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Dependencies;

class DependenciesContainer {
    private array $dependencies = [];

    public function setDependecie( string $class, mixed ...$args ): void {
        new DependenciesValidator( $class, ...$args );

        $object = new DependenciesController( $class, $this, ...$args );
        $this->dependencies[$class] = $object->getDependencie();
    }

    public function getDependencie( string $class ): object {
        if ( !isset( $this->dependencies[$class] ) ) {
            throw new InvalidDependencieException(
                "A classe {$class} não foi definida como uma dependencia."
            );
        }

        return $this->dependencies[$class];
    }
}
