<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Config;

/**
 * Tipos para o phpstan.
 *
 * @phpstan-type ConfigMap array{
 *      max_array_index: int,
 *      array_index_separator: non-empty-string|array<non-empty-string>
 * }
 */
class ConfigValidator {
    /** @var array<mixed> */
    private array $config;

    /**
     * Valida as configurações recebidas.
     *
     * Validações:
     * 1. Valida se é uma configuração que existe.
     * 2. Valida se o valor passado para a configuração é aceito.
     * 3. Valida se o valor passado para á configuração não está vazio.
     *
     * @param array<mixed> $config
     *
     * @phpstan-assert ConfigMap $config
     */
    public function __construct( array $config ) {
        $this->config = $config;
        $this->validate();
    }

    private function validate(): void {
        foreach ( $this->config as $name => $value ) {
            $this->isValidConfigName( $name );
            $this->isValidConfigValueType( $name, $value );
            $this->isValidConfigValue( $name, $value );
        }
    }

    /**
     * @throws ConfigException
     */
    private function isValidConfigName( int|string $name ): void {
        $defaultNames = new DefaultConfig()->getDefaultConfigNames();

        if ( !in_array( $name, $defaultNames ) ) {
            throw new ConfigException(
                "A configuração {$name} não existe."
            );
        }
    }

    private function isValidConfigValueType( int|string $name, mixed $value ): void {
        $validTypes = new DefaultConfig()->getDefaultConfigValuesType()[$name];
        $value = is_array( $value ) ? $value : [$value];
        $isArray = is_array( $validTypes );

        foreach ( $value as $key => $type ) {
            $type = get_debug_type( $type );
            $validTypes = is_array( $validTypes ) ? $validTypes : [$validTypes];

            if ( !in_array( $type, $validTypes ) ) {
                if ( !$isArray ) {
                    throw new ConfigException(
                        sprintf(
                            'A configuração %s espera receber (%s).',
                            $name,
                            implode( ' ,', $validTypes )
                        )
                    );
                }

                throw new ConfigException(
                    sprintf(
                        'A configuração %s espera receber (%s) ou um array contendo valores desses tipos.',
                        $name,
                        implode( ' ,', $validTypes )
                    )
                );
            }
        }
    }

    private function isValidConfigValue( int|string $name, mixed $value ): void {
        if ( is_string( $value ) && empty( trim( $value ) ) ) {
            throw new ConfigException(
                sprintf( 'A configuração %s espera receber uma string não vazia.', $name )
            );
        }

        if ( is_array( $value ) && empty( $value ) ) {
            throw new ConfigException(
                sprintf( 'A configuração %s espera receber um array não vazio.', $name )
            );
        }

        if ( is_array( $value ) ) {
            $callback = function ( mixed $value ): bool {
                return !empty( $value );
            };

            $filteredArray = array_filter( $value, $callback );

            if ( count( $value ) != count( $filteredArray ) ) {
                throw new ConfigException(
                    sprintf( 'A configuração %s espera receber um array não vazio.', $name )
                );
            }
        }
    }
}
