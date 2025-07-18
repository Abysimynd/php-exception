<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Helpers;

class ArrayHelper {
    /**
     * Cria um array de arrays com base no caminho passado e coloca o valor no ultimo indice.
     *
     * @param array<int|string> $path
     *
     * @return array<mixed>
     */
    public static function createArrayWithPath( array $path, mixed $value ): array {
        $array = [];
        $set = true;

        foreach ( array_reverse( $path ) as $i => $key ) {
            $array = $set ? [$key => $value] : [$key => $array];
            $set = false;
        }

        return $array;
    }

    /**
     * Verifica se o caminho existe dentro do array.
     *
     * @param array<int|string> $path
     * @param array<mixed>      $array
     */
    public static function hasPathInArray( array $path, array $array ): bool {
        $key = array_shift( $path );

        if ( !isset( $array[$key] ) ) {
            return false;
        }

        $array = $array[$key];

        if ( !empty( $path ) ) {
            if ( !is_array( $array ) ) {
                return false;
            }

            return self::hasPathInArray( $path, $array );
        }

        return true;
    }

    /**
     * Atualiza o valor do caminho dentro do array.
     *
     * @param array<int|string> $path
     * @param array<mixed>      $array
     *
     * @return array<mixed>
     */
    public static function updatePathInArray( array $path, array $array, mixed $value ): array {
        $key = array_shift( $path );

        if ( !empty( $path ) ) {
            /** @var array<mixed> $newArray */
            $newArray = $array[$key];
            $array[$key] = self::updatePathInArray( $path, $newArray, $value );

            return $array;
        }

        $array[$key] = $value;

        return $array;
    }

    /**
     * Remove o caminho passado de dentro do array.
     *
     * @param array<int|string> $path
     * @param array<mixed>      $array
     *
     * @return array<mixed>
     */
    public static function removePathInArray( array $path, array $array ): array {
        $key = array_shift( $path );

        if ( !empty( $path ) ) {
            /** @var array<mixed> $newArray */
            $newArray = $array[$key];
            $array[$key] = self::removePathInArray( $path, $newArray );

            return $array;
        }

        unset( $array[$key] );

        return $array;
    }

    /**
     * Retorna o valor do caminho no array
     * @param array<int|string> $path
     * @param array<mixed> $array
     * @return mixed
     */
    public static function getPathValueInArray( array $path, array $array ): mixed {
        $value = $array;

        foreach ( $path as $i => $key ) {
            /** @var array<mixed> */
            $value = $value[$key];
        }

        return $value;
    }
}
