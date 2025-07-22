<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Instances;

class InstanceValidator {
    private string $instance;

    public function __construct( string $instance ) {
        $this->instance = $instance;
        $this->validateInstance();
    }

    private function validateInstance(): void {
        $this->isValidInstanceSyntax();
        $this->isReservedInstance();
    }

    private function isValidInstanceSyntax(): void {
        $pattern = '/[\{\}\(\)\[\]]/';

        if ( preg_match( $pattern, $this->instance ) ) {
            throw new InstanceException(
                sprintf(
                    'A instancia %s não possui uma sintaxe valida.',
                    $this->instance
                )
            );
        }
    }

    private function isReservedInstance(): void {
        if ( 'MainException' == $this->instance ) {
            throw new InstanceException(
                "A instancia {$this->instance} é reservada para o sistema."
            );
        }
    }
}
