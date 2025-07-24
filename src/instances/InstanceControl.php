<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Instances;

use KeilielOliveira\Exception\Exceptions\InstanceException;

/**
 * Controla as instancias (chaves) onde os dados são armazenados.
 */
class InstanceControl {
    private ?string $instance = null;
    private ?string $tempInstance = null;

    public function set( string $instance, bool $isTemp = false ): void {
        new InstanceValidator( $instance );

        if ( !$isTemp ) {
            $this->instance = $instance;

            return;
        }

        $this->tempInstance = $instance;
    }

    public function get( bool $isTemp = false ): ?string {
        return !$isTemp ? $this->instance : $this->tempInstance;
    }

    public function getDefined(): ?string {
        return $this->tempInstance ?? $this->instance;
    }

    public function getValid(): string {
        $instance = $this->getDefined();

        if ( null == $instance ) {
            throw new InstanceException(
                'Não há uma instancia definida.'
            );
        }

        return $instance;
    }

    public function clearTemp(): void {
        $this->tempInstance = null;
    }
}
