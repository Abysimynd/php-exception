<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Validators;

use KeilielOliveira\Exception\Exceptions\DataPathException;
use KeilielOliveira\Exception\Interfaces\ValidatorsInterface;

/**
 * Valida se o caminho passado existe dentro do array.
 */
class DataPathValidator implements ValidatorsInterface {
    /** @var array<string> */
    private array $keys;

    /** @var array<mixed> */
    private array $data;

    private bool $expectHas;
    private string $path = '';

    /**
     * @param array<string> $keys
     * @param array<mixed>  $data
     */
    public function __construct( array $keys, array $data, bool $expectHas = true ) {
        $this->keys = $keys;
        $this->data = $data;
        $this->expectHas = $expectHas;
    }

    public function validate(): bool {
        $this->hasPathInArray();

        return true;
    }

    private function hasPathInArray(): void {
        if ( $this->expectHas != $this->checkIfHasPathInArray() ) {
            throw new DataPathException(
                $this->expectHas
                ? sprintf( 'Não há o caminho %s nos dados.', $this->path . ' -> ' . implode( '-> ', $this->keys ) )
                : sprintf( 'O caminho %s já existe nos dados.', $this->path . ' -> ' . implode( '-> ', $this->keys ) )
            );
        }
    }

    private function checkIfHasPathInArray(): bool {
        $key = array_shift( $this->keys );
        $this->path .= '' == $this->path ? $key : ' -> ' . $key;

        if ( !isset( $this->data[$key] ) ) {
            return false;
        }

        if ( !empty( $this->keys ) ) {
            if ( !is_array( $this->data[$key] ) || empty( $this->data[$key] ) ) {
                return false;
            }

            $this->data = $this->data[$key];

            return $this->checkIfHasPathInArray();
        }

        return true;
    }
}
