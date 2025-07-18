<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Data;

use KeilielOliveira\Exception\Config\Config;
use KeilielOliveira\Exception\Dependencies\DependenciesContainer;
use KeilielOliveira\Exception\Helpers\ArrayHelper;
use KeilielOliveira\Exception\Instances\InstanceControl;

class DataControl {
    /** @var DependenciesContainer Container de classes */
    private DependenciesContainer $container; // @phpstan-ignore property.onlyRead

    /** @var array<string, array<mixed>> Armazena todos os dados */
    private array $data;

    /**
     * Salva um dado dentro da chave na instancia atual.
     */
    public function setData( int|string $key, mixed $value ): void {
        $instance = $this->getInstance();
        [$keys, $data] = $this->prepareArgs( $key, $instance );

        $array = ArrayHelper::createArrayWithPath( $keys, $value );
        $this->data[$instance] = array_merge_recursive( $data, $array );
    }

    /**
     * Atualiza o valor da chave na instancia atual.
     */
    public function updateData( int|string $key, mixed $value ): void {
        $instance = $this->getInstance();
        [$keys, $data] = $this->prepareArgs( $key, $instance );

        $this->hasPathInArrayValidation( $keys, $data, $instance );

        $array = ArrayHelper::updatePathInArray( $keys, $data, $value );
        $this->data[$instance] = $array;
    }

    /**
     * Remove a chave na instancia atual.
     */
    public function removeData( int|string $key ): void {
        $instance = $this->getInstance();
        [$keys, $data] = $this->prepareArgs( $key, $instance );

        $this->hasPathInArrayValidation( $keys, $data, $instance );

        $array = ArrayHelper::removePathInArray( $keys, $data );
        $this->data[$instance] = $array;
    }

    /**
     * Retorna o valor da chave na instancia atual.
     */
    public function getData( null|int|string $key = null ): mixed {
        $instance = $this->getInstance();
        [$keys, $data] = $this->prepareArgs( $key, $instance );

        if ( empty( $keys ) ) {
            return $data;
        }

        $this->hasPathInArrayValidation( $keys, $data, $instance );

        return ArrayHelper::getPathValueInArray( $keys, $data );
    }

    /**
     * Deleta a instancia atual ou todos os dados.
     */
    public function clearData( bool $allInstances = false ): void {
        if ( !$allInstances ) {
            $instance = $this->getInstance();
            unset( $this->data[$instance] );

            return;
        }

        $this->data = [];
    }

    /**
     * Retorna a instancia atual.
     */
    private function getInstance(): string {
        $instanceControl = $this->container->getDependencie( InstanceControl::class );

        return $instanceControl->getValidInstance();
    }

    /**
     * Prepara os argumentos usados nas operações.
     *
     * @return array{0: array<int|string>, 1: array<mixed>}
     */
    private function prepareArgs( null|int|string $key, string $instance ): array {
        $this->setInstanceInData( $instance );

        $keys = null == $key ? [] : $this->getKeys( (string) $key );
        $data = $this->data[$instance];

        return [$keys, $data];
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
        $config = $this->container->getDependencie( Config::class );
        $separators = $config->getConfig( 'array_index_separator' );
        $separators = is_array( $separators ) ? $separators : [$separators];
        $keys = [];

        foreach ( $separators as $i => $separator ) {
            /** @var non-empty-string $separator */
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

        /** @var int $maxIndex */
        $maxIndex = $config->getConfig( 'max_array_index' );

        if ( count( $keys ) - 1 > $maxIndex ) {
            throw new DataException(
                sprintf(
                    'A chave %s excedeu o maximo de indices permitidos: (max: %s, keys: %s).',
                    $key,
                    $maxIndex,
                    count( $keys ) - 1
                )
            );
        }

        return $keys;
    }

    /**
     * Valida se o caminho existe dentro do array.
     *
     * @param array<int|string> $keys
     * @param array<mixed>      $data
     *
     * @throws DataException
     */
    private function hasPathInArrayValidation( array $keys, array $data, string $instance ): void {
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
}
