<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Validators;

use KeilielOliveira\Exception\Exceptions\ContainerException;
use KeilielOliveira\Exception\Interfaces\ValidatorsInterface;

class ContainerValidator implements ValidatorsInterface {
    private string $class;

    /** @var array<class-string, object> */
    private array $instances;

    private bool $get;

    /**
     * @param array<class-string, object> $instances
     */
    public function __construct( string $class, array $instances, bool $get = false ) {
        $this->class = $class;
        $this->instances = $instances;
        $this->get = $get;
    }

    public function validate(): bool {
        $this->classExists();

        if ( $this->get ) {
            $this->hasClassInContainer();
            $this->isClassObject();
        }

        return true;
    }

    private function classExists(): void {
        if ( !class_exists( $this->class ) ) {
            throw new ContainerException(
                sprintf( 'A classe %s não existe.', $this->class )
            );
        }
    }

    private function hasClassInContainer(): void {
        if ( !isset( $this->instances[$this->class] ) ) {
            throw new ContainerException(
                sprintf( 'A classe %s não foi registrada no container.', $this->class )
            );
        }
    }

    private function isClassObject(): void {
        $object = $this->instances[$this->class];

        if ( !$object instanceof $this->class ) {
            throw new ContainerException(
                sprintf( 'O objeto %s não é uma instancia da classe %s.', $object::class, $this->class )
            );
        }
    }
}
