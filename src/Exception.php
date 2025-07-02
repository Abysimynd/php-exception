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

    /**
     * Lança uma exception padrão do php.
     */
    public function __construct( string $message = '', int $code = 0, ?\Throwable $previous = null ) {
        if ( isset( self::$data[Config::EXCEPTION_MESSAGE_KEY->value] ) ) {
            $message = '' == $message ? self::$data[Config::EXCEPTION_MESSAGE_KEY->value] : $message;
        }

        /** @var string $message */
        parent::__construct( $message, $code, $previous );
    }

    /**
     * Defini uma propriedad que pode ser recuperada posterioromente.
     */
    public function __set( string $key, mixed $value ): void {
        $this::set( $key, $value );
    }

    /**
     * Recupera uma propriedade definida anteriormente.
     */
    public function __get( string $key ): mixed {
        return $this::get( $key );
    }

    /**
     * Defini uma propriedad que pode ser recuperada posterioromente.
     *
     * @param bool $templateData Defini se a propriedade será usada na construção de uma mensagem de exceção
     */
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

    /**
     * Recupera uma propriedade definida anteriormente.
     */
    public static function get( int|string $key ): mixed {
        $validator = new ExceptionValidator( self::$data );
        $validator->validateKey( $key, true );

        return self::$data[$key];
    }

    /**
     * Remove uma propriedade definida anteriormente.
     */
    public static function remove( int|string $key ): void {
        $validator = new ExceptionValidator( self::$data );
        $validator->validateKey( $key, true );

        unset( self::$data[$key] );
    }

    /**
     * Limpa todas as propriedades definidas.
     */
    public static function clear(): void {
        self::$data = [];
    }

    /**
     * Cria uma mensagem de exceção com base em um template e parametros definidos.
     */
    public static function createMessage( string $messageTemplate ): void {
        $message = new MessageTemplate( $messageTemplate );
        $message = $message->build();
        self::set( Config::EXCEPTION_MESSAGE_KEY->value, $message );
    }

    /**
     * Retorna uma instancia da classe.
     */
    public static function self(): self {
        return new Exception();
    }
}
