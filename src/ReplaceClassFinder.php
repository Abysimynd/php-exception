<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

use KeilielOliveira\Exception\Interfaces\KeysReplaceInterface;

/**
 * Descobre e retorna qual classe deve ser usada para recuperar o valor de uma determinada chave no template
 * de mensagens.
 */
class ReplaceClassFinder {
    private string $key;

    public function __construct( string $key ) {
        $this->key = $key;
    }

    /**
     * @return class-string<KeysReplaceInterface>
     */
    public function getClass(): string {
        if ( $this->isReservedKey() ) {
            return ReservedKeysReplace::class;
        }

        return DataKeysReplace::class;
    }

    private function isReservedKey(): bool {
        $config = new Config()->get( 'reserved_keys' );
        $reservedKeys = array_keys( $config );

        return in_array( $this->key, $reservedKeys );
    }
}
