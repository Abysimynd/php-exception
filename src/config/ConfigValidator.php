<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Config;

class ConfigValidator {
    /** @var array<mixed> Configuraões sendo validadas */
    private array $config;

    /**
     * Valida as configurações passadas.
     *
     * @param array<mixed> $config
     */
    public function __construct( array $config ) {
        $this->config = $config;
        $this->validate();
    }

    private function validate(): void {
        foreach ( $this->config as $name => $value ) {
            $this->isValidConfigName( $name );
            $this->isValidConfigValueType( $name, $value );
        }
    }

    private function isValidConfigName( int|string $name ): void {
        $defaultNames = new DefaultConfig()->getDefaultConfigNames();

        if ( !in_array( $name, $defaultNames ) ) {
            throw new InvalidConfigException(
                "A configuração '{$name}' não existe."
            );
        }
    }

    private function isValidConfigValueType( int|string $name, mixed $value ): void {
        $validTypes = new DefaultConfig()->getDefaultConfigValuesType()[$name];
        $value = is_array( $value ) ? $value : [$value];
        $isArray = false;

        foreach ( $value as $key => $type ) {
            $type = get_debug_type( $type );

            if ( isset( $validTypes['array'] ) && is_array( $validTypes['array'] ) ) {
                $isArray = true;
                $validTypes = $validTypes['array'];
            }

            /** @var array<string> $validTypes */
            $validTypes = is_array( $validTypes ) ? $validTypes : [$validTypes];

            if ( !in_array( $type, $validTypes ) ) {
                throw new InvalidConfigException(
                    sprintf(
                        "A configuração '%s' espera receber (%s).",
                        $name,
                        implode( ' ,', $validTypes )
                    )
                )->if( $isArray, 'ou um array contendo valores desse(s) tipo(s).' );
            }
        }
    }
}
