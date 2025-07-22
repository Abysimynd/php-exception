<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Template;

use KeilielOliveira\Exception\Config\Config;

class ReservedKeysControl {
    private Config $config;

    public function __construct( Config $config ) {
        $this->config = $config;
    }

    public function getKeyValue( string $key, ?string $index ): string {
        $index = $index ?? 0;

        $trace = $this->getTrace();
        $reservedKeys = $this->config->getReservedConfig( 'reserved_keys' );
        $key = $reservedKeys[$key];
        $this->hasTrace( $trace, $index, $key );
        $response = $trace[$index][$key];

        return is_string( $response ) ? $response : var_export( $response, true );
    }

    /**
     * @return list<array{
     *      function: string,
     *      line?: int,
     *      file?: string,
     *      class?:class-string,
     *      type?: '->'|'::',
     *      args?: array<mixed>,
     *      object?: object
     * }>
     */
    private function getTrace(): array {
        $trace = debug_backtrace();
        $callback = function ( array $trace ): bool {
            $class = [MessageTemplate::class, ReservedKeysControl::class];

            if ( isset( $trace['class'] ) && in_array( $trace['class'], $class ) ) {
                return false;
            }

            return true;
        };

        return array_values( array_filter( $trace, $callback ) );
    }

    /**
     * @param array<int, array<string, mixed>> $trace
     * @param int                              $index
     *
     * @throws TemplateException
     */
    private function hasTrace( array $trace, int|string $index, string $key ): void {
        if ( !isset( $trace[$index] ) ) {
            throw new TemplateException( "Não foi possível encontrar o índice {$index} no rastro de execução." );
        }

        $trace = $trace[$index];

        if ( !isset( $trace[$key] ) ) {
            throw new TemplateException( "não foi possível encontrar o item {$key} dentro do rastro de execução no índice {$index}" );
        }
    }
}
