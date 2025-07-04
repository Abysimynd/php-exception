<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

class KeyValidator {
    /**
     * Dados da instancia sendo usada.
     *
     * @var array<mixed>
     */
    private array $data;

    /**
     * Valida a chave sendo usada.
     *
     * @param array<mixed> $data
     */
    public function __construct( array $data ) {
        $this->data = $data;
    }

    public function validate( int|string $key ): void {
        $this->hasKey( $key );
    }

    private function hasKey( int|string $key ): void {
        $pattern = CoreConfig::DATA_KEYS_PATTERN->value;
        $keys = ArrayUtils::convertStringToArray( $key, $pattern );

        if ( !ArrayUtils::hasArrayIndexInArray( $keys, $this->data ) ) {
            throw new \Exception(
                "A chave '{$key}' não foi encontrada.",
                ExceptionCodes::KEY_ALREADY_DEFINED->value
            );
        }
    }
}
