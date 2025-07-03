<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

class InstanceValidator {
    private array $data;
    private string $instance;

    public function __construct( array $data, string $instance ) {
        $this->data = $data;
        $this->instance = $instance;
    }

    public function validate(): void {
        $this->hasInstance();
    }

    private function hasInstance(): void {
        if ( !isset( $this->data[$this->instance] ) ) {
            // Instancia não existe.

            throw new \Exception(
                "Instancia '{$this->instance}' não existe."
            );
        }
    }
}
