<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Message;

use KeilielOliveira\Exception\Config\Config;
use KeilielOliveira\Exception\Container;
use KeilielOliveira\Exception\Core;
use KeilielOliveira\Exception\Exceptions\MessageBuilderException;

/**
 * Substitui as marcações de rastros da execução do código no template.
 */
class ReservedKeysReplace implements MessageReplaceInterface {
    private string $template;
    private string $key;
    private ?string $index;

    public function __construct( string $template, string $key, ?string $index ) {
        $this->template = $template;
        $this->key = $key;
        $this->index = $index;
    }

    public function getReplacedTemplate(): string {
        $valueToReplace = $this->getReplaceValue();
        $pattern = $this->getReplacePattern();
        $template = preg_replace( $pattern, $valueToReplace, $this->template );

        if ( !is_string( $template ) ) {
            throw new MessageBuilderException(
                "Ocorreu um erro ao substituir a chave {$pattern} pelo seu valor."
            );
        }

        return $template;
    }

    private function getReplaceValue(): string {
        $keyToReplace = $this->getReservedKeyValue();
        $trace = $this->getTrace();
        $value = $this->getRequiredTrace( $keyToReplace, $trace );

        return is_string( $value ) ? $value : var_export( $value, true );
    }

    private function getReservedKeyValue(): string {
        return Container::getContainer()->get( Config::class )
            ->getConfig( 'reserved_keys' )[$this->key]
        ;
    }

    /**
     * @return array<array<mixed>>
     */
    private function getTrace(): array {
        $trace = debug_backtrace();
        $callback = function ( array $trace ): bool {
            $excludeClass = [
                ReservedKeysReplace::class, MessageBuilder::class, Core::class,
            ];

            if ( isset( $trace['class'] ) && in_array( $trace['class'], $excludeClass ) ) {
                return false;
            }

            return true;
        };

        return array_values( array_filter( $trace, $callback ) );
    }

    /**
     * @param array<array<mixed>> $trace
     *
     * @throws MessageBuilderException
     */
    private function getRequiredTrace( string $keyToReplace, array $trace ): mixed {
        $index = $this->index ?? 0;

        if ( !isset( $trace[$index] ) ) {
            throw new MessageBuilderException(
                "Não há o índice {$index} no rastro de execução atual."
            );
        }

        $trace = $trace[$index];

        if ( !isset( $trace[$keyToReplace] ) ) {
            throw new MessageBuilderException(
                "Não há o item {$keyToReplace} no rastro de execução do índice {$index}."
            );
        }

        return $trace[$keyToReplace];
    }

    private function getReplacePattern(): string {
        if ( null != $this->index ) {
            return sprintf( '/\{%s\[%s\]\}/', $this->key, $this->index );
        }

        return sprintf( '/\{%s\}/', $this->key );
    }
}
