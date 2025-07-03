<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

class CoreValidator {
    private array $data;
    private ?string $method;

    public function __construct( array $data, ?string $method = null ) {
        $this->data = $data;
        $this->method = $method;
    }

    public function validateInstance( string $instance ): void {
        $validator = new InstanceValidator( $this->data, $instance );
        $validator->validate();
    }

    public function validateKey( int|string $key ): void {
        $validator = new KeyValidator( $this->data );
        $validator->validate( $key );
    }
}
