<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

class CoreData {
    /**
     * Armazena todos os dados.
     *
     * @var array<string, array<int|string, mixed>>
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

        // Se a instancia for valida.
        $data = null != $instance ? self::$data[$instance] : self::$data;

        if ( null != $key ) {
            self::validateInstance( $instance );
            $pattern = CoreConfig::DATA_KEYS_PATTERN->value;
            $keys = ArrayUtils::convertStringToArray( $key, $pattern );
            $data = ArrayUtils::findInArrayWithArrayIndex( $keys, $data );
        }

        return $data;
    }

    public static function set( ?string $instance, int|string $key, mixed $value ): void {
        self::validateInstance( $instance );
        $pattern = CoreConfig::DATA_KEYS_PATTERN->value;
        $keys = ArrayUtils::convertStringToArray( $key, $pattern );
        $array = ArrayUtils::createArrayWithArrayIndex( $keys, $value );

        self::$data[$instance] = array_merge_recursive( self::$data[$instance], $array );
    }

    public static function update( ?string $instance, int|string $key, mixed $value ): void {
        self::validateInstance( $instance );
        $pattern = CoreConfig::DATA_KEYS_PATTERN->value;
        $keys = ArrayUtils::convertStringToArray( $key, $pattern );
        $array = ArrayUtils::createArrayWithArrayIndex( $keys, $value );

        self::$data[$instance] = array_replace_recursive( self::$data[$instance], $array );
    }

    public static function remove( ?string $instance, int|string $key ): void {
        self::validateInstance( $instance );
        $pattern = CoreConfig::DATA_KEYS_PATTERN->value;
        $keys = ArrayUtils::convertStringToArray( $key, $pattern );

        /** @var array<int|string, mixed> $array */
        $array = self::get( $instance );
        $array = ArrayUtils::unsetArrayKeyWithArrayIndex( $keys, $array );

        self::$data[$instance] = $array;
    }

    public static function clear( ?string $instance ): void {
        if ( null != $instance ) {
            self::validateInstance( $instance );
            self::$data[$instance] = [];

            return;
        }

        self::$data = [];
    }

    private static function validateInstance( ?string $instance ): void {
        $validator = new CoreValidator( self::$data );
        $validator->validateInstance( $instance );
    }
}
