<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Data;

use KeilielOliveira\Exception\Config\Config;

class KeysValidator {
    private Config $config;

    /** @var array<string> */
    private array $keys;

    /**
     * @param array<string> $keys
     */
    public function __construct( Config $config, array $keys ) {
        $this->config = $config;
        $this->keys = $keys;
        $this->validateKeys();
    }

    private function validateKeys(): void {
        $this->hasReachedIndexLimit();
        $this->isReservedKey();
    }

    private function hasReachedIndexLimit(): void {
        $maxIndex = $this->config->getConfig( 'max_array_index' );

        if ( count( $this->keys ) - 1 > $maxIndex ) {
            throw new DataException(
                sprintf(
                    'A chave %s excedeu o máximo de indices permitidos: (max: %s, keys: %s).',
                    implode( '->', $this->keys ),
                    $maxIndex,
                    count( $this->keys ) - 1
                )
            );
        }
    }

    private function isReservedKey(): void {
        $reservedKeys = $this->config->getReservedConfig( 'reserved_keys' );
        $key = implode( '->', $this->keys );

        if ( in_array( $key, $reservedKeys ) ) {
            throw new DataException(
                "A chave {$key} é reservada para o uso do sistema."
            );
        }
    }
}
