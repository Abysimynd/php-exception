<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

class ArrayUtils {
    public static function convertStringToArray( string $string, string $pattern ): array {
        preg_match_all( $pattern, $string, $matches );

        return $matches[1];
    }

    public static function hasArrayIndexInArray( array $keys, array $array ): bool {
        $result = false;

        if ( !empty( $array ) ) {
            $key = array_shift( $keys );

            if ( !isset( $array[$key] ) ) {
                return false;
            }

            $array = $array[$key];

            if ( !is_array( $array ) ) {
                return true;
            }

            $result = self::hasArrayIndexInArray( $keys, $array );
        }

        return $result;
    }

    public static function createArrayWithArrayIndex( array $keys, mixed $value ): array {
        $result = [];

        if ( count( $keys ) > 1 ) {
            $key = array_shift( $keys );

            $result[$key] = self::createArrayWithArrayIndex( $keys, $value );

            return $result;
        }

        $key = array_shift( $keys );
        $result[$key] = $value;

        return $result;
    }

    public static function unsetArrayKeyWithArrayIndex( array $keys, array $array ): array {
        if ( count( $keys ) > 1 ) {
            $key = array_shift( $keys );
            $array[$key] = self::unsetArrayKeyWithArrayIndex( $keys, $array[$key] );

            return $array;
        }

        $key = array_shift( $keys );
        unset( $array[$key] );

        return $array;
    }

    public static function findInArrayWithArrayIndex( array $keys, array $array ): mixed {
        if ( !empty( $keys ) ) {
            $key = array_shift( $keys );

            $array = $array[$key];

            if ( empty( $keys ) ) {
                return $array;
            }
            $array = self::findInArrayWithArrayIndex( $keys, $array );
        }

        return $array;
    }
}
