<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

/**
 * Armazena os dados e fornece as operações de inserção e recuperação dos mesmos.
 */
class Data {
    /** @var array<string, array<mixed>> */
    private array $data = [];

    /**
     * @param array<mixed> $data
     */
    public function setData( string $instance, array $data ): void {
        $this->data[$instance] = $data;
    }

    /**
     * @return array<mixed>
     */
    public function getData( string $instance ): array {
        return $this->data[$instance] ?? [];
    }

    public function clearData( ?string $instance ): void {
        if ( null == $instance ) {
            $this->data = [];

            return;
        }

        unset( $this->data[$instance] );
    }
}
