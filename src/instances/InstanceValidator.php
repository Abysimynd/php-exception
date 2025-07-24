<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Instances;

use KeilielOliveira\Exception\Exceptions\InstanceException;

class InstanceValidator {
    private string $instance;

    public function __construct( string $instance ) {
        $this->instance = $instance;
        $this->validate();
    }

    private function validate(): void {
        $this->hasValidSyntax();
    }

    private function hasValidSyntax(): void {
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
}
