<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Config;

/**
 * Controla as configurações de execução.
 *
 * Tipos para o phpstan.
 *
 * @phpstan-type ConfigMap array{
 *      max_array_index: int,
 *      array_index_separator: non-empty-string|array<non-empty-string>
 * }
 * @phpstan-type ReservedConfigMap array{
 *      reserved_keys: array<non-empty-string>
 * }
 */
class Config {
    /** @var ConfigMap Armazena todas as configurações */
    private array $config;

    /** @var ReservedConfigMap Armazena todas as configurações reservadas e imutáveis */
    private array $reservedConfig;

    /**
     * Recupera e defini as configurações de execução padrões.
     */
    public function __construct() {
        $default = new DefaultConfig();
        $this->config = $default->getDefaultConfig();
        $this->reservedConfig = $default->getReservedConfig();
    }

    /**
     * Substitui as configurações existentes pelas recebidas se as mesmas forem validas.
     *
     * @param array<mixed> $config
     */
    public function setConfig( array $config ): void {
        new ConfigValidator( $config );
        $config = array_merge( $this->config, $config );
        $this->config = $config;
    }

    /**
     * @template TKey of string ConfigMap
     *
     * @param TKey $config
     *
     * @return ConfigMap[TKey]
     *
     * @throws ConfigException
     */
    public function getConfig( string $config ): mixed {
        if ( !isset( $this->config[$config] ) ) {
            throw new ConfigException(
                "A configuração {$config} não existe."
            );
        }

        return $this->config[$config];
    }

    /**
     * @template TKey of string ReservedConfigMap
     *
     * @param TKey $config
     *
     * @return ReservedConfigMap[TKey]
     *
     * @throws ConfigException
     */
    public function getReservedConfig( string $config ): mixed {
        if ( !isset( $this->reservedConfig[$config] ) ) {
            throw new ConfigException(
                "A configuração {$config} não existe."
            );
        }

        return $this->reservedConfig[$config];
    }
}
