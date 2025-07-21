<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Data;

use KeilielOliveira\Exception\Config\Config;
use KeilielOliveira\Exception\Helpers\ArrayHelper;
use KeilielOliveira\Exception\Instances\InstanceControl;

/**
 * Gerencia todos os dados.
 */
class DataControl {
    private DataHelpers $helper;

    /** @var array<string, array<mixed>> Armazena todos os dados */
    private array $data = [];

    public function __construct( Config $config, InstanceControl $instanceControl ) {
        $this->helper = new DataHelpers( $config, $instanceControl );
    }

    /**
     * Salva um dado dentro da chave na instancia atual.
     */
    public function setData( int|string $key, mixed $value ): void {
        $instance = $this->helper->getInstance();
        [$keys, $data] = $this->helper->prepareArgs( $this->data, $key, $instance );

        $array = ArrayHelper::createArrayWithPath( $keys, $value );
        $this->data[$instance] = array_merge_recursive( $data, $array );
    }

    /**
     * Atualiza o valor da chave na instancia atual.
     */
    public function updateData( int|string $key, mixed $value ): void {
        $instance = $this->helper->getInstance();
        [$keys, $data] = $this->helper->prepareArgs( $this->data, $key, $instance );

        $this->helper->hasPathInArrayValidation( $keys, $data, $instance );

        $array = ArrayHelper::updatePathInArray( $keys, $data, $value );
        $this->data[$instance] = $array;
    }

    /**
     * Remove a chave na instancia atual.
     */
    public function removeData( int|string $key ): void {
        $instance = $this->helper->getInstance();
        [$keys, $data] = $this->helper->prepareArgs( $this->data, $key, $instance );

        $this->helper->hasPathInArrayValidation( $keys, $data, $instance );

        $array = ArrayHelper::removePathInArray( $keys, $data );
        $this->data[$instance] = $array;
    }

    /**
     * Retorna o valor da chave na instancia atual.
     */
    public function getData( null|int|string $key = null ): mixed {
        $instance = $this->helper->getInstance();
        [$keys, $data] = $this->helper->prepareArgs( $this->data, $key, $instance );

        if ( empty( $keys ) ) {
            return $data;
        }

        $this->helper->hasPathInArrayValidation( $keys, $data, $instance );

        return ArrayHelper::getPathValueInArray( $keys, $data );
    }

    /**
     * Deleta a instancia atual ou todos os dados.
     */
    public function clearData( bool $allInstances = false ): void {
        if ( !$allInstances ) {
            $instance = $this->helper->getInstance();
            unset( $this->data[$instance] );

            return;
        }

        $this->data = [];
    }
}
