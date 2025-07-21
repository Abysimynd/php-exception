<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Config;

/**
 * Inicia as configurações com seus valores padrão e auxilia na validação das configurações alteradas.
 *
 * Tipos para o phpstan.
 *
 * @phpstan-type ConfigMap array{
 *      max_array_index: int,
 *      array_index_separator: non-empty-string|array<non-empty-string>
 * }
 */
class DefaultConfig {
    /** @var ConfigMap Armazena as configurações padrão */
    private array $default;

    public function __construct() {
        $this->setDefault();
    }

    /**
     * @return ConfigMap
     */
    public function getDefaultConfig(): array {
        return $this->default;
    }

    /**
     * @return array<string>
     */
    public function getDefaultConfigNames(): array {
        return array_keys( $this->default );
    }

    /**
     * Retorna os tipos de valores aceitos por cada configuração.
     *
     * @return array<string, array<string>|string>
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
     * @param ConfigMap $config
     *
     * @return array<string, array<string>|string>
     */
    private function prepareDefaultValuesTypes( array $config ): array {
        $valueTypes = [];

        foreach ( $config as $name => $value ) {
            $valueTypes[$name] = $this->getConfigValueTypes( $value );
        }

        return $valueTypes;
    }

    /**
     * @return string|string[]
     */
    private function getConfigValueTypes( mixed $configValue ): array|string {
        if ( is_array( $configValue ) ) {
            $types = [];

            foreach ( $configValue as $key => $value ) {
                $type = get_debug_type( $value );

                if ( in_array( $type, $types ) ) {
                    continue;
                }

                $types[] = $type;
            }

            return $types;
        }

        return get_debug_type( $configValue );
    }
}
