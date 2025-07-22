<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Template;

use KeilielOliveira\Exception\Data;

class DataKeysControl {
    private Data $data;

    public function __construct( Data $data ) {
        $this->data = $data;
    }

    public function getKeyValue( string $key, ?string $index ): string {
        if ( null != $index ) {
            $this->data->in( $index );
        }

        $response = $this->data->get( $key );

        return is_string( $response ) ? $response : var_export( $response, true );
    }
}
