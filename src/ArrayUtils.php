<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

class ArrayUtils {
    /**
     * Converte uma string no formato a->b->c para um array.
     *
     * @param string $string
     *
     * @return array<int, string>
     */
    public static function convertStringToArray( int|string $string, string $pattern ): array {
        $string = (string) $string;
        preg_match_all( $pattern, $string, $matches );

        return $matches[1];
    }

    /**
     * Procura recursivamente as chaves ($keys) dentro do array passado ($array).
     *
     * @param array<int, string> $keys
     * @param array<mixed>       $array
     */
    public static function hasArrayIndexInArray( array $keys, array $array ): bool {
        $result = true;

        if ( !empty( $keys ) ) {
            $key = array_shift( $keys );

            if ( !isset( $array[$key] ) ) {
                return false;
            }

            $array = $array[$key];

            if ( empty( $keys ) ) {
                return true;
            }

            if ( empty( $array ) || !is_array( $array ) ) {
                return false;
            }

            $result = self::hasArrayIndexInArray( $keys, $array );
        }

        return $result;
    }

    /**
     * Cria de forma recursiva um array com as chaves ($keys) e adiciona o valor ($value) no ultimo indice.
     *
     * @param array<int, string> $keys
     *
     * @return array<int|string, mixed>
     */
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

    /**
     * Procura recursivamente as chaves ($keys) dentro do array passado ($array) e deleta a ultima chave.
     *
     * @param array<int, string> $keys
     * @param array<mixed>       $array
     *
     * @return array<mixed>
     */
    public static function unsetArrayKeyWithArrayIndex( array $keys, array $array ): array {
        $key = array_shift( $keys );

        if ( !isset( $array[$key] ) ) {
            return ['result' => false];
        }

        if ( count( $keys ) >= 1 ) {
            $pointer = $array[$key];

            if ( !is_array( $pointer ) ) {
                return ['result' => false];
            }

            $pointer = self::unsetArrayKeyWithArrayIndex( $keys, $pointer );

            if ( $pointer['result'] ) {
                $array[$key] = $pointer['data'];
                $array = ['result' => $pointer['result'], 'data' => $array];
            }

            return $array;
        }

        unset( $array[$key] );

        return ['result' => true, 'data' => $array];
    }

    /**
     * Procura recursivamente as chaves ($keys) dentro do array passado ($array) e retorna o valor da ultima chave.
     *
     * @param array<int, string> $keys
     * @param array<mixed>       $array
     *
     * @return array<string, mixed>
     */
    public static function findInArrayWithArrayIndex( array $keys, array $array ): array {
        if ( !empty( $keys ) ) {
            $key = array_shift( $keys );

            if ( !isset( $array[$key] ) ) {
                return ['result' => false];
            }

            $array = $array[$key];

            if ( !is_array( $array ) && !empty( $keys ) ) {
                echo 'Aqui1';

                return ['result' => false];
            }

            if ( empty( $keys ) ) {
                return ['result' => true, 'data' => $array];
            }

            return self::findInArrayWithArrayIndex( $keys, $array );
        }

        return ['result' => true, 'data' => $array];
    }
}
