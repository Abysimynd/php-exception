<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

class InstanceValidator {
    private array $data;
    private ?string $instance;

    public function __construct( array $data, ?string $instance ) {
        $this->data = $data;
        $this->instance = $instance;
    }

    public function validate(): void {
        $this->hasDefinedInstance();
        $this->hasInstance();
    }

    private function hasDefinedInstance(): void {
        if ( null == $this->instance ) {
            throw new \Exception(
                'Não há uma instancia definida.',
                ExceptionCodes::MISSING_INSTANCE->value
            );
        }
    }

    private function hasInstance(): void {
        if ( !isset( $this->data[$this->instance] ) ) {
            // Instancia não existe.

            throw new \Exception(
                "Instancia '{$this->instance}' não existe.",
                ExceptionCodes::INSTANCE_NOT_EXIST->value
            );
        }
    }
}
