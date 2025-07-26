<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

use KeilielOliveira\Exception\Validators\ContainerValidator;

class Container {
    private static self $container;

    /** @var array<class-string, object> */
    private array $instances = [];

    public function set( string $class ): void {
        $validator = new ContainerValidator( $class, $this->instances );
        $validator->validate();

        /** @var class-string $class */
        $object = new $class();
        $this->instances[$class] = $object;
        self::$container = $this;
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $class
     *
     * @return T
     */
    public function get( string $class ): object {
        $validator = new ContainerValidator( $class, $this->instances, true );
        $validator->validate();

        // @phpstan-ignore-next-line - Ignorando o erro porque o phpstan não identifica que o objeto é T.
        return $this->instances[$class];
    }

    public static function getContainer(): self {
        return self::$container ?? new Container();
    }
}
