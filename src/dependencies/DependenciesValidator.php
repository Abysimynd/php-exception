<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Dependencies;

class DependenciesValidator {
    /** @var string Classe sendo validada */
    private string $dependencie;

    /** @var array<mixed> Parametros que serão passados para a dependencia */
    private array $args;

    /**
     * Valida a classe passada com seus argumentos.
     *
     * @param mixed[] $args
     */
    public function __construct( string $dependencie, mixed ...$args ) {
        $this->dependencie = $dependencie;
        $this->args = $args;
        $this->validateDependencie();
    }

    /**
     * Valida a classe.
     */
    private function validateDependencie(): void {
        $class = $this->classExists();
        $reflectionClass = new \ReflectionClass( $class );

        if ( $this->hasConstructor( $reflectionClass ) ) {
            if ( !$this->requiresArgs( $reflectionClass ) ) {
                return;
            }

            $this->hasArgs( $reflectionClass );
        }
    }

    /**
     * Se a classe existir.
     *
     * @return class-string
     *
     * @throws InvalidDependencieException
     */
    private function classExists(): string {
        if ( !class_exists( $this->dependencie ) ) {
            throw new InvalidDependencieException(
                sprintf( 'Não foi possível encontrar a classe %s.', $this->dependencie )
            );
        }

        return $this->dependencie;
    }

    /**
     * Se a classe posuir um construtor.
     *
     * @param \ReflectionClass<object> $reflectionClass
     */
    private function hasConstructor( \ReflectionClass $reflectionClass ): bool {
        if ( !$reflectionClass->hasMethod( '__construct' ) ) {
            return false;
        }

        return true;
    }

    /**
     * Se o construtor da classe requerer parametros.
     *
     * @param \ReflectionClass<object> $reflectionClass
     */
    private function requiresArgs( \ReflectionClass $reflectionClass ): bool {
        $constructor = $reflectionClass->getMethod( '__construct' );

        if ( 0 == $constructor->getNumberOfRequiredParameters() ) {
            return false;
        }

        return true;
    }

    /**
     * Verifica se foram passados argumentos os suficiente para iniciar a classe.
     *
     * @param \ReflectionClass<object> $reflectionClass
     *
     * @throws InvalidDependencieException
     */
    private function hasArgs( \ReflectionClass $reflectionClass ): void {
        $constructor = $reflectionClass->getMethod( '__construct' );
        $requiredArgs = $constructor->getNumberOfRequiredParameters();
        $args = count( $this->args );

        // Valida se tem argumentos o suficiente para os parametros requeridos.
        if ( $requiredArgs > $args ) {
            throw new InvalidDependencieException(
                sprintf(
                    'A dependencia %s requer um minimo de %s, mas somente %s foram passados.',
                    $this->dependencie,
                    $requiredArgs,
                    $args
                )
            );
        }

        // Valida se não foram passados parametros a mais.
        $allArgs = $constructor->getNumberOfParameters();

        if ( $args > $allArgs ) {
            throw new InvalidDependencieException(
                sprintf(
                    'A dependencia %s recebe um maximo de %s, mas %s foram passados.',
                    $this->dependencie,
                    $allArgs,
                    $args
                )
            );
        }
    }
}
