<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

class CoreData {
    /**
     * Armazena todos os dados.
     *
     * @var array<string, array<mixed>>
     */
    private static array $data = [];

    public function __construct( ?string $instance ) {
        if ( null != $instance && !isset( self::$data[$instance] ) ) {
            self::$data[$instance] = [];
        }
    }

    public static function get( ?string $instance = null, null|int|string $key = null ): mixed {
        if ( null != $instance ) {
            self::validateInstance( $instance );
        }

        $data = null != $instance ? self::$data[$instance] : self::$data;

        if ( null != $key ) {
            $keys = self::getKeys( $key );
            $response = ArrayUtils::findInArrayWithArrayIndex( $keys, $data );

            if ( !$response['result'] ) {
                throw new \Exception(
                    'Ocorreu um erro inesperado ao tentar recuperar dados.',
                    ExceptionCodes::UNKNOW_CORE_ERROR->value
                );
            }

            $data = $response['data'];
        }

        return $data;
    }

    public static function set( ?string $instance, int|string $key, mixed $value ): void {
        self::validateInstance( $instance );
        /** @var string $instance */
        $keys = self::getKeys( $key );
        $array = ArrayUtils::createArrayWithArrayIndex( $keys, $value );

        // Validação extra para garantir que seja um array.
        $instanceData = self::$data[$instance];
        self::$data[$instance] = array_merge_recursive( $instanceData, $array );
    }

    public static function update( ?string $instance, int|string $key, mixed $value ): void {
        self::validateInstance( $instance );
        /** @var string $instance */
        $keys = self::getKeys( $key );
        $array = ArrayUtils::createArrayWithArrayIndex( $keys, $value );

        // Validação extra para garantir que seja um array.
        $instanceData = self::$data[$instance];
        self::$data[$instance] = array_replace_recursive( $instanceData, $array );
    }

    public static function remove( ?string $instance, int|string $key ): void {
        self::validateInstance( $instance );
        /** @var string $instance */
        $keys = self::getKeys( $key );
        $array = self::get( $instance );

        if ( !is_array( $array ) ) {
            throw new \Exception(
                'Ocorreu um erro inesperado ao tentar recuperar dados.',
                ExceptionCodes::UNKNOW_CORE_ERROR->value
            );
        }

        $response = ArrayUtils::unsetArrayKeyWithArrayIndex( $keys, $array );

        if ( !$response['result'] || !is_array( $response['data'] ) ) {
            throw new \Exception(
                'Ocorreu um erro inesperado ao tentar recuperar dados.',
                ExceptionCodes::UNKNOW_CORE_ERROR->value
            );
        }

        self::$data[$instance] = $response['data'];
    }

    public static function clear( ?string $instance ): void {
        if ( null != $instance ) {
            self::validateInstance( $instance );
            self::$data[$instance] = [];

            return;
        }

        foreach ( self::$data as $instance => $value ) {
            self::$data[$instance] = [];
        }
    }

    private static function validateInstance( ?string $instance ): void {
        $validator = new CoreValidator( self::$data );
        $validator->validateInstance( $instance );
    }

    /**
     * Summary of getKeys.
     *
     * @return array<int, string>
     */
    private static function getKeys( int|string $key ): array {
        $pattern = CoreConfig::DATA_KEYS_PATTERN->value;

        return ArrayUtils::convertStringToArray( $key, $pattern );
    }
}
