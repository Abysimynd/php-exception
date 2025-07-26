<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

use KeilielOliveira\Exception\Exceptions\ConfigException;

/**
 * Configurações de execução da biblioteca.
 *
 * @phpstan-type ConfigMap array{
 *      array_index_separator: array<non-empty-string>,
 *      max_array_index: int,
 *      reserved_keys: array<non-empty-string>
 * }
 */
class Config {
    /** @var ConfigMap Configurações de execução */
    private array $config;

    public function __construct() {
        $this->config = [
            'array_index_separator' => [
                '.', '->', '=>', '>',
            ],
            'max_array_index' => 5,
            'reserved_keys' => [
                '__FUNCTION__' => 'function', '__LINE__' => 'line', '__FILE__' => 'file', '__CLASS__' => 'class', '__OBJECT__' => 'object', '__TYPE__' => 'type', '__ARGS__' => 'args',
            ],
        ];
    }

    /**
     * Retorna a configuração caso exista ou todas as configurações se for null.
     *
     * @template TKey of key-of<ConfigMap>|null
     *
     * @param TKey $config
     *
     * @return (TKey is null ? ConfigMap : ConfigMap[TKey] )
     */
    public function get( ?string $config = null ): mixed {
        $this->hasConfig( $config );

        return null == $config ? $this->config : $this->config[$config];
    }

    private function hasConfig( ?string $config ): void {
        if ( null != $config && !isset( $this->config[$config] ) ) {
            throw new ConfigException(
                sprintf( 'A configuração %s não existe.', $config )
            );
        }
    }
}
