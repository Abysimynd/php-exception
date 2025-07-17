<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Dependencies;

class DependenciesContainer {
    /** @var array<string, object> Armazena todos os objetos */
    private array $dependencies = [];

    /**
     * Inicia e armazena uma instancia da classe passada.
     *
     * @param mixed[] $args
     */
    public function setDependecie( string $class, mixed ...$args ): void {
        new DependenciesValidator( $class, ...$args );

        $object = new DependenciesController( $class, $this, ...$args );
        $this->dependencies[$class] = $object->getDependencie();
    }

    /**
     * Retorna a instancia da classe passada se existir.
     *
     * @throws InvalidDependencieException
     */
    public function getDependencie( string $class ): object {
        if ( !isset( $this->dependencies[$class] ) ) {
            throw new InvalidDependencieException(
                "A classe {$class} não foi definida como uma dependencia."
            );
        }

        return $this->dependencies[$class];
    }
}
