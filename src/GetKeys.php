<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

use KeilielOliveira\Exception\Validators\KeysValidator;

/**
 * Recupera a chave principal e seus índices se houverem.
 */
class GetKeys {
    private string $key;

    public function __construct( string $key ) {
        $this->key = $key;
    }

    /**
     * @return array<string>
     */
    public function get(): array {
        $separators = $this->getSeparators();

        $keys = [$this->key];

        foreach ( $separators as $key => $separator ) {
            $keys = $this->splitKey( $keys, $separator );
        }

        $validator = new KeysValidator( $keys );
        $validator->validate();

        return $keys;
    }

    /**
     * @return array<non-empty-string>
     */
    private function getSeparators(): array {
        return new Config()->get( 'array_index_separator' );
    }

    /**
     * @param array<string>    $keys
     * @param non-empty-string $separator
     *
     * @return array<string>
     */
    private function splitKey( array $keys, string $separator ): array {
        $newKeys = [];

        foreach ( $keys as $i => $key ) {
            $newKeys = array_merge( $newKeys, explode( $separator, $key ) );
        }

        $callback = function ( string $key ): bool {
            return !empty( $key );
        };

        return array_filter( $newKeys, $callback );
    }
}
