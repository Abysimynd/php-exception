<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Config;

class DefaultConfig {
    private array $default;

    public function __construct() {
        $this->setDefault();
    }

    public function getDefaultConfig(): array {
        return $this->default;
    }

    public function getDefaultConfigNames(): array {
        return array_keys( $this->default );
    }

    public function getDefaultConfigValuesType(): array {
        return $this->prepareDefaultValuesTypes( $this->default );
    }

    private function setDefault(): void {
        $this->default = [
            'max_array_index' => 3,
            'array_index_separator' => ['.', '->', '=>'],
        ];
    }

    private function prepareDefaultValuesTypes( array $config ): array {
        $valuesType = [];

        foreach ( $config as $name => $value ) {
            if ( !is_array( $value ) ) {
                $type = get_debug_type( $value );

                if ( !in_array( $type, $valuesType ) ) {
                    $valuesType[$name] = $type;
                }

                continue;
            }

            $types = $this->prepareDefaultValuesTypes( $value );
            $valuesType[$name] = ['array' => $types];
        }

        return $valuesType;
    }
}
