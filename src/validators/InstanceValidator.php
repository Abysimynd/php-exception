<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Validators;

use KeilielOliveira\Exception\Exceptions\InstanceException;
use KeilielOliveira\Exception\Interfaces\ValidatorsInterface;

class InstanceValidator implements ValidatorsInterface {
    private ?string $instance;

    public function __construct( ?string $instance ) {
        $this->instance = $instance;
    }

    public function validate(): bool {
        $this->isValidInstance();
        $this->hasValidSyntax();

        return true;
    }

    private function isValidInstance(): void {
        if ( null === $this->instance ) {
            throw new InstanceException( 'Null não é uma instancia valida.' );
        }
    }

    private function hasValidSyntax(): void {
        $pattern = '/[\{\}\[\]\(\)]/';

        // @phpstan-ignore-next-line - Ignorando o erro pois o phpstan não reconhece a validação anterior.
        if ( preg_match( $pattern, $this->instance ) ) {
            throw new InstanceException(
                sprintf( 'A instancia %s não possui uma sintaxe valida.', $this->instance )
            );
        }
    }
}
