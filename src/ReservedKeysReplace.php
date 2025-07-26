<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

use KeilielOliveira\Exception\Exceptions\MessageException;
use KeilielOliveira\Exception\Interfaces\KeysReplaceInterface;

/**
 * Recupera o valor das chaves reservadas do sistema dentro do template de mensagem.
 */
class ReservedKeysReplace implements KeysReplaceInterface {
    private string $key;
    private string $index;

    public function __construct( string $key, string $index ) {
        $this->key = $key;
        $this->index = $index;
    }

    public function getKeyValue(): string {
        $trace = $this->getTrace();
        $key = $this->getReservedKeyValue();

        return $this->getRequiredTrace( $trace, $key );
    }

    /**
     * @return array<array<string, mixed>>
     */
    private function getTrace(): array {
        $backTrace = debug_backtrace();
        $skipValidation = false;

        foreach ( $backTrace as $key => $trace ) {
            if ( !isset( $trace['class'] ) || Core::class != $trace['class'] ) {
                if ( $skipValidation ) {
                    $backTrace[$key] = $trace;

                    continue;
                }

                unset( $backTrace[$key] );

                continue;
            }

            $skipValidation = true;
            $backTrace[$key] = $trace;
        }

        $backTrace = empty( $backTrace ) ? debug_backtrace() : $backTrace;

        return array_values( $backTrace );
    }

    private function getReservedKeyValue(): string {
        $config = new Config();
        $reservedKeys = $config->get( 'reserved_keys' );

        return $reservedKeys[$this->key];
    }

    /**
     * @param array<array<string, mixed>> $trace
     *
     * @throws MessageException
     */
    private function getRequiredTrace( array $trace, string $key ): string {
        $index = null == $this->index ? 0 : $this->index;

        if ( !isset( $trace[$index] ) ) {
            throw new MessageException(
                sprintf( 'Não há o índice %s no backtrace.', $index )
            );
        }

        $trace = $trace[$index];

        if ( !isset( $trace[$key] ) ) {
            throw new MessageException(
                sprintf( 'Não há a chave %s dentro do backtrace no índice %s', $key, $index )
            );
        }

        $trace = $trace[$key];

        return is_string( $trace ) ? $trace : var_export( $trace, true );
    }
}
