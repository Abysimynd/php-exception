<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

use KeilielOliveira\Exception\Validators\DataPathValidator;

/**
 * Oferece os métodos para controle completo dos dados.
 */
class DataControl {
    /** @var array<mixed> */
    private array $data;

    /**
     * @param array<mixed> $data
     */
    public function __construct( array $data ) {
        $this->data = $data;
    }

    /**
     * @return array<mixed>
     */
    public function set( string $key, mixed $value ): array {
        $keys = new GetKeys( $key )->get();

        $prepare = new PrepareData();
        $data = $prepare->createArrayWithPath( $keys, $value );

        $this->data = array_merge_recursive( $this->data, $data );

        return $this->data;
    }

    /**
     * @return array<mixed>
     */
    public function update( string $key, mixed $value ): array {
        $keys = new GetKeys( $key )->get();

        $validator = new DataPathValidator( $keys, $this->data );
        $validator->validate();

        $prepare = new PrepareData();

        return $prepare->updatePathInArray( $keys, $this->data, $value );
    }

    /**
     * @return array<mixed>
     */
    public function remove( string $key ): array {
        $keys = new GetKeys( $key )->get();

        $validator = new DataPathValidator( $keys, $this->data );
        $validator->validate();

        $prepare = new PrepareData();

        return $prepare->removePathInArray( $keys, $this->data );
    }

    public function get( ?string $key ): mixed {
        if ( null == $key ) {
            return $this->data;
        }

        $keys = new GetKeys( $key )->get();

        $validator = new DataPathValidator( $keys, $this->data );
        $validator->validate();

        $prepare = new PrepareData();

        return $prepare->getPathValueInArray( $keys, $this->data );
    }
}
