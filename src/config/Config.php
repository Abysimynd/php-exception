<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Config;

use KeilielOliveira\Exception\Exceptions\ConfigException;

/**
 * Controla as configurações de execução.
 *
 * Tipos para o phpstan.
 *
 * @phpstan-type ConfigMap array{
 *      max_array_index: int,
 *      array_index_separator: non-empty-string|array<non-empty-string>,
 *      reserved_keys: array<non-empty-string>
 * }
 */
class Config {
    /** @var ConfigMap Armazena todas as configurações */
    private array $config;

    /**
     * Recupera e defini as configurações de execução padrões.
     */
    public function __construct() {
        $default = new DefaultConfig();
        $this->config = array_merge(
            $default->getDefaultConfig(),
            $default->getReservedConfig()
        );
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
}
