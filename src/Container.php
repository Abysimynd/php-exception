<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

use KeilielOliveira\Exception\Exceptions\CoreException;

/**
 * Contêiner de dependências.
 */
class Container {
    private static Container $container;

    /** @var array<class-string, object> Armazena as instâncias das dependências */
    private array $dependencies = [];

    private function __construct( ?Container $container = null ) {
        if(null != $container) {
            self::$container = $container;
        }
    }

    /**
     * Defini e armazena o objeto da classe recebida.
     *
     * @throws CoreException
     */
    public function set( string $class ): void {
        if ( !class_exists( $class ) ) {
            throw new CoreException(
                "A classe {$class} não existe."
            );
        }
        $this->dependencies[$class] = new $class();
        new Container( $this );
    }

    /**
     * Retorna o objeto da classe recebida se a mesma existir.
     *
 * @template T of object
 * @param class-string<T> $class
 * @return T
     */
    public function get( string $class ): mixed {
        if ( !isset( $this->dependencies[$class] ) ) {
            throw new CoreException(
                "A classe {$class} não foi registrada."
            );
        }

        /** @var T $object */
        $object = $this->dependencies[$class];

        return $object;
    }

    /**
     * Retorna a instancia do container atual.
     */
    public static function getContainer(): self {
        return self::$container ?? new self();
    }
}
