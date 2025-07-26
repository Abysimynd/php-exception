<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

/**
 * Fornece os métodos de gerenciamento de dados.
 */
class PrepareData {
    /**
     * Cria um array de arrays com base no caminho passado e coloca o valor no ultimo índice.
     *
     * @param array<int|string> $path
     *
     * @return array<mixed>
     */
    public function createArrayWithPath( array $path, mixed $value ): array {
        $array = [];
        $set = true;

        foreach ( array_reverse( $path ) as $i => $key ) {
            $array = $set ? [$key => $value] : [$key => $array];
            $set = false;
        }

        return $array;
    }

    /**
     * Atualiza o valor do caminho dentro do array.
     *
     * @param array<int|string> $path
     * @param array<mixed>      $array
     *
     * @return array<mixed>
     */
    public function updatePathInArray( array $path, array $array, mixed $value ): array {
        $key = array_shift( $path );

        /** @var array<mixed> - Não validação, ela deve ser feita antes garantindo que o caminho exista */
        $nextArray = $array[$key];

        if ( !empty( $path ) ) {
            $array[$key] = $this->updatePathInArray( $path, $nextArray, $value );

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
    public function removePathInArray( array $path, array $array ): array {
        $key = array_shift( $path );

        /** @var array<mixed> - Não validação, ela deve ser feita antes garantindo que o caminho exista */
        $nextArray = $array[$key];

        if ( !empty( $path ) ) {
            $array[$key] = $this->removePathInArray( $path, $nextArray );

            return $array;
        }

        unset( $array[$key] );

        return $array;
    }

    /**
     * Retorna o valor do caminho no array.
     *
     * @param array<int|string> $path
     * @param array<mixed>      $array
     */
    public static function getPathValueInArray( array $path, array $array ): mixed {
        $value = $array;

        foreach ( $path as $i => $key ) {
            /** @var array<mixed> - Não validação, ela deve ser feita antes garantindo que o caminho exista */
            $value = $value[$key];
        }

        return $value;
    }
}
