<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

use KeilielOliveira\Exception\Validators\InstanceValidator;

/**
 * Controla as operações das instancias.
 */
class InstanceControl {
    private ?string $instance = null;
    private ?string $tempInstance = null;

    public function setInstance( string $instance, bool $isTemp = false ): void {
        $validator = new InstanceValidator( $instance );
        $validator->validate();

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

        $validator = new InstanceValidator( $instance );
        $validator->validate();

        /** @var string $instance - O php não reconhece a validação acima */
        return $instance;
    }

    public function clearTempInstance(): void {
        $this->tempInstance = null;
    }
}
