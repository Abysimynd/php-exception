<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

class CoreValidator {
    /**
     * Dados usados na validação atual.
     *
     * @var array<int|string, mixed>
     */
    private array $data;

    /**
     * Summary of __construct.
     *
     * @param array<int|string, mixed> $data
     */
    public function __construct( array $data ) {
        $this->data = $data;
    }

    public function validateInstance( ?string $instance ): void {
        $validator = new InstanceValidator( $this->data, $instance ); // @phpstan-ignore argument.type
        $validator->validate();
    }

    public function validateKey( int|string $key ): void {
        $validator = new KeyValidator( $this->data );
        $validator->validate( $key );
    }
}
