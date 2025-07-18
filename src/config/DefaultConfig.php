<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Config;

/**
 * @phpstan-type PrimitiveConfigType non-empty-string|int|float|bool|null
 * @phpstan-type ConfigType array<string, PrimitiveConfigType|array<PrimitiveConfigType>>
 */
class DefaultConfig {
    /** @var ConfigType Configurações padrão */
    private array $default;

    public function __construct() {
        $this->setDefault();
    }

    /**
     * Retorna as configurações padrão.
     *
     * @return ConfigType
     */
    public function getDefaultConfig(): array {
        return $this->default;
    }

    /**
     * Retorna os nomes das configurações existentes.
     *
     * @return array<string>
     */
    public function getDefaultConfigNames(): array {
        return array_keys( $this->default );
    }

    /**
     * Retorna os tipos de valores aceitos por cada configuração existente.
     *
     * Ignorando o erro do phpstan porque o metodo é recursivo e não achei uma forma viavel de definir o tipo de
     * retorno de forma que o phpstan entenda.
     *
     * @return array<string, array{array: array<string>}|string>
     */
    public function getDefaultConfigValuesType(): array {
        return $this->prepareDefaultValuesTypes( $this->default );
    }

    private function setDefault(): void {
        $this->default = [
            'max_array_index' => 3,
            'array_index_separator' => ['.', '->', '=>'],
        ];
    }

    /**
     * Recupera os tipos de valores aceitos por todas as configurações existentes.
     *
     * @param array<PrimitiveConfigType>|ConfigType $config
     *
     * Ignorando o erro do phpstan porque o metodo é recursivo e não achei uma forma viavel de definir o tipo de
     * retorno de forma que o phpstan entenda
     *
     * @return array<string, array{array: array<string>}|string>
     */
    private function prepareDefaultValuesTypes( array $config, int $max = 2 ): array {
        $valuesType = [];
        --$max;

        foreach ( $config as $name => $value ) {
            if ( !is_array( $value ) ) {
                $type = get_debug_type( $value );

                if ( !in_array( $type, $valuesType ) ) {
                    $valuesType[$name] = $type;
                }

                continue;
            }

            if ( 0 == $max ) {
                throw new InvalidConfigException(
                    'Ocorreu um erro inesperado ao preparar as configurações.'
                );
            }

            $types = $this->prepareDefaultValuesTypes( $value, $max );
            $valuesType[$name] = ['array' => $types];
        }

        return $valuesType; // @phpstan-ignore return.type
    }
}
