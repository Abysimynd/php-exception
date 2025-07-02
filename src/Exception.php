<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

use Exception as PhpException;

class Exception extends PhpException {
    /**
     * Armazena todos os dados definidos pelo usuario.
     *
     * @var array<int|string, mixed>
     */
    protected static array $data = [];

    public function __construct( string $message = '', int $code = 0, ?\Throwable $previous = null ) {
        if ( isset( self::$data[Config::EXCEPTION_MESSAGE_KEY->value] ) ) {
            $message = '' == $message ? self::$data[Config::EXCEPTION_MESSAGE_KEY->value] : $message;
        }

        /** @var string $message */
        parent::__construct( $message, $code, $previous );
    }

    public function __set( string $key, mixed $value ): void {
        $this::set( $key, $value );
    }

    public function __get( string $key ): mixed {
        return $this::get( $key );
    }

    public static function set( int|string $key, mixed $value, bool $templateData = false ): void {
        $validator = new ExceptionValidator( self::$data );
        $validator->validateKey( $key );

        if ( $templateData ) {
            self::$data[Config::TEMPLATE_DATA_KEY->value] = [];
            self::$data[Config::TEMPLATE_DATA_KEY->value][$key] = $value;

            return;
        }

        self::$data[$key] = $value;
    }

    public static function get( int|string $key ): mixed {
        $validator = new ExceptionValidator( self::$data );
        $validator->validateKey( $key, true );

        return self::$data[$key];
    }

    public static function remove( int|string $key ): void {
        $validator = new ExceptionValidator( self::$data );
        $validator->validateKey( $key, true );

        unset( self::$data[$key] );
    }

    public static function clear(): void {
        self::$data = [];
    }

    public static function createMessage( string $messageTemplate ): void {
        $message = new MessageTemplate( $messageTemplate );
        $message = $message->build();
        self::set( Config::EXCEPTION_MESSAGE_KEY->value, $message );
    }
}
