<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Dependencies;

class DependenciesValidator {
    private string $dependecie;
    private array $args;

    public function __construct( string $dependecie, mixed ...$args ) {
        $this->dependecie = $dependecie;
        $this->args = $args;
        $this->validateDependencie();
    }

    private function validateDependencie(): void {
        $this->classExists();

        $reflectionClass = new \ReflectionClass( $this->dependecie );

        if ( $this->hasConstructor( $reflectionClass ) ) {
            if ( !$this->requiresArgs( $reflectionClass ) ) {
                return;
            }

            $this->hasArgs( $reflectionClass );
        }
    }

    private function classExists(): void {
        if ( !class_exists( $this->dependecie ) ) {
            throw new InvalidDependencieException(
                sprintf( 'Não foi possível encontrar a classe %s.', $this->dependecie )
            );
        }
    }

    private function hasConstructor( \ReflectionClass $reflectionClass ): bool {
        if ( !$reflectionClass->hasMethod( '__construct' ) ) {
            return false;
        }

        return true;
    }

    private function requiresArgs( \ReflectionClass $reflectionClass ): bool {
        $constructor = $reflectionClass->getMethod( '__construct' );

        if ( 0 == $constructor->getNumberOfRequiredParameters() ) {
            return false;
        }

        return true;
    }

    private function hasArgs( \ReflectionClass $reflectionClass ): void {
        $constructor = $reflectionClass->getMethod( '__construct' );
        $requiredArgs = $constructor->getNumberOfRequiredParameters();
        $args = count( $this->args );

        if ( $requiredArgs > $args ) {
            throw new InvalidDependencieException(
                sprintf(
                    'A dependencia %s requer um minimo de %s, mas somente %s foram passados.',
                    $this->dependecie,
                    $requiredArgs,
                    $args
                )
            );
        }

        $allArgs = $constructor->getNumberOfParameters();

        if ( $args > $allArgs ) {
            throw new InvalidDependencieException(
                sprintf(
                    'A dependencia %s recebe um maximo de %s, mas %s foram passados.',
                    $this->dependecie,
                    $allArgs,
                    $args
                )
            );
        }
    }
}
