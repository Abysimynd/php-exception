<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Config;

class InvalidConfigException extends \Exception {
    public function __construct( string $message ) {
        parent::__construct( $message );
    }

    public function if( bool $condition, string $message ): self {
        if ( $condition ) {
            $newMessage = $this->getMessage();
            $newMessage = substr( $newMessage, 0, -1 );
            $newMessage .= ' ' . $message;
            parent::__construct( $newMessage );
        }

        return $this;
    }
}
