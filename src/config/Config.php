<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Config;

/**
 * @phpstan-type PrimitiveConfigType string|int|float|bool|null
 * @phpstan-type ConfigType array<string, PrimitiveConfigType|array<PrimitiveConfigType>>
 */
class Config {
    /** @var ConfigType Armazena todas as configurações */
    private array $config;

    /**
     * Defini as configurações padrão.
     */
    public function __construct() {
        $default = new DefaultConfig();
        $this->config = $default->getDefaultConfig();
    }

    /**
     * Defini as configurações customizadas.
     *
     * @param array<mixed> $config
     */
    public function setConfig( array $config ): void {
        new ConfigValidator( $config );

        /** @var ConfigType $config */
        $config = array_merge( $this->config, $config );
        $this->config = $config;
    }

    /**
     * Recupera uma configuração se a mesma existir.
     *
     * @return array<PrimitiveConfigType>|PrimitiveConfigType
     *
     * @throws InvalidConfigException
     */
    public function getConfig( string $config ): mixed {
        if ( !isset( $this->config[$config] ) ) {
            throw new InvalidConfigException(
                "A configuração '{$config}' não existe."
            );
        }

        return $this->config[$config];
    }
}
