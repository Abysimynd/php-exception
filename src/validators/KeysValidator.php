<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Validators;

use KeilielOliveira\Exception\Config;
use KeilielOliveira\Exception\Exceptions\InvalidKeyException;
use KeilielOliveira\Exception\Interfaces\ValidatorsInterface;

class KeysValidator implements ValidatorsInterface {
    /** @var array<string> */
    private array $keys;

    /**
     * @param array<string> $keys
     */
    public function __construct( array $keys ) {
        $this->keys = $keys;
    }

    public function validate(): bool {
        $this->hasReachedMaxArrayIndex();
        $this->isReservedKey();

        return true;
    }

    private function hasReachedMaxArrayIndex(): void {
        $maxIndex = new Config()->get( 'max_array_index' );
        $index = count( $this->keys );

        if ( $maxIndex < $index - 1 ) {
            throw new InvalidKeyException(
                sprintf(
                    'A chave %s ultrapassou o limite de indices em %s: (máximo: %s, chave: %s)',
                    implode( '.', $this->keys ),
                    ( $index - 1 ) - $maxIndex,
                    $maxIndex,
                    $index - 1
                )
            );
        }
    }

    private function isReservedKey(): void {
        if ( count( $this->keys ) > 1 ) {
            return;
        }

        $key = array_shift( $this->keys );

        /** @var array<string, string> */
        $config = new Config()->get( 'reserved_keys' );
        $reservedKeys = array_keys( $config );

        if ( in_array( $key, array_merge( $reservedKeys, ['*'] ) ) ) {
            throw new InvalidKeyException(
                sprintf( 'A chave %s é uma chave reservada do sistema.', $key )
            );
        }
    }
}
