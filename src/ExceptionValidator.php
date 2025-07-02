<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

class ExceptionValidator {
    /**
     * Dados da exceção.
     *
     * @var array<int|string, mixed>
     */
    private array $data;

    /**
     * Item sendo validado.
     */
    private mixed $pointer;

    /**
     * Recupera os dados atuais da exceção.
     *
     * @param array<int|string, mixed> $data
     */
    public function __construct( array $data ) {
        $this->data = $data;
    }

    public function validateKey( mixed $key, bool $has = false ): void {
        $this->pointer = $key;

        $this->isValidKey();

        if ( !$has ) {
            $this->hasKey();
        } else {
            $this->hasNotKey();
        }
    }

    private function isValidKey(): void {
        if ( !is_string( $this->pointer ) && !is_int( $this->pointer ) ) {
            $type = get_debug_type( $this->pointer );

            throw new Exception(
                "Propriedade '\$key' deve ser (string, int), '{$type}' recebido.",
                ExceptionCodes::INVALID_PROPERTY_NAME->value
            );
        }
    }

    private function hasKey(): void {
        if ( isset( $this->data[$this->pointer] ) ) {
            $key = $this->pointer;

            /** @var int|string $key */
            throw new Exception(
                "A propriedade '{$key}' já foi definida.",
                ExceptionCodes::PROPERTY_ALREADY_DEFINED->value
            );
        }
    }

    private function hasNotKey(): void {
        if ( !isset( $this->data[$this->pointer] ) ) {
            $key = $this->pointer;

            /** @var int|string $key */
            throw new Exception(
                "Propriedade '\$key' não encontrada",
                ExceptionCodes::PROPERTY_NOT_FOUND->value
            );
        }
    }
}
