<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Data;

use KeilielOliveira\Exception\Config\Config;
use KeilielOliveira\Exception\Helpers\ArrayHelper;
use KeilielOliveira\Exception\Instances\InstanceControl;

class DataHelpers {
    /** @var array<string, array<mixed>> Dados salvos */
    private array $data;

    private Config $config;
    private InstanceControl $instanceControl;

    public function __construct( Config $config, InstanceControl $instanceControl ) {
        $this->config = $config;
        $this->instanceControl = $instanceControl;
    }

    /**
     * Retorna a instancia atual.
     */
    public function getInstance(): string {
        return $this->instanceControl->getValidInstance();
    }

    /**
     * Prepara os argumentos usados nas operações.
     *
     * @param array<string, array<mixed>> $data
     *
     * @return array{0: array<int|string>, 1: array<mixed>}
     */
    public function prepareArgs( array $data, null|int|string $key, string $instance ): array {
        $this->data = $data;
        $this->setInstanceInData( $instance );

        $keys = null == $key ? [] : $this->getKeys( (string) $key );
        $data = $this->data[$instance];

        return [$keys, $data];
    }

    /**
     * Valida se o caminho existe dentro do array.
     *
     * @param array<int|string> $keys
     * @param array<mixed>      $data
     *
     * @throws DataException
     */
    public function hasPathInArrayValidation( array $keys, array $data, string $instance ): void {
        if ( !ArrayHelper::hasPathInArray( $keys, $data ) ) {
            throw new DataException(
                sprintf(
                    'Não foi possível localizar o caminho %s dentro da instance %s.',
                    implode( ' -> ', $keys ),
                    $instance
                )
            );
        }
    }

    /**
     * Defini a instancia dentro dos dados.
     */
    private function setInstanceInData( string $instance ): void {
        if ( !isset( $this->data[$instance] ) ) {
            $this->data[$instance] = [];
        }
    }

    /**
     * Recupera as chaves que serão usadas nas operações.
     *
     * @return array<string>
     *
     * @throws DataException
     */
    private function getKeys( string $key ): array {
        $keys = [];
        $separators = $this->getArrayIndexSeparators();

        foreach ( $separators as $i => $separator ) {
            if ( empty( $keys ) ) {
                $keys = explode( $separator, $key );

                continue;
            }

            $newKeys = [];

            foreach ( $keys as $i => $value ) {
                $newKeys = array_merge( $newKeys, explode( $separator, $value ) );
            }
            $keys = $newKeys;
        }

        $this->validateKeys( $keys, $key );

        return $keys;
    }

    /**
     * @return array<non-empty-string>
     *
     * @throws DataException
     */
    private function getArrayIndexSeparators(): array {
        $separators = $this->config->getConfig( 'array_index_separator' );

        return is_array( $separators ) ? $separators : [$separators];
    }

    /**
     * @param array<string> $keys
     *
     * @throws DataException
     */
    private function validateKeys( array $keys, string $key ): void {
        $maxIndex = $this->config->getConfig( 'max_array_index' );

        if ( count( $keys ) - 1 > $maxIndex ) {
            throw new DataException(
                sprintf(
                    'A chave %s excedeu o máximo de indices permitidos: (max: %s, keys: %s).',
                    $key,
                    $maxIndex,
                    count( $keys ) - 1
                )
            );
        }
    }
}
