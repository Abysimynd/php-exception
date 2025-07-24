<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Data;

use KeilielOliveira\Exception\Config\Config;
use KeilielOliveira\Exception\Container;
use KeilielOliveira\Exception\Exceptions\DataException;

class KeysValidator {
    /** @var array<string> */
    private array $keys;

    /**
     * @param array<string> $keys
     */
    public function __construct( array $keys ) {
        $this->keys = $keys;
        $this->validateKeys();
    }

    private function validateKeys(): void {
        $this->hasReachedIndexLimit();
        $this->isReservedKey();
    }

    private function hasReachedIndexLimit(): void {
        $config = Container::getContainer()->get( Config::class );
        $maxIndex = $config->getConfig( 'max_array_index' );

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
        $config = Container::getContainer()->get( Config::class );
        $reservedKeys = $config->getConfig( 'reserved_keys' );
        $key = implode( '->', $this->keys );

        if ( isset( $reservedKeys[$key] ) ) {
            throw new DataException(
                "A chave {$key} é reservada para o uso do sistema."
            );
        }
    }
}
