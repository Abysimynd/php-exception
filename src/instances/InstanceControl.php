<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Instances;

/**
 * Controla as instancias (chaves) onde os dados são armazenados.
 */
class InstanceControl {
    private ?string $instance = null;
    private ?string $tempInstance = null;

    public function setInstance( string $instance, bool $isTemp = false ): void {
        new InstanceValidator( $instance );

        if ( !$isTemp ) {
            $this->instance = $instance;

            return;
        }

        $this->tempInstance = $instance;
    }

    public function getInstance( bool $isTemp = false ): ?string {
        return !$isTemp ? $this->instance : $this->tempInstance;
    }

    public function getDefinedInstance(): ?string {
        return $this->tempInstance ?? $this->instance;
    }

    public function getValidInstance(): string {
        $instance = $this->getDefinedInstance();

        if ( null == $instance ) {
            throw new InstanceException(
                'Não há uma instancia definida.'
            );
        }

        return $instance;
    }

    public function clearTempInstance(): void {
        $this->tempInstance = null;
    }
}
